<?php

namespace App\Controllers;

class Peers extends BaseController {
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

		try {
			$peers = $amiModel->getPeers();
		} catch (\Exception $e) {
			return json_encode([
				'result' => false,
				'message' => 'Ошибка AMI. ' . $e->getMessage()
			]);
		}

		$data['peers'] = $peers;

	  return json_encode([
	  	'result' => true,
	  	'data' => $data
	  ]);
  }

  public function getSip_Prune_Realtime_All() {
  	// ACL
    $userModel = model('App\Models\UserModel');
    if (!$user = $userModel->getCurrent())
      return json_encode([
      	'result' => false,
      	'message' => 'Ошибка авторизации.'
      ]);

		//
    $amiModel = model('App\Models\AMIModel');

		try {
			$result = $amiModel->sipPruneRealtimeAll();
		} catch (\Exception $e) {
			return json_encode([
				'result' => false,
				'message' => 'Ошибка AMI. ' . $e->getMessage()
			]);
		}

	  return json_encode($result);
  }

  public function getSip_Reload() {
  	// ACL
    $userModel = model('App\Models\UserModel');
    if (!$user = $userModel->getCurrent())
      return json_encode([
      	'result' => false,
      	'message' => 'Ошибка авторизации.'
      ]);

		//
    $amiModel = model('App\Models\AMIModel');

		try {
			$result = $amiModel->sipReload();
		} catch (\Exception $e) {
			return json_encode([
				'result' => false,
				'message' => 'Ошибка AMI. ' . $e->getMessage()
			]);
		}

		if ($result['result'] && !$result['message'])
			$result['message'] = 'Выполнено.';

	  return json_encode($result);
  }

  public function getSip_Prune_Realtime_Peer() {
  	// ACL
    $userModel = model('App\Models\UserModel');
    if (!$user = $userModel->getCurrent())
      return json_encode([
      	'result' => false,
      	'message' => 'Ошибка авторизации.'
      ]);

    //
    $peername = $this->request->getGet('peername');

    $amiModel = model('App\Models\AMIModel');

		try {
			$result = $amiModel->sipPruneRealtimePeer($peername);
		} catch (\Exception $e) {
			return json_encode([
				'result' => false,
				'message' => 'Ошибка AMI. ' . $e->getMessage()
			]);
		}

	  return json_encode($result);
  }
}
