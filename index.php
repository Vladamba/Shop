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

if (isset($_GET['buy'])) {
    if (isset($_COOKIE['product_ids']) && trim($_COOKIE['product_ids']) != '') {
        $product_ids = explode(' ', $_COOKIE['product_ids']);
        $amount = 0;
        foreach ($product_ids as $value) {
            if ($value != '') {
                $result = $db->selectPriceFromProducts($value);
                $row = $result->fetch(PDO::FETCH_OBJ);
                $amount += $row->price;
            }
        }
        $db->insertIntoOrders($_COOKIE['customer_id'], trim($_COOKIE['product_ids']), $amount, 'Flying to you');
    }
}

if (isset($_GET['orders'])) {
    $template = Template::orders($db->selectFromOrders($_COOKIE['customer_id']));
}

if (isset($_GET['removeFromBasket'])) {
    $s = str_replace(' ' . $_GET['product_id'], '', $_COOKIE['product_ids']);
    setcookie('product_ids', $s);
}

if (isset($_GET['toBasket'])) {
    if (isset($_COOKIE['product_ids']) && trim($_COOKIE['product_ids']) != '') {
        $product_ids = explode(' ', $_COOKIE['product_ids']);
        $products = [];
        foreach ($product_ids as $value) {
            if ($value != '') {
                $products[] = $db->selectNamePriceFromProducts($value);
            }
        }
        $template = Template::basket($products);
    } else {
        $template = Template::basket(null);
    }
}

if (isset($_POST['selectCustomer'])) {
    $result = $db->selectCustomerIdFromCustomers($_POST['login'], $_POST['password']);
    $row = $result->fetch(PDO::FETCH_OBJ);
    if ($row) {
        setcookie('enter', 1);
        setcookie('customer_id', $row->customer_id);
        setcookie('login', $_POST['login']);
        exit('<a href="index.php">OK</a>');
    } else {
        $template = Template::enter();
    }
}

if (isset($_GET['enter'])) {
    if ($admin) {
        $template = Template::customers($db->selectFromCustomers());
    } else {
        $template = Template::enter();
    }
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

    if (isset($_POST['addCustomer'])) {
        $db->insertIntoCustomers($_POST['login'], $_POST['password'], $_POST['email']);
    }

    if (isset($_GET['addToBasket'])) {
        if (isset($_COOKIE['product_ids'])) {
            setcookie('product_ids', $_COOKIE['product_ids'] . ' ' . $_GET['product_id']);
        } else {
            setcookie('product_ids', ' ' . $_GET['product_id']);
        }
    }

    if (!isset($_GET['productCategory'])) {
        $_GET['productCategory'] = DB::ALL_CATEGORIES;
    }
    $template = Template::main($db->selectCategoriesFromProducts(), $db->selectFromProducts($_GET['productCategory']), $admin);
}

echo $template;