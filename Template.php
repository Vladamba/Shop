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
                $s .= str_replace('*product_id*', $row->product_id, file_get_contents('adminDeleteProduct.tpl'));
            }
            $s .= '<hr>';
        }
        if ($admin) {
            $s .= file_get_contents('adminAddProduct.tpl');
        }
        if (isset($_COOKIE['enter'])) {
            $s .= '<div>Hello ' . $_COOKIE['login'] . '!</div> <br> <a href="index.php?toBasket=1">To basket</a> <br> <a href="index.php?orders=1">Orders</a>';
        } else {
            $s .= '<a href="index.php?enter=1">Enter</a>';
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
                $row->bought . '; Remaining: ' . $row->remaining . '</div> <img src="' . $_GET['image'] . '" width="200" height="200"> <br>';
        }
        $s .= '<a href="index.php">Back</a>';
        if (isset($_COOKIE['enter'])) {
           $s .= '<br> <a href="index.php?addToBasket=1&product_id=' . $_GET['product_id'] . '">Add to basket</a>';
        }
        if ($admin) {
            $s .= str_replace('*product_id*', $_GET['product_id'], file_get_contents('adminProductInfo.tpl'));
        }
        return str_replace('*productInfo*', $s, file_get_contents('productInfo.tpl'));
    }

    public static function enter(): string
    {
        return file_get_contents('enter.tpl');
    }

    public static function customers($customers): string
    {
        $s = '';
        while ($row = $customers->fetch(PDO::FETCH_OBJ)) {
            $s .= '<div>Id: ' . $row->customer_id . '; Login: ' . $row->login . '; Password: ' . $row->password . '; Email: ' . $row->email . '</div> <hr>';
        }
        return str_replace('*customers*', $s, file_get_contents('customers.tpl'));
    }

    public static function basket($products): string
    {
        $s = '';
        if (!$products) {
            $s .= '<div>Empty(</div>';
        } else {
            foreach ($products as $value) {
                $row = $value->fetch(PDO::FETCH_OBJ);
                $s .= '<div>Name: ' . $row->name . '; Price: ' . $row->price .
                    ';</div> <a href="index.php?removeFromBasket=1&product_id=' . $row->product_id . '">Remove from basket</a> <hr>';
            }
        }
        $s .= '<a href="index.php?buy=1">Buy</a> <br> <a href="index.php">Back</a>';
        return str_replace('*products*', $s, file_get_contents('basket.tpl'));
    }

    public static function orders($orders): string
    {
        $s = '';
        $row = $orders->fetch(PDO::FETCH_OBJ);
        if (!$row) {
            $s .= '<div>Empty(</div>';
        } else {
            do {
                $s .= '<div>Id: ' . $row->order_id . '; Product ids: ' . $row->product_ids . '; Amount: ' . $row->amount . '; Status: ' . $row->status . '</div> <hr>';
            } while ($row = $orders->fetch(PDO::FETCH_OBJ));
        }
        $s .= '<br> <a href="index.php">Back</a>';
        return str_replace('*orders*', $s, file_get_contents('orders.tpl'));
    }
}