<?php
require_once "connection/database.php";

function existsUser($login, $password) {
    global $dbConnection;

    $sql = "SELECT * FROM users WHERE username='" . $login . "' AND password='" . $password . "'";
    $result = mysqli_query($dbConnection, $sql);
    $exists = false;

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $id = $row["id"];
            $username = $row["username"];
            $exists = true;
        }
    }

    if ($exists) {
        return [
            "id" => $id,
            "username" => $username
        ];
    }

    return false;
}

function backUp(){
    global $dbConnection;

    $file = 'backups/mytable.data';
    mysqli_query($dbConnection, "SELECT * INTO OUTFILE '" . $file . "' FROM `history`");


}