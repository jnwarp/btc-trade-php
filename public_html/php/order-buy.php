<?php
require_once(dirname(__FILE__) . '/../../resources/snippets/prepend.php');

// require that the user is logged out
$login = new Login();
$login->requireLoggedIn(true);

// get the price and coin symbol
$num_shares = (isset($_POST['num_shares'])) ? $_POST['num_shares'] : '0.001';
$coin_symbol = (isset($_POST['coin_symbol'])) ? $_POST['coin_symbol'] : 'BTC';

// get list of prices
$orderbook = new Orderbook();
$result = $orderbook->buyCoin($login->accountId, $num_shares);
$json['order'] = $result;

// return the result
$json['success'] = true;
return_json($json);