<?php require_once '../config/DBConnection.php';

class User_Request{
    public $table_name,$user_request_id, $user_request_name	, $email_id, $company_name, $mobile_no, $father_name,$address,$pincode,  $is_active, $created_by,$created_on, $updated_by, $updated_on, $db, $conn;
    function __construct(){
        $this->user_request_id = 0;
        $this->user_request_name = "";
        $this->email_id = "";
        $this->company_name = "";
        $this->mobile_no = "";
        $this->father_name = "";
        $this->address = "";
        $this->pincode = 0;
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'user_request';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert(){
        $data = [
            'user_request_name'     => $this->user_request_name,
            'email_id'              => $this->email_id,
            'company_name'          => $this->company_name,
            'mobile_no'             => $this->mobile_no,
            'father_name'           => $this->father_name,
            'address'               => $this->address,
            'pincode'               => $this->pincode,
            'is_active'             => $this->is_active,
            'created_by'            => $this->created_by
        ];
        $sql = "INSERT INTO ".$this->table_name."(user_request_name, email_id, company_name, mobile_no, is_active,father_name,address,pincode, created_by) VALUES (:user_request_name, :email_id, :company_name, :mobile_no, :is_active, :father_name,:address,:pincode, :created_by)";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        // print_r($stmt);exit;
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery(); 
        // echo $debug_query;exit;
        $this->user_request_id = $this->conn->lastInsertId();
        $stmt->closeCursor();
        return true;
    }

    function update() {

        $data = [
            'user_request_id'       => $this->user_request_id,
            'user_request_name'     => $this->user_request_name,
            'email_id'              => $this->email_id,
            'company_name'          => $this->company_name,
            'mobile_no'             => $this->mobile_no,
            'father_name'           => $this->father_name,
            'address'               => $this->address,
            'pincode'               => $this->pincode,
            'is_active'             => $this->is_active,
            'updated_by'            => $this->updated_by,
        ];
        $sql = "UPDATE ".$this->table_name." SET user_request_name=:user_request_name, email_id=:email_id, company_name=:company_name, mobile_no=:mobile_no, father_name=:father_name, address=:address, pincode=:pincode, is_active=:is_active, updated_by=:updated_by WHERE user_request_id=:user_request_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        // print_r($stmt);exit;
        $stmt->closeCursor();
        return true;
    }

    function delete() {
        $data = [
            'user_request_id'   => $this->user_request_id,
            'is_active'         => 2,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET is_active=:is_active, updated_by=:updated_by WHERE user_request_id=:user_request_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        $stmt->closeCursor();
        return $last_query;
    }

    function get($Request) {
        $output = [];
        $data = [
            'is_active' => 2
        ];
        if(!empty($Request)){
            $query = "SELECT u_qst.user_request_id, u_qst.user_request_name, u_qst.email_id, 
            u_qst.company_name, u_qst.mobile_no, u_qst.father_name, u_qst.address, u_qst.pincode, 
            u.name as rqst_by FROM ".$this->table_name." u_qst
            INNER JOIN user u ON (u.user_id=u_qst.created_by)
            WHERE u_qst.is_active < :is_active"; 
            if (isset($Request->search->value) && $Request->search->value != '' ) {
                $data['search_value'] = '%'.$Request->search->value.'%';
                $query .= " AND (user_request_id LIKE :search_value";
                $query .= " OR name LIKE :search_value";
                $query .= " OR email_id LIKE :search_value";
                $query .= " OR mobile_no LIKE :search_value)";
            } 
            if($this->user_request_id>0) {
                $data['user_request_id'] = $this->user_request_id;
                $query .= " AND user_request_id = :user_request_id";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY ".$Request->order['0']->column." ".$Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY email_id asc';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start. ',' . $Request->length;
            }
        }else{
            if($this->user_request_id>0) {
                $data = [
                    'user_request_id'   => $this->user_request_id
                ];
                $query = "SELECT * FROM ".$this->table_name." WHERE u_qst.user_request_id  :user_request_id";
            } else {
                $query = "SELECT * FROM ".$this->table_name." WHERE u_qst.is_active < :is_active";
            }
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        // print_r($results);exit;

        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery(); 
        // echo $debug_query;exit;
        $stmt->closeCursor();
        if($count>0) {
            foreach($results as $row) {
                $output[] = [
                    'user_request_id'       => $row['user_request_id'],
                    'user_request_name'     => $row['user_request_name'],
                    'email_id'              => $row['email_id'],
                    'company_name'          => $row['company_name'],
                    'mobile_no'             => $row['mobile_no'],
                    'father_name'           => $row['father_name'],
                    'address'               => $row['address'],
                    'rqst_by'               => $row['rqst_by'],
                    'pincode'               => $row['pincode']
                ];
            }
        }
        return $output;
    }

    // Check User name
    
    function check() {
        $data = [
            'user_request_name' => $this->user_request_name,
            'is_active' => 1
        ];
        $stmt = $this->conn->prepare("SELECT user_request_id FROM ".$this->table_name." WHERE user_request_name = :user_request_name 
                AND is_active = :is_active");
        $stmt->execute($data);
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery(); 
        // echo $debug_query;exit;
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if($count>0) {
            $this->user_request_id = $row['user_request_id'];
            return true;
        } else 
            return false;
            
        }
    // Check User Email Id
    function checkEmail() {
        $data = [
            'email_id'          => $this->email_id,
            'is_active' => 1
        ];
        $stmt = $this->conn->prepare("SELECT user_request_id FROM ".$this->table_name." WHERE email_id = :email_id 
                AND is_active = :is_active");
        $stmt->execute($data);
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery(); 
        // echo $debug_query;exit;
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if($count>0) {
            $this->user_request_id = $row['user_request_id'];
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
