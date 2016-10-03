<?php
require_once "../components/header.php";
require_once "../dbActions/db_markets.php";
require_once "../components/nav.php";
require_once "../dbActions/db_products.php";
require_once "../dbActions/db_history.php";
require_once "../dbActions/db_full_pay_history.php";


if (!isset($_SESSION["userId"])) {
    header('Location: ../index.php');
}

$userId = $_SESSION["userId"];


if (isset($_GET["marketId"])) {
    $currentMarketId = $_GET["marketId"];
    $currentMarket = getMarketAtId($currentMarketId);
}

$products = getProducts($userId);

if (isset($_POST["productId"]) && isset($_POST["price"])) {
    $product["id"] = $_POST["productId"];
    $product["price"] = $_POST["price"];

    createProductForMarketAtId($userId, $currentMarketId, $product);
}

$marketProducts = getProductsOfMarketAtId($userId, $currentMarketId);

if (isset($_POST["actionProductId"]) && isset($_POST["price"]) && isset($_POST["weight"]) && isset($_POST["total"])) {
    $soldProductId = ($_POST["actionProductId"]);
    $soldProductPrice = ($_POST["price"]);
    $soldProductWeight = ($_POST["weight"]);
    $soldProductTotal = ($_POST["total"]);

    $history = ['product_id' => $soldProductId,
        'price' => $soldProductPrice,
        'weight' => $soldProductWeight,
        'total' => $soldProductTota];

    $idInHistory = addHistoryToMarketAt($userId, $currentMarketId, $history);

    if($soldProductPrice * $soldProductWeight == $soldProductTotal) {
        setFullPayDate($idInHistory);
    }
}

$history = getHistoryForMarketAtId($currentMarketId);

if(isset($_POST["addedCash"]) && isset($_POST["history_id"])) {
    $addedCash = $_POST["addedCash"];
    $addedToHistory = $_POST["history_id"];

    $finalCash = $history[$addedToHistory]["total"] + $addedCash;

    if($finalCash == $history[$addedToHistory]["weight"] * $history[$addedToHistory]["price"]) {
        setFullPayDate($addedToHistory);
    }

    addCashToHistoryAtId($addedToHistory, $finalCash);

}

$history = getHistoryForMarketAtId($currentMarketId);
$payDates = getFullPayDates($userId);

$totalSummary = totalAmountOfEveryProduct($currentMarketId, $products);

$totalWeightOfEveryProduct = new SplFixedArray(1000000);
$totalMoneyFromEveryProduct = new SplFixedArray(1000000);
$totalDebtOfEveryProduct = new SplFixedArray(1000000);

$totalWeight = 0;
$totalMoney = 0;
$totalDebt = 0;

foreach ($totalSummary as $key => $item) {
    $totalWeightOfEveryProduct[$key] = 0;
    $totalMoneyFromEveryProduct[$key] = 0;
    $totalDebtOfEveryProduct[$key] = 0;
    foreach ($item as $value) {
        $totalWeightOfEveryProduct[$key] += $value["weight"];
        $totalMoneyFromEveryProduct[$key] += ($value["weight"] * $value["price"]);
        $totalDebtOfEveryProduct[$key] += ($value["weight"] * $value["price"] - $value["total"]);
    }

    $totalWeight += $totalWeightOfEveryProduct[$key];
    $totalMoney += $totalMoneyFromEveryProduct[$key];
    $totalDebt += $totalDebtOfEveryProduct[$key];
}

//echo '<pre>';
//var_dump($totalSummary);
//echo '</pre>';

?>

<div class="container-fluid market-container">

    <div id="addModal" class="modal">

        <!-- Modal content -->

        <div class="modal-content" id="content">
            <form method="post" action="market.php?marketId=<?=$currentMarketId?>" enctype="multipart/form-data" name="myForm" id="add-modal">
                <h3>Введите добавленную сумму</h3>
                <input  class="form-control input-large price" name="addedCash" id="addedCash" placeholder="Сумма"><br>
                <button class="btn btn-info btn-lg" type="submit">Добавить</button>
            </form>
        </div>

    </div>

    <div id="myModal" class="modal">

        <!-- Modal content -->

        <div class="modal-content" id="content">
            <form method="post" action="market.php?marketId=<?=$currentMarketId?>" enctype="multipart/form-data" name="myForm" id="market-modal">
                <h3>Выберите продукт</h3>
                <select name="productId">
                    <?php
                    foreach ($products as $key => $product) {
                        echo '<option value="' . $key . '">';
                        echo $product;
                        echo '</option>';
                    }
                    ?>
                </select>
                <h3>Цена по умолчанию</h3>
                <input  class="form-control input-large price" name="price" placeholder="Цена"><br>
                <button class="btn btn-info btn-lg" type="submit">Добавить</button>
            </form>
        </div>

    </div>

    <div id="actionModal" class="modal">

        <!-- Modal content -->

        <div class="modal-content" id="content">
            <form method="post" action="market.php?marketId=<?=$currentMarketId?>" enctype="multipart/form-data" name="myForm" id="market-modal">
                <h3>Выберите продукт</h3>
                <select name="actionProductId" id="products" onchange="changedToId()">
                    <option></option>
                    <?php
                    foreach ($marketProducts as $key => $product) {
                        echo '<option value="' . $key . '">';
                        echo $product["name"];
                        echo '</option>';
                    }
                    ?>
                </select>
                <h3>Цена</h3>
                <input id="defaultPrice" onkeyup="calculateTotal()" class="form-control input-large price" name="price" placeholder="Цена"><br>
                <h3>Вес</h3>
                <input id="weight" onkeyup="calculateTotal()" class="form-control input-large price" name="weight" placeholder="Вес" value=""><br>
                <h3>Оплачено</h3>
                <input id="total" class="form-control input-large price" name="total" placeholder="Итог" value=""><br>
                <button class="btn btn-info btn-lg" type="submit">Добавить</button>
            </form>
        </div>

    </div>


    <h1 id="market-page-header" class="page-header"><?= $currentMarket["name"] ?></h1>

    <div class="col-md-4 product-panel-container">
        <div class="panel panel-danger product-panel">
            <div class="panel-heading">
                <h3 class="panel-title">Продукты магазина</h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <button style="background-color: #D7ECCE" id="myBtn" type="button" class="list-group-item add-product-btn" aria-label="Left Align">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true">
                            <h4 style="display: inline-block" class="list-group-item-heading">Добавить продукт</h4>
                        </span>
                    </button>
                </div>
                <ul class="list-group">
                    <?php
                    foreach ($marketProducts as $key => $product) {
                        echo '<li class="list-group-item">';
                        echo '<span class="badge money-badge">' . $totalMoneyFromEveryProduct[$key] . '</span>';
                        echo '<span class="badge debt-badge">' . $totalDebtOfEveryProduct[$key] . '</span>';
                        echo '<span class="badge mix-badge">' . ($totalMoneyFromEveryProduct[$key] - $totalDebtOfEveryProduct[$key]) . '</span>';
                        echo '<span class="badge">' . $totalWeightOfEveryProduct[$key] . "кг" . '</span>';
                        echo '<span class="badge price-badge">' . $product["price"] . '</span>';
                        echo $product["name"];
                        echo '</li>';
                    }
                    ?>
                </ul>
            </div>
        </div>
        <div class="panel panel-warning summary-panel">
            <div class="panel-heading">
                <h3 class="panel-title">Итог</h3>
            </div>
            <div class="panel-body summary-body">
                <div class="list-group">
                    <li class="list-group-item">
                        <span class="badge mix-badge"><?=$totalMoney - $totalDebt?></span>
                        Общяя сумма минус долги
                    </li>
                    <li class="list-group-item">
                        <span class="badge debt-badge"><?=$totalDebt ?></span>
                        Общий долг
                    </li>
                    <li class="list-group-item">
                        <span class="badge"><?=$totalWeight ?>кг</span>
                        Общий вес
                    </li>
                    <li class="list-group-item">
                        <span class="badge money-badge"><?=$totalMoney ?></span>
                        Общяя сумма
                    </li>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8 product-panel-container">
        <div class="panel panel-info history-panel">
            <div class="panel-heading">
                <h3 class="panel-title">История действий</h3>
            </div>
            <div class="panel-body">
                <div class="list-group">
                    <button style="background-color: #D7ECCE" id="actionBtn" type="button" class="list-group-item" aria-label="Left Align">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true">
                            <h4 style="display: inline-block" class="list-group-item-heading">Добавить действие</h4>
                        </span>
                    </button>
                </div>
                <table class="table">
                    <thead>
                        <th>Продукт</th>
                        <th>Вес</th>
                        <th>Цена</th>
                        <th>Дата доставки</th>
                        <th>Дата полной оплаты</th>
                        <th>Оплачено</th>
                    </thead>
                    <tbody>
                        <?php

                        foreach ($history as $key => $item) {

                            $style = "";

                            echo "<tr>";
                            echo "<td>" . $item["name"] . "</td>";
                            echo "<td>" . $item["weight"] . "</td>";
                            echo "<td>" . $item["price"] . "</td>";
                            echo "<td>" . $item["date"] . "</td>";
                            if (isset($payDates[$key])) {
                                echo "<td>" . $payDates[$key] . "</td>";
                            } else {
                                echo "<td>-----</td>";
                            }
                            if($item["weight"] * $item["price"] > $item["total"]) {
                                echo '<td><button class="btn btn-danger btn-xs edit" onclick="addCashTo(' . $key .')">' . $item["total"] .'</button></td>';
                            } else {
                                echo "<td style='text-align: center'>" . $item["total"] . "</td>";
                            }
                            echo "</tr>";
                        }

                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<?php
require_once "../components/footer.php";
?>

<script type="text/javascript">

    var products = <?= json_encode($marketProducts) ?>;

    function changedToId() {
        var x = document.getElementById("products").value;
        var priceField = document.getElementById("defaultPrice");
        console.log(products);
        priceField.value = products[x].price;

        var price = document.getElementById("defaultPrice").value;
        var weight = document.getElementById("weight").value;
        document.getElementById("total").value = price * weight;
    }

    function addCashTo(id) {
        var addModal = document.getElementById('addModal');
        var history = <?= json_encode($history) ?>;

        var mustBeSum = history[id].weight * history[id].price;
        var givenSum = history[id].total;

        var difference = mustBeSum - givenSum;

        document.getElementById("addedCash").value = difference;

        addModal.style.display = "block";

        var input = document.createElement("input");

        input.setAttribute("type", "hidden");

        input.setAttribute("name", "history_id");

        input.setAttribute("value", id);

        document.getElementById("add-modal").appendChild(input);

    }
</script>
