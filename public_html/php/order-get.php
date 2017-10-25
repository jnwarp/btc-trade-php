<?php
require_once(dirname(__FILE__) . '/../../resources/snippets/prepend.php');

// require that the user is logged out
$login = new Login();
$login->requireLoggedIn(true);

// get the page number
$page_num = (isset($_POST['page_num'])) ? $_POST['page_num'] : '1';

// get list of prices
$orderbook = new Orderbook();
$result = $orderbook->getOrderHistory($login->accountId, $page_num);
$json['orders'] = $result;

// return the result
$json['success'] = true;
return_json($json);