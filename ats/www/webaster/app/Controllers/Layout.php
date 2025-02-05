<?php

namespace App\Controllers;

class Layout extends BaseController {
  public function index() {
    $userModel = model('App\Models\UserModel');
    if (!$user = $userModel->getCurrent()) {
      return view('login');
    }

    return view('layout', [
    	'user' => $user
    ]);
  }
}
