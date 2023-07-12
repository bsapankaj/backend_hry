<?php
    require 'MyPDOStatement.php';
    class DBConnection {
        protected $con;
        private $host;
        private $dbname;
        private $user;
        private $pass;
        private $options;
        public function __construct() {
            $this->host = 'localhost';
            $this->dbname = 'shop_H';
            $this->user = 'root';
            $this->pass = '';
            $this->options  = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,PDO::ATTR_STATEMENT_CLASS => array('MyPDOStatement', array()));
        }
        public function connect() {
            try {
                $this->con = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass,$this->options);
                return $this->con;
            }
            catch(PDOException $e) {
                echo 'ERROR: ' . $e->getMessage();
            }
        } // function ends

        public function closeConnection() {
            $this->con = null;
        }
    }
?>