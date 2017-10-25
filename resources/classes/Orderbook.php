<?php

class Orderbook
{
    public function __construct()
    {
        
    }

    public function buyCoin($account_id, $num_shares)
    {
        $connect = new Connect();

        // sanitize data
        $account_id = intval($account_id);
        $num_shares = ((float) intval(floatval($num_shares) * 100000000)) / 100000000;
        
        // do not allow trades below 0.00000001 BTC
        if ($num_shares == 0) {
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
        $row['buy_total'] = intval($row['usd_value'] * $num_shares * 100) / 100;

        // do not execute trade if user does not have enough money
        if (($row['balance_usd'] - $row['buy_total']) < 0) {
            $connect->close();
            $row['error'] = 'insufficient_funds';
            return $row;
        };

        // update the user's new totals
        $row['balance_usd'] -= $row['buy_total'];
        $row['balance_coin'] += $num_shares;

        // construct main query
        $query = "INSERT INTO `orderbook` " .
            "(`account_id`, `num_shares`, `order_type`, `price_id`, `balance_usd`, `balance_coin`) " . 
            "VALUES ($account_id, $num_shares, 'BUY', " . $row['price_id'] . ", " . $row['balance_usd'] . ", " . $row['balance_coin'] . ");";

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
        $row['sell_total'] = intval($row['usd_value'] * $num_shares * 100) / 100;
        $row['num_shares'] = $num_shares;

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