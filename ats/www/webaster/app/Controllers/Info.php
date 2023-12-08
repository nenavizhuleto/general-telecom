<?php

namespace App\Controllers;

class Info extends BaseController {
  public function getIndex() {
  	// ACL
    $userModel = model('App\Models\UserModel');
    if (!$user = $userModel->getCurrent())
      return json_encode([
      	'result' => false,
      	'message' => 'Ошибка авторизации.'
      ]);

		//
    $amiModel = model('App\Models\AMIModel');

    $data = [];

    // host
    $data['hostname'] = gethostname();

    // uptime
    $data['uptime'] = exec('uptime -p');

    // os
    $data['os'] = exec('lsb_release -ds');

    // ips
    exec('ifconfig | grep "inet\b" | awk \'{print $2}\' | grep -v 127.0.0.1', $data['ips']);
    $data['ips'] = implode('<br>', $data['ips']);

    // routes
    exec('route -n | tail --lines=+3 | awk \'{print $1 "/" $3 " => " $2 " (" $8 ")"}\'', $data['routes']);
    $data['routes'] = implode('<br>', $data['routes']);

		// asterisk status
		$data['asterisk_status'] = exec('systemctl status asterisk | grep "Active:\ " | sed -n "s:.*Active\:\s\(.*\)since\(.*\):\1:p"');
		$data['asterisk_uptime'] = exec('systemctl status asterisk | grep "Active:\ " | sed -n "s:\(.*\)UTC;\s\(.*\)\sago$:\2:p"');

    // asterisk
		try {
			$coreSettings = $amiModel->getCoreSettings();
	  	$data['asterisk_version'] = $coreSettings['asteriskversion'];
			$data['asterisk_ami_version'] = $coreSettings['amiversion'];
		} catch (\Exception $e) {
			$data['asterisk_version'] = '?';
			$data['asterisk_ami_version'] = '?';
		}

		/*{
			"response":"Success",
			"actionid":"1668978414.021",
			"amiversion":"5.0.0",
			"asteriskversion":"16.2.1~dfsg-2ubuntu1",
			"systemname":"",
			"coremaxcalls":"0",
			"coremaxloadavg":"0.000000",
			"corerunuser":"",
			"corerungroup":"",
			"coremaxfilehandles":"0",
			"corerealtimeenabled":"Yes",
			"corecdrenabled":"Yes",
			"corehttpenabled":"No"
		}*/

	  return json_encode([
	  	'result' => true,
	  	'data' => $data
	  ]);
  }
}
