<?php

class Template
{
    public static function main($options, $products, $admin): string
    {
        $s = '';
        $f = false;
        while ($row = $options->fetch(PDO::FETCH_OBJ)) {
            if ($row->category == $_GET['productCategory']) {
                $s .= '<option value="' . $row->category . '" selected>' . $row->category . '</option>';
                $f = true;
            } else {
                $s .= '<option value="' . $row->category . '">' . $row->category . '</option>';
            }
        }
        if ($f) {
            $s = '<option value="All">All</option>' . $s;
        } else {
            $s = '<option value="All" selected>All</option>' . $s;
        }
        $t = str_replace('*options*', $s, file_get_contents('main.tpl'));

        $s = '';
        while ($row = $products->fetch(PDO::FETCH_OBJ)) {
            $s .= '<div>Id: ' . $row->product_id . '; Name: ' . $row->name . '; Price: ' . $row->price . '; Category: ' . $row->category .
                '</div> <img src="' . $row->image . '" width="200" height="200"> <br> <a href="index.php?productInfo=1&product_id=' . $row->product_id . '&image=' . $row->image . '">Info</a>';
            if ($admin) {
                $s .= str_replace('*product_id*', $row->product_id, file_get_contents('adminDeleteProduct.tpl'));;
            }
            $s .= '<hr>';
        }
        if ($admin) {
            $s .= file_get_contents('adminAddProduct.tpl');
        }
        return str_replace('*products*', $s, $t);
    }

    public static function productInfo($productInfo, $admin): string
    {
        $s = '';
        $row = $productInfo->fetch(PDO::FETCH_OBJ);
        if (!$row) {
            $s .= '<div>No info(</div>';
        } else {
            $s .= '<div>Company: ' . $row->company . '; Country: ' . $row->country . '; Description: ' . $row->description . '; Bought: ' .
                $row->bought . '; Remaining: ' . $row->remaining . '</div> <img src="' . $_GET['image'] . '" width="200" height="200">' . '<br> <a href="index.php">Back</a>';
        }
        if ($admin) {
            $s .= str_replace('*product_id*', $_GET['product_id'], file_get_contents('adminProductInfo.tpl'));
        }
        return str_replace('*productInfo*', $s, file_get_contents('productInfo.tpl'));
    }
}