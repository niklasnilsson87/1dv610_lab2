<?php

namespace Login\View;

class LayoutView
{

  public function render($isLoggedIn, $v, DateTimeView $dtv)
  {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->renderLink($isLoggedIn) . '
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          
          <div class="container">
              ' . $v->response($isLoggedIn) . '
              
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }

  private function renderIsLoggedIn($isLoggedIn)
  {
    if ($isLoggedIn) {
      return '<h2>Logged in</h2>';
    } else {
      return '<h2>Not logged in</h2>';
    }
  }

  private function renderLink($isLoggedIn)
  {
    if (!$isLoggedIn && !$this->userWantsToRegister()) {
      return '<a href=?register>Register a new user</a>';
    } else if ($this->userWantsToRegister()) {
      return '<a href=?>Back to login</a>';
    }
  }

  public function userWantsToRegister()
  {
    return isset($_GET['register']);
  }
}
