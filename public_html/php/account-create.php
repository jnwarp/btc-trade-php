<?php
require_once(dirname(__FILE__) . '/../../resources/snippets/prepend.php');

// require that the user is logged out
$login = new Login();
$login->requireLoggedIn(false);

// get account email and password
$email = (isset($_POST['email'])) ? $_POST['email'] : '';
$password = (isset($_POST['password'])) ? $_POST['password'] : '';

// verify if the information is valid
$accounts = new Accounts();
$json['valid_email'] = $accounts->checkEmail($email);
$json['valid_password'] = $accounts->checkPassword($password);
require_valid($json);

// create the account
$json['success'] = $accounts->createAccount($email, $password);
return_json($json);