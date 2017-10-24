<?php
require_once(dirname(__FILE__) . '/../../resources/snippets/prepend.php');

// require that the user is logged out
$login = new Login();
$login->requireLoggedIn(true);

// get the price and coin symbol
$page_num = (isset($_POST['page_num'])) ? $_POST['page_num'] : '1';
$coin_symbol = (isset($_POST['coin_symbol'])) ? $_POST['coin_symbol'] : 'BTC';

// get list of prices
$prices = new Prices();
$json['last_price'] = $prices->updatePrice();
$result = $prices->getPriceHistory($coin_symbol, $page_num);
$json['prices'] = $result;

// return the result
$json['success'] = true;
return_json($json);