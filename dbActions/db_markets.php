<?php
require_once "../connection/database.php";

function getMarkets() {
    global $dbConnection;

    $markets = [];

    $sql = "SELECT * FROM markets";
    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $markets[$row['id']] = $row['name'];
        }
    }

    return $markets;
}

function getMarketAtId($id) {
    global $dbConnection;
    $market = [];

    $sql = "SELECT * FROM markets WHERE id=" . $id;

    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $market["id"] = $row['id'];
            $market["name"] = $row['name'];
        }
    }

    return $market;
}

function createMarket($market) {
    global $dbConnection;

    $sql = "INSERT INTO markets (`name`) VALUES ('" . $market . "')";
    mysqli_query($dbConnection, $sql);
}