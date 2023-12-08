<?php

namespace App\Controllers;

class Dialplan extends BaseController {
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

		try {
			$dialplan = $amiModel->getDialplan();
		} catch (\Exception $e) {
			return json_encode([
				'result' => false,
				'message' => 'Ошибка AMI. ' . $e->getMessage()
			]);
		}

		// data
    $data = [
    	'contexts' => []
    ];

    // contexts
    foreach ($dialplan as $rule){
    	if (!array_key_exists($rule['context'], $data['contexts']))
				$data['contexts'][$rule['context']] = [];

			$data['contexts'][$rule['context']][] = $rule;
		}

		ksort($data['contexts']);

	  return json_encode([
	  	'result' => true,
	  	'data' => $data
	  ]);
  }

  public function getDialplan_Reload() {
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
			$result = $amiModel->dialplanReload();
		} catch (\Exception $e) {
			return json_encode([
				'result' => false,
				'message' => 'Ошибка AMI. ' . $e->getMessage()
			]);
		}

	  return json_encode($result);
  }
}
