<?php
require_once "../connection/database.php";

function getHistoryForMarketAtId($marketId) {
    global $dbConnection;

    $history = [];

    $sql = "SELECT products.name, history.price, history.id, history.weight, history.date, history.total 
            FROM history
            INNER JOIN products
            ON history.product_id = products.id
            WHERE market_id=" . $marketId . " 
            ORDER BY history.date DESC";
    $result = mysqli_query($dbConnection, $sql);
    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $history[$row["id"]] = [
                    'name' => $row['name'],
                    'price' => $row["price"],
                    'weight' => $row["weight"],
                    'date' => $row["date"],
                    'total' => $row["total"]];
            }
        }
    }
    return $history;
}

function addHistoryToMarketAt($userId, $marketId, $history) {
    global $dbConnection;

    $sql = "INSERT INTO history (user_id, market_id, product_id, price, weight, total) 
            VALUES ('" . $userId . "',
                  '" . $marketId . "',
                  '" . $history["product_id"] . "',
                  '" . $history["price"] . "',
                  '" . $history["weight"] . "',
                  '" . $history["total"] ."'
                  )";
    mysqli_query($dbConnection, $sql);

    $sql = "SELECT max(id) as id FROM history";
    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while ($row = mysqli_fetch_assoc($result)) {
            $ans = $row["id"];
        }
    }

    return $ans;

}

function addCashToHistoryAtId($history_id, $finalCash) {
    global $dbConnection;

    $sql = "UPDATE history SET total='" . $finalCash . "' WHERE id =" . $history_id;
    mysqli_query($dbConnection, $sql);
}

function totalAmountOfEveryProduct($marketId, $products) {
    global $dbConnection;

    $res = [];

    foreach ($products as $key => $product) {
        $sql = "SELECT price, weight, total FROM history WHERE product_id='{$key}' AND market_id='{$marketId}'";
        $result = mysqli_query($dbConnection, $sql);
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while ($row = mysqli_fetch_assoc($result)) {
                $res[$key][] = [
                    "weight" => $row["weight"],
                    "price" => $row["price"],
                    "total" => $row["total"]
                ];
            }
        }
    }

    return $res;
}