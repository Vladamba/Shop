<?php

class DB
{
    private PDO $pdo;
    public const ALL_CATEGORIES = 'All';
    public const ADMIN_PASSWORD = 2152;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=Shop;', 'root', DB::ADMIN_PASSWORD, array(PDO::ATTR_PERSISTENT => false));
    }

//    public function __destruct()
//    {
//        unset($this->pdo);
//    }

    private function execute($query, $params = null): PDOStatement|false
    {
        $result = $this->pdo->prepare($query);
        $result->execute($params);
        return $result;
    }

    public function createProducts(): void
    {
        $this->execute('CREATE TABLE Products(
                        product_id INT PRIMARY KEY AUTO_INCREMENT,
                        name VARCHAR(128), 
                        price DECIMAL(6, 2), 
                        category VARCHAR(32),
                        image VARCHAR(256));');
    }

    public function createProductInfo(): void
    {
        $this->execute('CREATE TABLE ProductInfo(
                        product_id INT,
                        company VARCHAR(32),
                        country VARCHAR(32),                          
                        description TEXT,
                        bought INT,
                        remaining INT,
                        FOREIGN KEY (product_id) REFERENCES Products(product_id));');
    }

    public function createCustomers(): void
    {
        $this->execute('CREATE TABLE Customers(
                        customer_id INT PRIMARY KEY AUTO_INCREMENT,
                        login VARCHAR(64), 
                        password VARCHAR(64),
                        email VARCHAR(128));');
    }

    public function createOrders(): void
    {
        $this->execute('CREATE TABLE Orders(
                        order_id INT PRIMARY KEY AUTO_INCREMENT,
                        customer_id INT,   
                        product_ids TEXT, 
                        amount DECIMAL(6, 2),                                            
                        status varchar(32),
                        FOREIGN KEY (customer_id) REFERENCES Customers(customer_id));');
    }

    ////////////////////////////////////////////////////////////////////////////////

    public function insertIntoProducts($name, $price, $category, $image): void
    {
        $this->execute('INSERT INTO Products(name, price, category, image) 
                        VALUES(:Name, :Price, :Category, :Image);',
            array(
                ':Name' => $name,
                ':Price' => $price,
                ':Category' => $category,
                ':Image' => $image));
    }

    public function insertIntoProductInfo($product_id, $company, $country, $description, $bought, $remaining): void
    {
        $this->execute('INSERT INTO ProductInfo(product_id, company, country, description, bought, remaining)
                        VALUES(:Product_id, :Company, :Country, :Description, :Bought, :Remaining);',
            array(
                ':Product_id' => $product_id,
                ':Company' => $company,
                ':Country' => $country,
                ':Description' => $description,
                ':Bought' => $bought,
                ':Remaining' => $remaining));
    }

    public function insertIntoCustomers($login, $password, $email): void
    {
        $this->execute('INSERT INTO Customers(login, password, email)
                        VALUES(:Login, :Password, :Email);',
            array(
                ':Login' => $login,
                ':Password' => $password,
                ':Email' => $email));
    }

    public function insertIntoOrders($customer_id, $product_ids, $amount, $status): void
    {
        $this->execute('INSERT INTO Orders(customer_id, product_ids, amount, status)
                        VALUES(:Customer_id, :Product_ids, :Amount, :Status);',
            array(
                ':Customer_id' => $customer_id,
                ':Product_ids' => $product_ids,
                ':Amount' => $amount,
                ':Status' => $status));
    }

    ////////////////////////////////////////////////////////////////////////////////

    public function selectFromProducts($category): PDOStatement|false
    {
        if ($category == DB::ALL_CATEGORIES) {
            return $this->execute('SELECT * FROM Products;');
        } else {
            return $this->execute('SELECT * FROM Products WHERE category=:Category;',
                array(':Category' => $category));
        }
    }

    public function selectCategoriesFromProducts(): PDOStatement|false
    {
        return $this->execute('SELECT DISTINCT(category) FROM Products;');
    }

    public function selectNamePriceFromProducts($product_id): PDOStatement|false
    {
        return $this->execute('SELECT product_id, name, price FROM Products WHERE product_id=:Product_id;',
            array(':Product_id' => $product_id));
    }

    public function selectPriceFromProducts($product_id): PDOStatement|false
    {
        return $this->execute('SELECT price FROM Products WHERE product_id=:Product_id;',
            array(':Product_id' => $product_id));
    }

    public function selectFromProductInfo($product_id): PDOStatement|false
    {
        return $this->execute('SELECT * FROM ProductInfo WHERE product_id=:Product_id;',
            array(':Product_id' => $product_id));
    }

    public function selectCustomerIdFromCustomers($login, $password): PDOStatement|false
    {
        return $this->execute('SELECT customer_id FROM Customers WHERE login=:Login AND password=:Password;',
            array(
                ':Login' => $login,
                ':Password' => $password));
    }

    public function selectFromCustomers(): PDOStatement|false
    {
        return $this->execute('SELECT * FROM Customers;');
    }

    public function selectFromOrders($customer_id): PDOStatement|false
    {
        return $this->execute('SELECT * FROM Orders WHERE customer_id=:Customer_id;',
            array(
                ':Customer_id' => $customer_id));
    }

    ////////////////////////////////////////////////////////////////////////////////

    public function deleteFromProducts($product_id): void
    {
        $this->execute('DELETE FROM Products WHERE product_id=:Product_id;',
            array(':Product_id' => $product_id));
    }

    public function deleteFromProductInfo($product_id): void
    {
        $this->execute('DELETE FROM ProductInfo WHERE product_id=:Product_id;',
            array(':Product_id' => $product_id));
    }

    public function deleteFromCustomers($customer_id): void
    {
        $this->execute('DELETE FROM Customers WHERE customer_id=:Customer_id;',
            array(':Customer_id' => $customer_id));
    }

    public function deleteFromOrders($order_id): void
    {
        $this->execute('DELETE FROM Orders WHERE order_id=:Order_id;',
            array(':Order_id' => $order_id));
    }

}