<?php
require_once(dirname(__FILE__) . '/../../resources/snippets/prepend.php');

// require that the user is logged out
$login = new Login();
$login->requireLoggedIn(true);

// get the price and coin symbol
$num_dollars = (isset($_POST['num_dollars'])) ? $_POST['num_dollars'] : '0.01';
$coin_symbol = (isset($_POST['coin_symbol'])) ? $_POST['coin_symbol'] : 'BTC';

// update the prices first
$prices = new Prices();
$result = $prices->updatePrice();

// get list of prices
$orderbook = new Orderbook();
$result = $orderbook->buyCoin($login->accountId, $num_dollars);
$json = $result;

if (!isset($json['error'])) {
    $json['success'] = true;
}

// return the result
return_json($json);