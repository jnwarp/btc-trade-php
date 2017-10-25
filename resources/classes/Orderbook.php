<?php

class Orderbook
{
    public function __construct()
    {
        
    }

    public function initAccount($account_email) {
        $connect = new Connect();
        
        // sanitize data
        $account_email = $connect->real_escape_string($account_email);

        // construct subquery to get order data
        $subquery1 = "SELECT `price_id` " .
            "FROM `prices` " . 
            "ORDER BY `price_id` DESC " .
            "LIMIT 1";
        $subquery2 = "SELECT `account_id` " .
            "FROM `accounts` " .
            "WHERE `email` = '$account_email'";

        // construct main query
        $query = "INSERT INTO `orderbook` " .
            "(`account_id`, `num_shares`, `order_type`, `price_id`, `balance_usd`, `balance_coin`) " . 
            "VALUES (($subquery2), 0, 'INIT', ($subquery1), 5000.00, 0);";

        // execute actual insert query
        $result = $connect->query($query);
        $connect->close();

        return true;
    }

    public function buyCoin($account_id, $num_dollars)
    {
        $connect = new Connect();

        // sanitize data
        $account_id = intval($account_id);
        $num_dollars = ((float) intval(floatval($num_dollars) * 100)) / 100;
        
        // do not allow trades below $0.01
        if ($num_dollars < 0.01) {
            $connect->close();
            $json['error'] = 'minimum_trade';
            return $json;
        }

        // construct subquery to get order data
        $subquery = "SELECT o.`balance_usd`, o.`balance_coin`, p.`price_id`, p.`usd_value` " .
            "FROM `orderbook` AS o, `prices` as p " . 
            "WHERE o.`account_id` = $account_id " .
            "ORDER BY o.`order_id` DESC, p.`price_id` DESC " .
            "LIMIT 1;";

        // execute subquery first in order to get values
        $result = $connect->query($subquery);
        $row = $result->fetch_assoc();
        $row['num_shares'] = (float) intval($num_dollars / $row['usd_value'] * 100000000) / 100000000;
        $row['num_dollars'] = $num_dollars;

        // do not execute trade if user does not have enough money
        if (($row['balance_usd'] - $num_dollars) < 0) {
            $connect->close();
            $row['error'] = 'insufficient_funds';
            return $row;
        };

        // update the user's new totals
        $row['balance_usd'] -= $num_dollars;
        $row['balance_coin'] += $row['num_shares'];

        // construct main query
        $query = "INSERT INTO `orderbook` " .
            "(`account_id`, `num_shares`, `order_type`, `price_id`, `balance_usd`, `balance_coin`) " . 
            "VALUES ($account_id, " . $row['num_shares'] . ", 'BUY', " . $row['price_id'] . ", " . $row['balance_usd'] . ", " . $row['balance_coin'] . ");";

        // execute actual insert query
        $result = $connect->query($query);
        $connect->close();

        return $row;
    }

    public function getOrderHistory($account_id, $page=1, $count=10)
    {
        $connect = new Connect();

        // sanitize data
        $account_id = intval($account_id);
        $count = intval($count);
        $offset = (intval($page) - 1) * $count;

        // construct query to get orderbook details
        $query = "SELECT o.`order_id`, p.`time`, o.`num_shares`, p.`usd_value`, " .
            "o.`balance_usd`, o.`balance_coin`, o.`order_type`, p.`coin_symbol` " .
            "FROM `orderbook` as o, `prices` as p " . 
            "WHERE o.`account_id` = $account_id AND p.`price_id` = o.`price_id` " .
            "ORDER BY `order_id` DESC " . 
            "LIMIT $offset, $count;";
        $result = $connect->query($query);

        // create an array of values
        $table = [];
        while ($row = $result->fetch_assoc()) {
            $table[] = $row;
        }

        $connect->close();

        return $table;
    }

    public function sellCoin($account_id, $num_shares)
    {
        $connect = new Connect();

        // sanitize data
        $account_id = intval($account_id);
        $num_shares = ((float) intval(floatval($num_shares) * 100000000)) / 100000000;

        // do not allow trades below 0.00000001 BTC
        if ($num_shares == 0) {
            $connect->close();
            $json['error'] = 'minimum_trade_btc';
            return $json;
        }

        // construct subquery to get order data
        $subquery = "SELECT o.`balance_usd`, o.`balance_coin`, p.`price_id`, p.`usd_value` " .
            "FROM `orderbook` AS o, `prices` as p " . 
            "WHERE o.`account_id` = $account_id " .
            "ORDER BY o.`order_id` DESC, p.`price_id` DESC " .
            "LIMIT 1;";

        // execute subquery first in order to get values
        $result = $connect->query($subquery);
        $row = $result->fetch_assoc();
        $row['sell_total'] = intval($row['usd_value'] * $num_shares * 100) / 100;
        $row['num_shares'] = $num_shares;

        // do not allow trades below $0.01 USD
        if ($row['sell_total'] < 0.01) {
            $connect->close();
            $json['error'] = 'minimum_trade_usd';
            $json['min_trade'] = intval((0.01 / $row['usd_value']  + 0.00000001) * 100000000) / 100000000;
            return $json;
        }

        // do not execute trade if user does not have enough money
        if (($row['balance_coin'] - $num_shares) < 0) {
            $connect->close();
            $row['error'] = 'insufficient_funds';
            return $row;
        };

        // update the user's new totals
        $row['balance_coin'] -= $num_shares;
        $row['balance_usd'] += $row['sell_total'];

        // construct main query
        $query = "INSERT INTO `orderbook` " .
            "(`account_id`, `num_shares`, `order_type`, `price_id`, `balance_usd`, `balance_coin`) " . 
            "VALUES ($account_id, $num_shares, 'SELL', " . $row['price_id'] . ", " . $row['balance_usd'] . ", " . $row['balance_coin'] . ");";

        // execute actual insert query
        $result = $connect->query($query);
        $connect->close();

        return $row;
    }
}