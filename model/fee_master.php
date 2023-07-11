<?php
require_once '../config/DBConnection.php';

class Fee_master{ 

    Public $fee_master_id, $client_code_id, $particulars, $task_id, $unit, $fee, $is_active, $created_by, $created_on, $updated_by, $updated_on, $table_name, $db, $conn;

    function __construct(){
        $this->fee_master_id = 0;
        $this->client_code_id = 0;
        $this->particulars = "";
        $this->task_id = 0;
        $this->unit = "";
        $this->fee = 0;
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = "fee_master";
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    public function insert(){
        $data = [
            "client_code_id" => $this->client_code_id,
            "particulars" => $this->particulars,
            "task_id" => $this->task_id,
            "unit" => $this->unit,
            "fee" => $this->fee,
            "is_active" => $this->is_active,
            "created_by" => $this->created_by
        ];

        $sql = "INSERT INTO ".$this->table_name." (client_code_id, particulars, task_id, unit, fee, is_active, created_by) VALUES(:client_code_id, :particulars, :task_id, :unit, :fee, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        return true;
    }

    // Update 
    public function update(){
        $data = [
            "fee_master_id" => $this->fee_master_id,
            "client_code_id" => $this->client_code_id,
            "particulars" => $this->particulars,
            "task_id" => $this->task_id,
            "unit" => $this->unit,
            "fee" => $this->fee,
            "is_active" => $this->is_active,
            "updated_by" => $this->updated_by
           
        ];
        // print_r($data);exit;
        $sql = "UPDATE ".$this->table_name." SET client_code_id = :client_code_id, particulars = :particulars, task_id = :task_id, unit = :unit, fee = :fee, is_active = :is_active, updated_by = :updated_by WHERE fee_master_id = :fee_master_id AND is_active = :is_active";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }


    //Delete
    public function delete(){
        $data = [
            "fee_master_id"  => $this->fee_master_id,
            "is_active"     => 2,
            "updated_by"    => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET is_active = :is_active, updated_by = :updated_by WHERE fee_master_id = :fee_master_id";
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

        if(empty($Request)){
            $query = "SELECT fee_master_id,fm.client_code_id, fm.particulars, fm.task_id, unit, fee, cc.client_name, tt.task_type
            FROM ".$this->table_name." fm
            INNER JOIN client_code as cc ON (fm.client_code_id=cc.client_code_id AND cc.is_active=1)
            INNER JOIN task_type as tt ON (fm.task_id=tt.task_id AND tt.is_active=1)
            WHERE fm.is_active < :is_active";
            if (isset($Request->search->value)) {
                $data['search_value'] = '%'.$Request->search->value.'%';
                $query .= " AND (fm.client_code_id LIKE :search_value";
                $query .= " OR fm.task_id LIKE :search_value";
                $query .= " OR unit LIKE :search_value)";
            } 
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY ".$Request->order['0']->column." ".$Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY fee_master_id asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start. ', ' . $Request->length;
            }

        }else{
            if(isset($this->client_code_id) && $this->client_code_id>0){
                $data = [
                    "client_code_id"    => $this->client_code_id,
                    "is_active"         => 2
                ];
                $query = "SELECT fee_master_id,fm.client_code_id, fm.particulars, fm.task_id, unit, fee, cc.client_name, tt.task_type
                FROM ".$this->table_name." fm
                INNER JOIN client_code as cc ON (fm.client_code_id=cc.client_code_id AND cc.is_active=1)
                INNER JOIN task_type as tt ON (fm.task_id=tt.task_id AND tt.is_active=1)
                WHERE fm.client_code_id=:client_code_id AND fm.is_active < :is_active";
            } else {
                $query = "SELECT fee_master_id,fm.client_code_id, fm.particulars, fm.task_id, unit, fee, cc.client_name, tt.task_type
                FROM ".$this->table_name." fm
                INNER JOIN client_code as cc ON (fm.client_code_id=cc.client_code_id AND cc.is_active=1)
                INNER JOIN task_type as tt ON (fm.task_id=tt.task_id AND tt.is_active=1)
                WHERE fm.is_active < :is_active";
            }
            
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        $debug_query = $stmt->_debugQuery();
        $results = $stmt->fetchAll();
        // echo "<pre>";
        // print_r($results);exit;
        $count = $stmt->rowCount();
        $stmt->closeCursor();

        if($count > 0){
            foreach($results as $row){
                $output[] = [
                    'fee_master_id' => $row['fee_master_id'],
                    'client_code_id' => $row['client_code_id'],
                    'particulars' => $row['particulars'],
                    'task_id' => $row['task_id'],
                    'client_name' => $row['client_name'],
                    'task_type' => $row['task_type'],
                    'unit' => $row['unit'],
                    'fee' => $row['fee']
                ];
            }
        }
        return $output;



    }

    public function get_by_client_id_and_task_id($Request = []){
        $output = [];
        $data = [
            "is_active"  => 2,
            "client_code_id" =>$this->client_code_id,
            "task_id" =>$this->task_id
        ];
        $query = "SELECT fee_master_id,fm.client_code_id, fm.particulars, fm.task_id, unit, fee, cc.client_name, tt.task_type
        FROM ".$this->table_name." fm
        INNER JOIN client_code as cc ON (fm.client_code_id=cc.client_code_id AND cc.is_active=1)
        INNER JOIN task_type as tt ON (fm.task_id=tt.task_id AND tt.is_active=1)
        WHERE fm.is_active < :is_active AND fm.client_code_id = :client_code_id AND fm.task_id = :task_id";
      

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
                    'fee_master_id' => $row['fee_master_id'],
                    'client_code_id' => $row['client_code_id'],
                    'particulars' => $row['particulars'],
                    'task_id' => $row['task_id'],
                    'client_name' => $row['client_name'],
                    'task_type' => $row['task_type'],
                    'unit' => $row['unit'],
                    'fee' => $row['fee']
                ];
            }
        }
        return $output;



    }

    // Check
    public function check() {

        $data = [
            "client_code_id" => $this->client_code_id,
            "task_id" => $this->task_id,
            'is_active'     => 1,
        ];
        $stmt = $this->conn->prepare("SELECT fee_master_id FROM ".$this->table_name." WHERE client_code_id = :client_code_id AND task_id = :task_id AND is_active=:is_active");
        $stmt->execute($data);
        $count = $stmt->rowCount();
        if($count>0) {
            $row = $stmt->fetch();
            $this->fee_master_id = $row['fee_master_id'];
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
