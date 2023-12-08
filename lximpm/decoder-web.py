from flask import Flask, render_template, request, jsonify
import flask_cors
import lximpm_decoder

app = Flask(__name__)
flask_cors.CORS(app)


@app.route("/")
def index():
    return render_template("index.html")


@app.route("/decode", methods=['POST'])
def decode():
    data = request.get_json()
    print(data)

    device = data['device']
    result = ''

    if device == 'PM':
        result = lximpm_decoder.decode_pm_status(
            data['status'], data['SYMP4KI'])

    if device == 'MIM5':
        result = lximpm_decoder.decode_mim5_status(data['status'])

    return jsonify(result)
