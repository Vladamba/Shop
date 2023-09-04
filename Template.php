<?php

class Template
{
    public static function main($category, $options, $products, $admin): string
    {
        $s = '';
        if ($category == 'All') {
            $s .= '<option value="All" selected>All</option>';
        } else {
            $s .= '<option value="All">All</option>';
        }
        while ($row = $options->fetch(PDO::FETCH_OBJ)) {
            if ($row->category == $category) {
                $s .= '<option value="' . $row->category . '" selected>' . $row->category . '</option>';
            } else {
                $s .= '<option value="' . $row->category . '">' . $row->category . '</option>';
            }
        }
        $t = str_replace('*options*', $s, file_get_contents('main.tpl'));

        $s = '';
        while ($row = $products->fetch(PDO::FETCH_OBJ)) {
            $s .= '<div>Id: ' . $row->product_id . '; Name: ' . $row->name . '; Price: ' . $row->price . '; Category: ' . $row->category .
                '</div> <img src="' . $row->image . '" width="200" height="200"> <br> <a href="index.php?info=1&product_id=' . $row->product_id . '&image=' . $row->image . '">More...</a>';
            if ($admin) {
                $s .= '<br> <a href="index.php?delete=1&product_id=' . $row->product_id . '">Delete</a>';
            }
            $s .= '<hr>';
        }
        return str_replace('*products*', $s, $t);
    }
}