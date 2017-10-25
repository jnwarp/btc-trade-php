<?php

class Prices
{
    public function __construct()
    {
        
    }

    public function getPriceHistory($symbol="BTC", $page=1, $count=10)
    {
        $connect = new Connect();

        // sanitize data
        $symbol = $connect->real_escape_string($symbol);
        $count = intval($count);
        $offset = (intval($page) - 1) * $count;

        // construct 
        $query = "SELECT `price_id`, `time`, `usd_value` " .
            "FROM `prices` " . 
            "WHERE `coin_symbol` = '$symbol' " .
            "ORDER BY `price_id` DESC " . 
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

    public function updatePrice()
    {
        $connect = new Connect();

        // construct 
        $query = "SELECT `price_id` " .
            "FROM `prices` " . 
            "WHERE `time` > (NOW() - INTERVAL 2 MINUTE) " .
            "LIMIT 1;";
        $result = $connect->query($query);

        // exit if there has already been a request in the last 2 minutes
        if ($result->num_rows > 0) return false;
        

        // call BitStamp api to get latest price
        $result = file_get_contents("https://www.bitstamp.net/api/ticker/");
        $result = json_decode($result, true);
        $price = ((float) intval($result['last'] * 100)) / 100;

        $query = "INSERT INTO `prices` " .
            "(`coin_symbol`, `time`, `usd_value`) " . 
            "VALUES ('BTC', NOW(), $price);";
        $result = $connect->query($query);

        return $price;
    }
}