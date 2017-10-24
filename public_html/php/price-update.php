<?php
require_once(dirname(__FILE__) . '/../../resources/snippets/prepend.php');

// require that the user is logged out
$login = new Login();
$login->requireLoggedIn(true);

// get list of prices
$prices = new Prices();
$result = $prices->updatePrice();
$json['last_price'] = $result;

// return the result
$json['success'] = ($result > 0);
return_json($json);