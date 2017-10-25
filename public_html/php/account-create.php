<?php
require_once(dirname(__FILE__) . '/../../resources/snippets/prepend.php');

// require that the user is logged out
$login = new Login(false);
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
if ($accounts->createAccount($email, $password)) {
    // give the account an initial balance
    $orderbook = new Orderbook();
    $json['success'] = $orderbook->initAccount($email);
}

return_json($json);