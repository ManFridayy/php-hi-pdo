<?php
//Database.php
//PDO Support PHP 5 >= 5.1.0, PHP 7, PECL pdo >= 0.1.0

interface Singleton {
    public static function getInstance();
}

class Database implements Singleton {
    private static $instance;
    private $pdo;

    private function __construct() {
        $this->pdo = new PDO(
            "mysql:host=localhost;dbname=me",
            "root",
            "root**",
			array(
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_PERSISTENT => true,
				PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8' COLLATE 'utf8_general_ci'",
                PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => false
			)         
        );
    }

    public static function getInstance() {
        if(self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }
}

?>
