package main

import (
	"bytes"
	"fmt"
	"io"
	"log"
	"net/http"
	"os/exec"
)

func main() {
	url := "http://193.150.102.91/"
	contentType := "application/json"
	cmd := exec.Command("./reg_stat_zabbix.php")

	var stdout bytes.Buffer
	var stderr bytes.Buffer
	cmd.Stdout = &stdout
	cmd.Stderr = &stderr

	cmd.Run()

	fmt.Println(stdout.String())
	fmt.Println(stderr.String())

	body := stderr

	res, err := http.Post(url, contentType, bytes.NewReader(body.Bytes()))

	if err != nil {
		log.Fatalf("Error: %s", err.Error())
	}

	defer res.Body.Close()
	response, _ := io.ReadAll(res.Body)

	fmt.Println(string(response))

}
