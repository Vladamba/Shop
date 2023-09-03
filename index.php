<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'DB.php';
$db = new DB();

//require_once 'Tables.php';
//Tables::create($db);

if (isset($_GET['delete'])) {
    $db->deleteFromProductInfo($_GET['product_id']);
    $db->deleteFromProducts($_GET['product_id']);
}

if (isset($_GET['info'])) {
    $result = $db->selectFromProductInfo($_GET['product_id']);
    $row = $result->fetch(PDO::FETCH_OBJ);
    if (!$row) {
        exit('No info(');
    }
    echo 'Company: ' . $row->company . '; Country: ' . $row->country . '; Description: ' . $row->description . '; Bought: ' .
        $row->bought . '; Remaining: ' . $row->remaining . '<br> <img src="' . $_GET['image'] . '" width="200" height="200">' . '<br> <a href="index.php">Back</a>';
} else {
    $admin = isset($_GET['admin']) && $_GET['admin'] == 2152;
    $result = $db->selectFromProducts();
    while ($row = $result->fetch(PDO::FETCH_OBJ)) {
        echo 'Id: ' . $row->product_id . '; Name: ' . $row->name . '; Price: ' . $row->price . '; Category: ' . $row->category .
            '<br> <img src="' . $row->image . '" width="200" height="200"> <br> <a href="index.php?info=1&product_id=' . $row->product_id . '&image=' . $row->image . '">More...</a>';
        if ($admin) {
            echo '<br> <a href="index.php?delete=1&product_id=' . $row->product_id . '">Delete</a>';
        }
        echo '<hr>';
    }
}


//$db->insertIntoProducts('Lego plane', 305.50, 'Toys', 'https://ir.ozone.ru/s3/multimedia-m/wc1000/6252688018.jpg');
//$db->deleteFromProducts(3);
//$db->insertIntoProductInfo(2, 'Lego', 'China', 'The best Car ever', 5, 10);
