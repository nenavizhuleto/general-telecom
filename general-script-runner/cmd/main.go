package main

import (
	"html/template"
	"io"
	"log"

	"github.com/labstack/echo/v4"
	"github.com/labstack/echo/v4/middleware"

	"general-script-runner/pkg/pages"
)

type TemplateRenderer struct {
	templates *template.Template
}

func (t *TemplateRenderer) Render(w io.Writer, name string, data interface{}, c echo.Context) error {
	return t.templates.ExecuteTemplate(w, name, data)
}

func main() {
	tmpls, err := template.New("").ParseGlob("public/views/*.html")
	if err != nil {
		log.Fatalf("couldn't initialize templates: %v", err)
	}

	e := echo.New()
	e.Renderer = &TemplateRenderer{
		templates: tmpls,
	}

	e.Use(middleware.Recover())

	e.Static("/dist", "dist")
	e.Static("/assets", "assets")
	e.Static("/css", "css")

	e.GET("/", pages.Index)
	e.POST("/run/:filename", pages.RunScriptSync)

	e.Logger.Fatal(e.Start(":3000"))
}
