<?php
require_once "../components/header.php";
require_once "../dbActions/db_products.php";
require_once "../components/nav.php";


if (!isset($_SESSION["userId"])) {
    header('Location: ../index.php');
}


$userId = $_SESSION["userId"];
?>

<div class="container">

    <div id="productModal" class="modal">

        <!-- Modal content -->

        <div class="modal-content" id="content">
            <form method="post" action="product-list.php" enctype="multipart/form-data" name="myForm" id="form">
                <h2>Введите название Продукта</h2>
                <input id="title" class="form-control" name="name" placeholder="Название"><br>
                <button class="btn btn-info btn-lg" type="submit">Добавить</button>
            </form>
        </div>

    </div>

    <span class="page-header" id="header">
            <h1>
                Лист Продуктов
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

    $it = 1;

    if(isset($_POST["name"]) && $_POST["name"] != "") {
        $newProductName = $_POST["name"];
        createProduct($userId, $newProductName);
    }

    $products = getProducts($userId);
    $quantity = count($products);

    ?>

    <table class="table">
        <thead>
            <th>#</th>
            <th>Название продукта</th>
        </thead>
        <tbody>

        <?php
        foreach ($products as $product) {
            echo "<tr>";
            echo "<td style='font-weight: bolder'>" . ($it) . "</td>";
            echo "<td id='title-limit'>" . $product . "</td>";
//            echo '<td><button class="btn btn-danger btn-md edit" onclick="confirmDeleteOf(' . $currentPage . ', ' . $posts[$i]->getId() . ')">Delete</button></td>';
//            echo '<td><button class="btn btn-warning btn-md delPost" onclick="editPost(' . $i . ')">Edit</button></td>';
            echo "</tr>";
            $it++;
        }
        ?>
        </tbody>
    </table>



    <?php
    require_once "../components/footer.php";
    ?>

</div>
