<?php
require_once "../connection/database.php";

function getProducts($userId) {
    global $dbConnection;

    $products = [];

    $sql = "SELECT * FROM products WHERE user_id=" . $userId;
    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $products[$row['id']] = $row['name'];
        }
    }

    return $products;
}

function getProductsOfMarketAtId ($userId, $marketId) {
    global $dbConnection;
    $products = [];

    $sql = "SELECT rel_market_product.product_id AS id, products.name, rel_market_product.price, rel_market_product.amount
            FROM rel_market_product
            INNER JOIN products
            ON rel_market_product.product_id=products.id
            WHERE rel_market_product.market_id=" . $marketId . " AND rel_market_product.user_id=" . $userId;

    $result = mysqli_query($dbConnection, $sql);

    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $products[$row['id']]["name"] = $row['name'];
            $products[$row['id']]["price"] = $row['price'];
            $products[$row['id']]["amount"] = $row['amount'];
        }
    }

    return $products;
}

function createProduct($userId, $product)
{
    global $dbConnection;

    $sql = "INSERT INTO products (`user_id`, `name`) VALUES ('" . $userId . "', '" . $product ."')";
    mysqli_query($dbConnection, $sql);
}

function createProductForMarketAtId($userId, $marketId, $product) {
    global $dbConnection;

    $sql = "INSERT INTO rel_market_product (user_id, market_id, product_id, price) VALUES ('" . $userId ."', '" . $marketId . "', '" . $product['id'] . "', '" . $product['price'] . "')";
    mysqli_query($dbConnection, $sql);

}
