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
        $num_shares = ((float) intval($num_shares * 100000000)) / 100000000;

        // construct subqueries to get order data
        $subquery1 = "SELECT `price_id` " .
            "FROM `prices` " . 
            "ORDER BY `price_id` DESC " .
            "LIMIT 1";
        $subquery2 = "SELECT `balance_usd`, `balance_coin` " .
            "FROM `orderbook` " . 
            "WHERE `account_id` = $account_id " .
            "ORDER BY `order_id` DESC " .
            "LIMIT 1;";

        // execute subquery2 first in order to get values
        $result = $connect->query($subquery2);
        $row = $result->fetch_assoc();
        $balance_usd = $row['balance_usd'];
        $balance_coin = $row['balance_coin'];

        // construct main query
        $query = "INSERT INTO `orderbook` " .
            "(`account_id`, `num_shares`, `order_type`, `price_id`, `balance_usd`, `balance_coin`) " . 
            "VALUES ($account_id, $num_shares, 'BUY', ($subquery1), $balance_usd, $balance_coin);";
        echo $query;

        // execute actual insert query
        $result = $connect->query($query);

        $connect->close();

        return true;
    }
}