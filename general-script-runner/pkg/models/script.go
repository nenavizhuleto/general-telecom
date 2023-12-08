package models

import (
	"bufio"
	"os"
	"strings"

	"github.com/google/uuid"
)

var ScriptsDirectory = "scripts/"

var (
	SheBang         = "#!"
	NameFlag        = "@Name:"
	AuthorFlag      = "@Author:"
	DescriptionFlag = "@Description:"
	ColorFlag       = "@Color:"
)

type Script struct {
	UUID        string
	Filename    string
	Path        string
	Interpreter string

	Name        string
	Author      string
	Description string

	Color string
}

func NewScriptFromFileInfo(fi os.FileInfo) (*Script, error) {
	filename := fi.Name()
	script := Script{
		UUID:     uuid.NewString(),
		Filename: filename,
		Path:     ScriptsDirectory + filename,
	}

	f, err := os.Open(script.Path)
	if err != nil {
		return &script, err
	}

	defer f.Close()

	scanner := bufio.NewScanner(f)

	for scanner.Scan() {
		line := scanner.Text()

		// Parsing Interpreter from shebang
		if l := strings.TrimPrefix(line, SheBang); l != line {
			script.Interpreter = l
			continue
		}
		// Parsing name
		if l := strings.SplitAfter(line, NameFlag); len(l) != 1 {
			script.Name = l[1]
			continue
		}

		// Parsing author
		if l := strings.SplitAfter(line, AuthorFlag); len(l) != 1 {
			script.Author = l[1]
			continue
		}

		// Parsing description
		if l := strings.SplitAfter(line, DescriptionFlag); len(l) != 1 {
			script.Description = l[1]
			continue
		}

		// Parsing color
		if l := strings.SplitAfter(line, ColorFlag); len(l) != 1 {
			script.Color = l[1]
			continue
		}
	}

	return &script, nil
}

func GetAvailableScripts() ([]Script, error) {
	// Reading scripts directory
	files, err := os.ReadDir(ScriptsDirectory)
	if err != nil {
		return nil, err
	}

	scripts := make([]Script, len(files))

	for i, file := range files {
		fi, err := file.Info()
		if err != nil {
			continue
		}
		script, err := NewScriptFromFileInfo(fi)
		if err != nil {
			continue
		}
		scripts[i] = *script
	}

	return scripts, nil
}
