<?php

namespace Login\View;

interface IView
{
  public function response(bool $isLoggedIn);
}
