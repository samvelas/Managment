<?php
require_once "../connection/database.php";

function getFullPayDates () {
    global $dbConnection;

    $dates = [];
    $sql = "SELECT * FROM full_pay_date";
    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $dates[$row['id']] = $row['pay_date'];
        }
    }
    return $dates;
}

function setFullPayDate($history_id) {
    global $dbConnection;

    $sql = "INSERT INTO full_pay_date (id) VALUES ('" . $history_id . "')";
    mysqli_query($dbConnection, $sql);
}