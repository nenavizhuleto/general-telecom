const loc = window.location;

let uri = 'ws:';
uri += '//' + loc.host;
uri += loc.pathname + 'ws';

const out = document.getElementById('output');
const terminal = document.getElementById('terminal')

let ws = undefined

function initWebSocket() {
  if (!ws) {
    ws = new WebSocket(uri)
    ws.onopen = function() {
      out.innerHTML = '';
      console.log('Connected')
    }
    ws.onmessage = function(evt) {
      out.innerHTML += evt.data + '\n'
      terminal.scrollTop = terminal.scrollHeight;
    }
  }
}


// Connect only when RUN button is pressed
const btns = document.querySelectorAll(".run-button");
btns.forEach(btn => {
  btn.addEventListener('click', (e) => {
    e.preventDefault()
    initWebSocket()
      out.innerHTML += '\n\n\n'
  });
});
