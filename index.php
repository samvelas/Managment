<?php
require_once "components/header.php";
require "db_users.php";

$check = false;
session_start();

if(isset($_GET["action"])) {
    if($_GET["action"] == "out") {
        session_destroy();
        header('Location: index.php');
    }
}

if(isset($_POST["login"]) && isset($_POST["password"]) && $_POST["login"] != "" && $_POST["password"] != "") {
    $login = $_POST["login"];
    $password = $_POST["password"];

    if(existsUser($login, $password)) {
        $user = existsUser($login, $password);
        $check = true;
    }
}

if ($check) {
    $_SESSION['valid'] = true;
    $_SESSION['timeout'] = time();
    $_SESSION['userId'] = $user["id"];
    $_SESSION['username'] = $user["username"];
}

if(isset($_SESSION["userId"])) {
    $check = true;
}

backUp();

?>

<div class="container">
    <div class="row main-page-row">
        <div class="col-md-offset-4 col-md-4">
            <?php
            if(!$check) {
                echo '
                    <form method="post" action="index.php">
                        <input id="login" name="login" class="form-control" placeholder="Имя пользователья">
                        <input id="password" name="password" type="password" class="form-control" placeholder="Пароль">
                        <button id="login-btn" type="submit" class="btn btn-lg btn-info">Log In</button>
                    </form>';
                } else {
                    echo '
                    <div >
                        <a class="btn btn-default btn-lg" > Покупка</a >
                    </div >
                    <div >
                        <a href = "pages/market-list.php" class="btn btn-default btn-lg" > Продажа</a >
                    </div >
                    <div >
                        <a href = "pages/product-list.php" class="btn btn-default btn-lg" > Продукты</a >
                    </div >
                    <div >
                        <a class="btn btn-default btn-lg" > Итог</a >
                    </div >
                    <div >
                        <a href = "pages/history.php" class="btn btn-default btn-lg" > Вся история </a >
                    </div >
                    <div >
                        <a href = "index.php?action=out" class="btn btn-warning btn-lg" > Выйти из системы</a >
                    </div >';

                }
            ?>
        </div>
    </div>
</div>

<?php
require_once "components/footer.php";
?>