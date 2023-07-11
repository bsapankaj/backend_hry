<?php
require_once '../config/DBConnection.php';

class User_Test{

    Public $user_type_id, $user_type, $sort_code, $is_active, $created_by, $created_on, $updated_by, $updated_on, $table_name, $db, $conn;

    function __construct(){
        $this->user_type_id = "";
        $this->user_type = "";
        $this->sort_code = "";
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = "test";
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    public function insert(){
        $data = [
            "user_type"     => $this->user_type,
            "sort_code"     => $this->sort_code,
            "is_active"     => $this->is_active,
            "created_by"    => $this->created_by
        ];

        $sql = "INSERT INTO ".$this->table_name." (user_type, sort_code, is_active, created_by) VALUES(:user_type,  :sort_code, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query;exit;
        return true;
    }

    // Update 
    public function update(){
        $data = [
            "user_type_id"  => $this->user_type_id,
            "user_type"     => $this->user_type,
            "sort_code"     => $this->sort_code,
            "is_active"     => $this->is_active,
            "created_by"    => $this->created_by,
           
        ];
        // print_r($data);exit;
        $sql = "UPDATE ".$this->table_name." SET user_type = :user_type, sort_code = :sort_code, is_active = :is_active, created_by = :created_by WHERE user_type_id = :user_type_id AND is_active = :is_active";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query;exit;
        return true;
    }


    //Delete
    public function delete(){
        $data = [
            "user_type_id"  => $this->user_type_id,
            "is_active"     => 2,
            "updated_by"    => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET is_active = :is_active, updated_by = :updated_by WHERE user_type_id = :user_type_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    
    // Get
    public function get($Request = []){
        $output = [];
        $data = [
            "is_active"  => 2
        ];

        if(!empty($Request)){
            $query = "SELECT user_type_id,user_type, sort_code FROM ".$this->table_name." WHERE is_active < :is_active";

        }else{
            $query = "SELECT * FROM ".$this->table_name." WHERE is_active < :is_active";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();

        if($count > 0){
            foreach($results as $row){
                $output[] = [
                    'user_type_id' => $row['user_type_id'],
                    'user_type' => $row['user_type'],
                    'sort_code' => $row['sort_code'],
                    // 'sort_order' => $row['sort_order']
                ];
            }
        }
        return $output;



    }

    // Check
    public function check() {

        $data = [
            'user_type'   => $this->user_type,
            'is_active'     => 1,
        ];
        $stmt = $this->conn->prepare("SELECT user_type_id FROM ".$this->table_name." WHERE user_type = :user_type AND is_active=:is_active");
        $stmt->execute($data);
        $count = $stmt->rowCount();
        if($count>0) {
            $row = $stmt->fetch();
            $this->user_type_id = $row['user_type_id'];
            return true;
        } else 
            return false;
    }

    function get_total_count(){
        $stmt = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE is_active < 2");
        $stmt->execute();
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $count;
    }





}

?>