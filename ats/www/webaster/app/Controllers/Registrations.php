<?php

namespace App\Controllers;

class Registrations extends BaseController {
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
			$registrations = $amiModel->getRegistrations();
		} catch (\Exception $e) {
			return json_encode([
				'result' => false,
				'message' => 'Ошибка AMI. ' . $e->getMessage()
			]);
		}

		$data['registrations'] = $registrations;

	  return json_encode([
	  	'result' => true,
	  	'data' => $data
	  ]);
  }
}
