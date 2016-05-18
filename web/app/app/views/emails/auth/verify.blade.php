<!DOCTYPE html>
<html lang="en-US">
  <head>
    <meta charset="utf-8">
  </head>
  <body>

    <h1>Hi {{$first_name}} {{$last_name}}</h1>

    <p>Your email was used recently to register with CloudHRD. Click {{ link_to_action('AuthController@getVerify', 'this link', array($token)) }} to verify your email address.</p>
    <p>If you think this is an error, click {{ link_to_action('AuthController@getUnlist', 'this link', array($token)) }} to delist your email address from our databases.</p>

    <p>Thanks.</p>

  </body>
</html>
