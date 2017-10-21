<?php
require_once(dirname(__FILE__) . '/../../resources/prepend.php');

// require that the user is logged out
$login = new Login(false);
$login->requireLoggedIn(false);

// get user email and password
$email = (isset($_POST['email'])) ? $_POST['email'] : '';
$password = (isset($_POST['password'])) ? $_POST['password'] : '';

// attempt to make the new login session
$json['success'] = $login->createLoginSession($email, $password);

return_json($json);