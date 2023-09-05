<?php

class Start
{
    public static function createTables(DB $db): void
    {
        $db->createProducts();
        $db->createProductInfo();
        $db->createCustomers();
        $db->createOrders();
    }

    public static function insertIntoProducts(DB $db): void
    {
        //$db->insertIntoProducts('Lego plane', 305.50, 'Toys', 'https://ir.ozone.ru/s3/multimedia-m/wc1000/6252688018.jpg');
        //$db->insertIntoProductInfo(1, 'Lego', 'China', 'The best Plane ever', 5, 10);

        //$db->insertIntoProducts('Lego car', 59.99, 'Toys', 'https://img.5element.by/import/images/ut/goods/good_1aaa9fbc-b11a-11ed-bb93-005056012463/42143-ferrari-daytona-sp3-konstruktor-lego-1_600.jpg');
        //$db->insertIntoProductInfo(2, 'Lego', 'Not China', 'The best Car ever', 50, 2);

        $db->insertIntoProducts('Guitar', 400.0, 'Musical Instruments', 'https://musicmarket.by/images/ab__webp/thumbnails/530/530/detailed/1055/ibanez-gio-grg121dx-bkf-black-flat-1_jpg.webp');
    }
}
