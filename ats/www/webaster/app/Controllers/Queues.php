<?php

namespace App\Controllers;

class Queues extends BaseController {
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
			$queues = $amiModel->getQueues();
		} catch (\Exception $e) {
			return json_encode([
				'result' => false,
				'message' => 'Ошибка AMI. ' . $e->getMessage()
			]);
		}

		$data['queues'] = $queues;

	  return json_encode([
	  	'result' => true,
	  	'data' => $data
	  ]);
  }

  public function getQueue_Reload_All() {
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
			$result = $amiModel->queueReloadAll();
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
}
