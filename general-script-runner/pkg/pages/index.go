package pages

import (
	"bufio"
	"bytes"
	"fmt"
	"io"
	"log"
	"os/exec"
	"sync"
	"time"

	"github.com/labstack/echo/v4"
	"golang.org/x/net/websocket"

	"general-script-runner/pkg/models"
)

var (
	Scripts map[string]models.Script = map[string]models.Script{}
	done                             = make(chan bool, 1)
)

type Connection struct {
	IsOpen     bool
	Disconnect chan bool
}

type Connections struct {
	mx sync.RWMutex
	m  map[*websocket.Conn]*Connection
}

func NewConnections() *Connections {
	return &Connections{
		m: make(map[*websocket.Conn]*Connection),
	}
}

func (c *Connections) IsConnected(key *websocket.Conn) (*Connection, bool) {
	c.mx.Lock()
	defer c.mx.Unlock()
	val, ok := c.m[key]
	return val, ok
}

func (c *Connections) New(key *websocket.Conn) {
	c.mx.Lock()
	defer c.mx.Unlock()

	c.m[key] = &Connection{
		IsOpen:     true,
		Disconnect: make(chan bool, 1),
	}
}

func (c *Connections) Delete(key *websocket.Conn) {
	c.mx.Lock()
	defer c.mx.Unlock()
	delete(c.m, key)
}

func (c *Connections) Broadcast(msg string) bool {
	c.mx.Lock()
	defer c.mx.Unlock()
	for ws := range c.m {
		err := websocket.Message.Send(ws, msg)
		if err != nil {
			c.mx.Lock()
			c.m[ws].IsOpen = false
			c.m[ws].Disconnect <- true
			c.mx.Unlock()
		}
	}
	return true
}

var Cons = NewConnections()

type IndexContext struct {
	Scripts []models.Script
}

// Think about it
func return_err(c echo.Context, err error) error {
	return c.String(500, err.Error())
}

func Clear(c echo.Context) error {
	return c.NoContent(200)
}

func Index(c echo.Context) error {
	scripts, err := models.GetAvailableScripts()
	if err != nil {
		return_err(c, err)
	}
	for _, v := range scripts {
		Scripts[v.Filename] = v
	}
	return c.Render(200, "index.html", map[string]interface{}{
		"Scripts": scripts,
	})
}

func ReadOutputFromChannel(output chan<- string, channel *io.ReadCloser) {
	scanner := bufio.NewScanner(*channel)
	for scanner.Scan() {
		text := scanner.Text()
		output <- text
	}
}

func ReadOutputSync(channel *io.ReadCloser) string {
	scanner := bufio.NewScanner(*channel)
	output := bytes.NewBufferString("")
	for scanner.Scan() {
		text := scanner.Text()
		output.WriteString("\n" + text)
	}

	return output.String()
}

func Broadcast(logmsg string, output <-chan string, done <-chan bool) {
	log.Printf("%s", logmsg)
	Cons.Broadcast(logmsg)
	for {
		select {
		case msg := <-output:
			log.Printf("%s", msg)
			Cons.Broadcast(msg)
		case <-done:
			log.Printf("Done.")
			break
		}
	}
}

func logPrintf(c echo.Context, format string, v ...any) {
	msg := fmt.Sprintf(format, v...)
	log.Printf("[%s]: %s", c.RealIP(), msg)
}

func RunScriptSync(c echo.Context) error {
	filename := c.Param("filename")
	script := Scripts[filename]
	logPrintf(c, "Executing %s", filename)
	cmd := exec.Command(script.Interpreter, script.Path)

	stdout, err := cmd.StdoutPipe()
	if err != nil {
		logPrintf(c, "Script failed: %v", err)
		return c.String(500, "")
	}

	stderr, err := cmd.StderrPipe()
	if err != nil {
		logPrintf(c, "Script failed: %v", err)
		return c.String(500, "")
	}

	if err := cmd.Start(); err != nil {
		logPrintf(c, "Script failed: %v", err)
		return c.String(500, "")
	}

	message := fmt.Sprintf("\n[%s] Executing %s at %s:\n", c.RealIP(), filename, time.Now().Format(time.DateTime))

	stdOutput := ReadOutputSync(&stdout)
	stdErr := ReadOutputSync(&stderr)

	return c.Render(200, "code", map[string]string{
		"Message": message,
		"Out":     stdOutput,
		"Err":     stdErr,
	})
}

func RunScript(c echo.Context) error {
	filename := c.Param("filename")
	script := Scripts[filename]
	logPrintf(c, "Executing %s", filename)
	cmd := exec.Command(script.Interpreter, script.Path)
	output := make(chan string)
	done := make(chan bool, 1)

	stdout, err := cmd.StdoutPipe()
	if err != nil {
		logPrintf(c, "Script failed: %v", err)
		return c.String(500, "")
	}
	go ReadOutputFromChannel(output, &stdout)

	stderr, err := cmd.StderrPipe()
	if err != nil {
		logPrintf(c, "Script failed: %v", err)
		return c.String(500, "")
	}
	go ReadOutputFromChannel(output, &stderr)

	if err := cmd.Start(); err != nil {
		logPrintf(c, "Script failed: %v", err)
		return c.String(500, "")
	}

	logmsg := fmt.Sprintf("[%s] Executing %s at %s:\n", c.RealIP(), filename, time.Now())

	go Broadcast(logmsg, output, done)

	defer func() {
		cmd.Wait()
		done <- true
	}()

	return c.String(200, "Success")
}

func WebSocket(c echo.Context) error {
	websocket.Handler(func(ws *websocket.Conn) {
		logPrintf(c, "Got new ws connection: %s", ws.RemoteAddr().String())
		Cons.New(ws)
		defer func(_ws *websocket.Conn) {
			logPrintf(c, "%s disconnected", _ws.RemoteAddr().String())
			Cons.Delete(_ws)
			_ws.Close()
		}(ws)

		connection, _ := Cons.IsConnected(ws)
		<-connection.Disconnect
	}).ServeHTTP(c.Response(), c.Request())
	return nil
}
