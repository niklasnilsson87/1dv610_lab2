<?php

class LoginController {
    private $model;
    private $view;

    public function __construct(LoginModel $loginModel, LoginView $loginView) {
        $this->model = $loginModel;
        $this->view = $loginView;
    }
}