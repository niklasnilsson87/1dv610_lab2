<?php

class ErrorPage
{

  public static function echoError($e)
  {
    echo '<!DOCTYPE html>
    <html>
      <head>
        <meta charset="utf-8">
        <title>Error Page</title>
      </head>
      <body>
        <h1>Error Page</h1>
        
        <div class="container">
          <p>Something went wrong in the application</p>
          <p>Error: </p>
          <br>
          <p> ' . $e . ' </p>
        </div>
       </body>
    </html>
  ';
  }
}
