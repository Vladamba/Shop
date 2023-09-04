<?php

class DB
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO('mysql:host=127.0.0.1;dbname=Shop;', 'root', '2152', array(PDO::ATTR_PERSISTENT => false));
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
                        name VARCHAR(64), 
                        email VARCHAR(128));');
    }

    public function createOrders(): void
    {
        $this->execute('CREATE TABLE Orders(
                        order_id INT PRIMARY KEY AUTO_INCREMENT,
                        customer_id INT,   
                        product_ids TEXT,                                             
                        status INT,
                        FOREIGN KEY (customer_id) REFERENCES Customers(customer_id));');
    }

    public function createTransactions(): void
    {
        $this->execute('CREATE TABLE Transactions(
                        transaction_id INT PRIMARY KEY AUTO_INCREMENT,
                        amount DECIMAL(6, 2),
                        order_id INT,                                                  
                        FOREIGN KEY (order_id) REFERENCES Orders(order_id));');
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

    public function insertIntoCustomers($name, $email): void
    {
        $this->execute('INSERT INTO Customers(name, email)
                        VALUES(:Name, :Email);',
            array(
                ':Name' => $name,
                ':Email' => $email));
    }

    public function insertIntoOrders($customer_id, $product_ids, $status,): void
    {
        $this->execute('INSERT INTO Orders(customer_id, product_ids, status)
                        VALUES(:Customer_id, :Product_ids, :Status);',
            array(
                ':Customer_id' => $customer_id,
                ':Product_ids' => $product_ids,
                ':Status' => $status));
    }

    public function insertIntoTransactions($amount, $order_id): void
    {
        $this->execute('CREATE TABLE Transactions(amount, order_id)                                                  
                        VALUES(:Amount, :Order_id);',
            array(
                ':Amount' => $amount,
                ':Order_id' => $order_id));
    }

    ////////////////////////////////////////////////////////////////////////////////

    public function selectFromProducts($category): PDOStatement|false
    {
        if ($category == 'All') {
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

    public function selectFromProductInfo($product_id): PDOStatement|false
    {
        return $this->execute('SELECT * FROM ProductInfo WHERE product_id=:Product_id;',
            array(':Product_id' => $product_id));
    }

    /*
     * @TODO some selects
     */

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

    public function deleteFromTransactions($transaction_id): void
    {
        $this->execute('DELETE FROM Transactions WHERE transaction_id=:Transaction_id;',
            array(':Transaction_id' => $transaction_id));
    }
}