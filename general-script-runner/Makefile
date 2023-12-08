

build: clean
	rm -rf build
	mkdir build
	cp -R assets build/
	cp -R css build/
	cp -R dist build/
	cp -R public build/
	cp -R scripts build/
	go build -v -o /tmp/ ./...
	mv /tmp/cmd ./build/app

run: build 
	go run ./cmd/main.go

clean:
	rm -rf build
