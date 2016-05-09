<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>

    <h1>Hi {{$first_name}} {{$last_name}}</h1>

    <p>You have recently requested to reset your password for CloudHRD. Click {{ link_to_action('AuthController@getResetPassword', 'this link', array($token)) }} to reset your password.</p>

    <p>Thanks.</p>

  </body>
</html>
