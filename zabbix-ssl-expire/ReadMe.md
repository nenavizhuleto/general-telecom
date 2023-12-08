# Monitoring service for ssl certificate's expiration date with Zabbix Server and Zabbix Sender

## Installation

1. Clone repo:

```
$ git clone https://github.com/nenavizhuleto/scripts.git
```

2. Cd into _scripts_ directory:

```
$ cd scripts/
```

3. Run installer:

```
# ./ssl_expire_install
```

4. Edit config file:

```
# vim /etc/ssl_expire/ssl_expire.conf
```

```
ZabbixServer=127.0.0.1 # Zabbix server's ip address to send data to
Hostname=SSL_EXPIRE # Hostname of host (must be the same when creating a new host in Zabbix)
### Websites=(google.com github.com)
Websites=() # List of websites to check ssl certificate
### Time until next query (use suffixes 's' for seconds, 'm' for minutes, 'h' for hours, 'd' for days)
SleepFor=10s
```

5. Start service:

```
# systemctl start ssl_expire_mon.service
```

## Zabbix

-   Create a new host with hostname from configuration file
-   Add new items to host type of 'zabbix trapper' and set key value to website's name without .com etc (eg. if website is 'google.com', the key would be 'google')
-   Add triggers for each item

## Done
