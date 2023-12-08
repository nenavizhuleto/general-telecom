import datetime
import math
import time
from struct import *
from pymodbus.utilities import computeCRC
from pyzabbix import ZabbixMetric, ZabbixSender
import json

import socket
zbx = ZabbixSender('193.150.102.91')


OUT = 0
IN = 1

PARSE_DATA = False

TIMEOUT = 1
BUFFER_SIZE = 1024
DEBUG = False

CMD_GET_RUNTIME_VALUES = bytes([0x65, 0x03, 0x0D, 0xD4, 0x00, 0x53])
FMT_GET_RUNTIME_VALUES = ">" + "BB BB BB" + "f" * 6 * 6 + 'f' * 2 * 2

CMD_GET_RESULT_VALUES = bytes([0x65, 0x03, 0x0D, 0x54, 0x00, 0x68])
FMT_GET_RESULT_VALUES = ">" + "BB BB BB" + ("d" * 6 * 2) + \
    ('d' * 2 * 4) + ('B' * 2 * 7) + 'd' * 2 + 'L' * 3


CMD_FIRST = bytes([0x65, 0x03, 0x00, 0x00, 0x00, 0x13])


CMD_SECOND = bytes([0x65, 0x03, 0x0a, 0x6d, 0x00, 0x04])

CMD_THIRD = bytes([0x65, 0x03, 0x00, 0x69, 0x00, 0x7d])

CMD_FOURTH = bytes([0x65, 0x03, 0x00, 0xE6, 0x00, 0x7D])

CMD_FIFTH = bytes([0x65, 0x03, 0x01, 0x63, 0x00, 0x05])

CMD_SIXTH = bytes([0x65, 0x03, 0x0A, 0x71, 0x00, 0x03])

CMD_SEVENTH = bytes([0x65, 0x03, 0x08, 0x69, 0x00, 0x0B])

CMD_EIGHTH = bytes([0x65, 0x03, 0x0E, 0xD9, 0x00, 0x20])


def print_package(package, dir):
    direction = "Recieved" if dir else "Sent"

    print(f"{direction} bytes: {len(package)}\n\r\t")
    for byte in package:
        print(hex(byte), ' ', end='')

    print("\n\r")


def get_float(value: float):
    value = pack('f', value)
    new_v = bytes([value[1], value[0], value[3], value[2]])
    value = unpack('>f', new_v)

    if math.isnan(value[0]):
        return 0.0

    return value[0]


def get_time(pdu):
    return datetime.datetime(pdu[3] + 2000, pdu[0], pdu[1], pdu[2], pdu[5], pdu[4]).ctime()


def get_pdu(package, fmt):
    address = package[0]
    function = package[1]
    data_size = package[2]
    checksum = package[-1]
    data = package[3:-2]
    data = unpack(fmt, data)
    return data


def send_recieve(fd, command: bytes):
    command = command.__add__(computeCRC(command).to_bytes(2, 'big'))
    if DEBUG:
        print_package(command, OUT)

    fd.send(command)
    time.sleep(TIMEOUT)

    recv_bytes = fd.recv(BUFFER_SIZE)
    if recv_bytes:
        if DEBUG:
            print_package(recv_bytes, IN)

    return recv_bytes


def get_current_values(sock):
    response = {}
    data = send_recieve(sock, CMD_GET_RUNTIME_VALUES)
    pdu = get_pdu(data, FMT_GET_RUNTIME_VALUES)

    response['date'] = get_time(pdu)

    response['t'] = []
    for x in pdu[6:12]:
        response['t'].append(get_float(x))

    response['P'] = []
    for x in pdu[13:19]:
        response['P'].append(get_float(x))

    response['Go'] = []
    for x in pdu[20:26]:
        response['Go'].append(get_float(x))

    response['Gm'] = []
    for x in pdu[27:33]:
        response['Gm'].append(get_float(x))

    response['F'] = []
    for x in pdu[34:40]:
        response['F'].append(get_float(x))

    response['H'] = []
    for x in pdu[41:45]:
        response['H'].append(get_float(x))

    response['Ftv'] = []
    for x in pdu[46:48]:
        response['Ftv'].append(get_float(x))

    response['Hx'] = []
    for x in pdu[49:51]:
        response['Hx'].append(get_float(x))

    return response


sock = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
sock.connect(('172.17.50.42', 41001))
while True:
    values = get_current_values(sock)
    print(values)
    output = json.dumps(values)
    metric = ZabbixMetric('TV7', 'tv.info', output)
    zbx.send([metric])
    time.sleep(5)

# send_recieve(sock, CMD_FIRST)
# send_recieve(sock, CMD_SECOND)
# send_recieve(sock, CMD_THIRD)
# send_recieve(sock, CMD_FOURTH)
# send_recieve(sock, CMD_FIFTH)
# send_recieve(sock, CMD_SIXTH)
# send_recieve(sock, CMD_SEVENTH)
# send_recieve(sock, CMD_EIGHTH)
