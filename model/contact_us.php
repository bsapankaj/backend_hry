<?php require_once '../config/DBConnection.php';

class Contact_Us{
    public $table_name,$contactUs_id, $contact_name, $email_id, $contactMessage, $mobile_no, $is_active, $created_by,$created_on, $updated_by, $updated_on, $db, $conn;
    function __construct(){
        $this->contactUs_id = 0;
        $this->contact_name = "";
        $this->email_id = "";
        $this->contactMessage = "";
        $this->mobile_no = "";
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'contact_us';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert(){
        $data = [
            'contact_name'          => $this->contact_name,
            'email_id'              => $this->email_id,
            'mobile_no'             => $this->mobile_no,
            'contactMessage'        => $this->contactMessage,
            'is_active'             => $this->is_active,
            'created_by'            => $this->created_by
        ];
        $sql = "INSERT INTO ".$this->table_name."(contact_name, email_id, mobile_no, is_active, contactMessage, created_by) VALUES (:contact_name, :email_id, :mobile_no, :is_active, :contactMessage, :created_by)";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        // print_r($stmt);exit;
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery(); 
        // echo $debug_query;exit;
        $this->contactUs_id = $this->conn->lastInsertId();
        $stmt->closeCursor();
        return true;
    }

    // function update() {

    //     $data = [
    //         'contactUs_id'       => $this->contactUs_id,
    //         'user_request_name'     => $this->user_request_name,
    //         'email_id'              => $this->email_id,
    //         'company_name'          => $this->company_name,
    //         'mobile_no'             => $this->mobile_no,
    //         'father_name'           => $this->father_name,
    //         'address'               => $this->address,
    //         'pincode'               => $this->pincode,
    //         'is_active'             => $this->is_active,
    //         'updated_by'            => $this->updated_by,
    //     ];
    //     $sql = "UPDATE ".$this->table_name." SET user_request_name=:user_request_name, email_id=:email_id, company_name=:company_name, mobile_no=:mobile_no, father_name=:father_name, address=:address, pincode=:pincode, is_active=:is_active, updated_by=:updated_by WHERE contactUs_id=:contactUs_id";
    //     $stmt= $this->conn->prepare($sql);
    //     $stmt->execute($data);
    //     // print_r($stmt);exit;
    //     $stmt->closeCursor();
    //     return true;
    // }

    function delete() {
        $data = [
            'contactUs_id'   => $this->contactUs_id,
            'is_active'         => 2,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET is_active=:is_active, updated_by=:updated_by WHERE contactUs_id=:contactUs_id";
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
            $query = "SELECT u_qst.contactUs_id, u_qst.contact_name, u_qst.email_id, 
            u_qst.contactMessage, u_qst.mobile_no,
            u.name as rqst_by FROM ".$this->table_name." u_qst
            INNER JOIN user u ON (u.user_id=u_qst.created_by)
            WHERE u_qst.is_active < :is_active"; 
            if (isset($Request->search->value) && $Request->search->value != '' ) {
                $data['search_value'] = '%'.$Request->search->value.'%';
                $query .= " AND (contactUs_id LIKE :search_value";
                $query .= " OR name LIKE :search_value";
                $query .= " OR email_id LIKE :search_value";
                $query .= " OR mobile_no LIKE :search_value)";
            } 
            if($this->contactUs_id>0) {
                $data['contactUs_id'] = $this->contactUs_id;
                $query .= " AND contactUs_id = :contactUs_id";
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
            if($this->contactUs_id>0) {
                $data = [
                    'contactUs_id'   => $this->contactUs_id
                ];
                $query = "SELECT * FROM ".$this->table_name." WHERE u_qst.contactUs_id  :contactUs_id";
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
                    'contactUs_id'          => $row['contactUs_id'],
                    'contact_name'          => $row['contact_name'],
                    'email_id'              => $row['email_id'],
                    'contactMessage'        => $row['contactMessage'],
                    'mobile_no'             => $row['mobile_no'],
                    'rqst_by'               => $row['rqst_by']
                ];
            }
        }
        return $output;
    }

    // Check User name
    
    // function check() {
    //     $data = [
    //         'user_request_name' => $this->user_request_name,
    //         'is_active' => 1
    //     ];
    //     $stmt = $this->conn->prepare("SELECT contactUs_id FROM ".$this->table_name." WHERE user_request_name = :user_request_name 
    //             AND is_active = :is_active");
    //     $stmt->execute($data);
    //     $count = $stmt->rowCount();
    //     // $last_query = $stmt->queryString;
    //     // $debug_query = $stmt->_debugQuery(); 
    //     // echo $debug_query;exit;
    //     $row = $stmt->fetch();
    //     $stmt->closeCursor();
    //     if($count>0) {
    //         $this->contactUs_id = $row['contactUs_id'];
    //         return true;
    //     } else 
    //         return false;
            
    // }
    // Check User Email Id
    // function checkEmail() {
    //     $data = [
    //         'email_id'          => $this->email_id,
    //         'is_active' => 1
    //     ];
    //     $stmt = $this->conn->prepare("SELECT contactUs_id FROM ".$this->table_name." WHERE email_id = :email_id 
    //             AND is_active = :is_active");
    //     $stmt->execute($data);
    //     $count = $stmt->rowCount();
    //     // $last_query = $stmt->queryString;
    //     // $debug_query = $stmt->_debugQuery(); 
    //     // echo $debug_query;exit;
    //     $row = $stmt->fetch();
    //     $stmt->closeCursor();
    //     if($count>0) {
    //         $this->contactUs_id = $row['contactUs_id'];
    //         return true;
    //     } else 
    //         return false;
    // }

    function get_total_count(){
        $stmt = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE is_active < 2");
        $stmt->execute();
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $count;
    }
}
