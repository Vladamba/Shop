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

if (isset($_GET['delete'])) {
    $db->deleteFromProductInfo($_GET['product_id']);
    $db->deleteFromProducts($_GET['product_id']);
}

if (!isset($_GET['category'])) {
    $_GET['category'] = DB::ALL_CATEGORIES;
}
echo Template::main($_GET['category'], $db->selectCategoriesFromProducts(), $db->selectFromProducts($_GET['category']), $admin);



//if (isset($_GET['category']) && $row->category == $_GET['category'])


//
//

//
//if (isset($_GET['add'])) {
//
//}
//
//if (isset($_GET['info'])) {
//    $result = $db->selectFromProductInfo($_GET['product_id']);
//    $row = $result->fetch(PDO::FETCH_OBJ);
//    if (!$row) {
//        echo 'No info(';
//        if ($admin) {
//            echo '<br> <a href="index.php?add=1&product_id=' . $_GET['product_id'] . '">Add</a>';
//        }
//    } else {
//        echo 'Company: ' . $row->company . '; Country: ' . $row->country . '; Description: ' . $row->description . '; Bought: ' .
//            $row->bought . '; Remaining: ' . $row->remaining . '<br> <img src="' . $_GET['image'] . '" width="200" height="200">' . '<br> <a href="index.php">Back</a>';
//    }
//} else {
//
//    }
