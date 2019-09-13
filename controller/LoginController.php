<?php

namespace Login\Controller;

class LoginController {
  private $model;
  private $view;

  public function __construct(\Login\Model\LoginModel $loginModel, \Login\View\LoginView $loginView) {
    $this->model = $loginModel;
    $this->view = $loginView;

  }

  public function determandView() {
  // Check if user is logged in
      //login = true
          //show logged in view
      //login = false
          // show loginView
  }
  

}