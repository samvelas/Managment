<?php
require_once "../components/header.php";
require_once "../dbActions/db_markets.php";
require_once "../components/nav.php"
?>

    <div class="container">

        <div id="myModal" class="modal">

            <!-- Modal content -->

            <div class="modal-content" id="content">
                <form method="post" action="market-list.php" enctype="multipart/form-data" name="myForm" id="form">
                    <h2>Введите название магазина</h2>
                    <input id="title" class="form-control" name="name" placeholder="Название"><br>
                    <button class="btn btn-info btn-lg" type="submit">Добавить</button>
                </form>
            </div>

        </div>

        <span class="page-header" id="header">
            <h1>
                Лист Магазинов
            </h1>
        </span>
        <span id="add-button">
            <button id="myBtn" type="button" class="btn btn-success btn-lg" aria-label="Left Align">
                <span class="glyphicon glyphicon-plus" aria-hidden="true">
                    Добавить
                </span>
            </button>
        </span>

<?php

session_start();
$userId = $_SESSION["userId"];
$it = 1;

if(isset($_POST["name"]) && $_POST["name"] != "") {
    $newMarketName = $_POST["name"];
    createMarket($newMarketName);
}

$markets = getMarkets();
$quantity = count($markets);

?>

    <table class="table">
        <thead>
            <th>#</th>
            <th>Название магазина</th>
        </thead>
        <tbody>
        <?php
        foreach ($markets as $key => $market) {
            echo "<tr onclick='openMarketAtId(" . $key . ")'>";
            echo "<td style='font-weight: bolder'>" . ($it) . "</td>";
            echo "<td id='title-limit'>" . $market . "</td>";
//            echo '<td><button class="btn btn-danger btn-md edit" onclick="confirmDeleteOf(' . $currentPage . ', ' . $posts[$i]->getId() . ')">Delete</button></td>';
//            echo '<td><button class="btn btn-warning btn-md delPost" onclick="editPost(' . $i . ')">Edit</button></td>';
            echo "</tr>";
            $it++;
        }
        ?>
        </tbody>
    </table>


    </div>
<?php
require_once "../components/footer.php";
?>
