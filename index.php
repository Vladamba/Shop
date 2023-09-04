<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'Template.php';
require_once 'DB.php';
$db = new DB();

//require_once 'Start.php';
//Start::createTables($db);
//Start::insertIntoProducts($db);

$admin = isset($_GET['admin']) && $_GET['admin'] == DB::ADMIN_PASSWORD;

$template = '';

if (isset($_GET['selectCustomer'])) {
    $result = $db->selectFromCustomers($_GET['login'], $_GET['password']);
    $row = $result->fetch(PDO::FETCH_OBJ);
    if ($row) {
        $_COOKIE['enter'] = 1;
        $_COOKIE['login'] = $_GET['login'];
    } else {
        $template = Template::enter();
    }
}

if (isset($_GET['enter'])) {
    $template = Template::enter();
}

if (isset($_GET['productInfo'])) {
    $template = Template::productInfo($db->selectFromProductInfo($_GET['product_id']), $admin);
}

if ($template == '') {
    if (isset($_GET['addProduct'])) {
        $db->insertIntoProducts($_GET['name'], $_GET['price'], $_GET['category'], $_GET['image']);
    }
    if (isset($_GET['deleteProduct'])) {
        $db->deleteFromProductInfo($_GET['product_id']);
        $db->deleteFromProducts($_GET['product_id']);
    }

    if (isset($_GET['addProductInfo'])) {
        $db->deleteFromProductInfo($_GET['product_id']);
        $db->insertIntoProductInfo($_GET['product_id'], $_GET['company'], $_GET['country'], $_GET['description'], $_GET['bought'], $_GET['remaining']);
    }
    if (isset($_GET['deleteProductInfo'])) {
        $db->deleteFromProductInfo($_GET['product_id']);
    }

    if (isset($_GET['addCustomer'])) {
        $db->insertIntoCustomers($_GET['login'], $_GET['password'], $_GET['email']);
    }

    if (!isset($_GET['productCategory'])) {
        $_GET['productCategory'] = DB::ALL_CATEGORIES;
    }
    $template = Template::main($db->selectCategoriesFromProducts(), $db->selectFromProducts($_GET['productCategory']), $admin);
}

echo $template;

//<form action="index.php" method="get">
//    <input type="text" name="login" placeholder="Login"/>
//    <input type="text" name="password" placeholder="Password"/>
//    <input type="email" name="email" placeholder="Email"/>
//    <input type="submit" value="Add"/>
//</form>