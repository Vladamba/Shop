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

if (isset($_GET['add'])) {
    if ($_GET['add'] == 0) {
        $db->deleteFromProductInfo($_GET['product_id']);
        $db->insertIntoProductInfo($_GET['product_id'], $_GET['company'], $_GET['country'], $_GET['description'], $_GET['bought'], $_GET['remaining']);
        $_GET['info'] = 1;
    } else {
        $template = Template::add();
    }
}
if (isset($_GET['info'])) {
    $template = Template::info($db->selectFromProductInfo($_GET['product_id']), $admin);
}
if ($template == '') {
    if (isset($_GET['delete'])) {
        $db->deleteFromProductInfo($_GET['product_id']);
        $db->deleteFromProducts($_GET['product_id']);
    }

    if (!isset($_GET['category'])) {
        $_GET['category'] = DB::ALL_CATEGORIES;
    }
    $template = Template::main($db->selectCategoriesFromProducts(), $db->selectFromProducts($_GET['category']), $admin);
}

echo $template;

//<form action="index.php" method="get">
//    <input type="text" name="login" placeholder="Login"/>
//    <input type="text" name="password" placeholder="Password"/>
//    <input type="email" name="email" placeholder="Email"/>
//    <input type="submit" value="Add"/>
//</form>