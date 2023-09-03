<?php
class Tables
{
    public static function create(DB $db): void
    {
        $db->createProducts();
        $db->createProductInfo();
        $db->createCustomers();
        $db->createOrder();
        $db->createTransactions();
    }
}
