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

if (isset($_GET['info'])) {
    echo Template::info($db->selectFromProductInfo($_GET['product_id']), $admin);
} else {

    if (isset($_GET['delete'])) {
        $db->deleteFromProductInfo($_GET['product_id']);
        $db->deleteFromProducts($_GET['product_id']);
    }

    if (!isset($_GET['category'])) {
        $_GET['category'] = DB::ALL_CATEGORIES;
    }
    echo Template::main($db->selectCategoriesFromProducts(), $db->selectFromProducts($_GET['category']), $admin);
}