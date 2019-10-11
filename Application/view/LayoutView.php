<?php

namespace Application\View;

class LayoutView
{

  public function render($isLoggedIn, $v, DateTimeView $dtv, $rv = null, $runView = null)
  {
    echo '<!DOCTYPE html>
      <html>
        <head>
          <meta charset="utf-8">
          <link rel="stylesheet" type="text/css" href="style.css" />
          <title>Login Example</title>
        </head>
        <body>
          <h1>Assignment 2</h1>
          ' . $this->renderLink($isLoggedIn) . '
          ' . $this->renderIsLoggedIn($isLoggedIn) . '
          
          <div class="container">
              ' . $v->response($isLoggedIn) . '
              ' . $this->renderRunningPage($rv, $isLoggedIn) . '
              ' . $this->renderRunningPage($runView, $isLoggedIn) . '
         
              ' . $dtv->show() . '
          </div>
         </body>
      </html>
    ';
  }

  private function renderRunningPage($view, $isLoggedIn)
  {
    if ($isLoggedIn) {
      return ($view != null)
        ? "<br>" . $view->response()
        : '';
    }
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
