<?php

namespace Application\View;

class RunningView
{
  public function response()
  {
    $response = $this->toHTML();
    return $response;
  }

  public function toHTML()
  {
    return '<h1>My Running App!</h1>';
  }
}
