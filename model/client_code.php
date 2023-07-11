<?php require_once '../config/DBConnection.php';

class Client_code {
    public $client_code_id, $client_code,$clerkage,$clerkage_type, $client_name,$table_name, $client_address, $state_id, $gst_no, $gst_on_bill, $contact_person_name, $contact_person_mobile_no, $contact_person_email_id, $sort_order, $is_active, $created_by, $updated_by, $db, $conn;

    function __construct(){
        $this->client_code_id = "";
        $this->client_code = "";
        $this->client_name = "";
        $this->client_address = "";
        $this->state_id = "";
        $this->gst_no = "";
        $this->clerkage = 0;
        $this->clerkage_type = "";
        $this->gst_on_bill = "";
        $this->contact_person_name = "";
        $this->contact_person_mobile_no = 0;
        $this->contact_person_email_id = '';
        $this->sort_order = 0;
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'client_code';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert(){
        $data = [
            'client_code'               => $this->client_code,
            'client_name'               => $this->client_name,
            'client_address'            => $this->client_address,
            'state_id'                  => $this->state_id,
            'gst_no'                    => $this->gst_no,
            'gst_on_bill'               => $this->gst_on_bill,
            'contact_person_name'       => $this->contact_person_name,
            'contact_person_mobile_no'  => $this->contact_person_mobile_no,
            'contact_person_email_id'   => $this->contact_person_email_id,
            'sort_order'                => $this->sort_order,
            'clerkage'                  => $this->clerkage,
            'clerkage_type'             => $this->clerkage_type,
            'is_active'                 => $this->is_active,
            'created_by'                => $this->created_by
        ];
        $sql = "INSERT INTO ".$this->table_name." (client_code, client_name, client_address, state_id, gst_no, gst_on_bill, contact_person_name, contact_person_mobile_no, contact_person_email_id, sort_order, is_active, created_by,clerkage,clerkage_type) VALUES (:client_code, :client_name, :client_address, :state_id, :gst_no, :gst_on_bill, :contact_person_name, :contact_person_mobile_no, :contact_person_email_id, :sort_order, :is_active, :created_by,:clerkage,:clerkage_type)";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
        //$stmt = $this->db->prepare($sql)->execute($data);
    }

    function update() {
        $data = [
            'client_code'               => $this->client_code,
            'client_name'               => $this->client_name,
            'client_address'            => $this->client_address,
            'state_id'                  => $this->state_id,
            'gst_no'                    => $this->gst_no,
            'gst_on_bill'               => $this->gst_on_bill,
            'contact_person_name'       => $this->contact_person_name,
            'contact_person_mobile_no'  => $this->contact_person_mobile_no,
            'contact_person_email_id'   => $this->contact_person_email_id,
            'sort_order'                => $this->sort_order,
            'clerkage'                  => $this->clerkage,
            'clerkage_type'             => $this->clerkage_type,
            'is_active'                 => $this->is_active,
            'updated_by'                => $this->updated_by,
            'client_code_id'            => $this->client_code_id
        ];
        $sql = "UPDATE ".$this->table_name." SET client_code=:client_code, client_name=:client_name, client_address=:client_address, state_id=:state_id, gst_no=:gst_no, gst_on_bill=:gst_on_bill, contact_person_name=:contact_person_name, contact_person_mobile_no=:contact_person_mobile_no,  contact_person_email_id=:contact_person_email_id, sort_order=:sort_order,clerkage=:clerkage,clerkage_type=:clerkage_type, is_active=:is_active, updated_by=:updated_by WHERE client_code_id=:client_code_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query;exit;
        return true;
    }

    function delete() {
        $data = [
            'client_code_id'    => $this->client_code_id,
            'is_active'         => 2,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET is_active=:is_active, updated_by=:updated_by WHERE client_code_id=:client_code_id";
        //$sql = "DELETE FROM ".$this->table_name." WHERE client_code_id = :client_code_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        return $last_query;
    }

    function get($Request) {
        $output = [];
        $data = [
            'is_active' => 2
        ];
        if(!empty($Request)){
            $query = "SELECT client_code,client_name,client_address, state_id, gst_no,gst_on_bill,contact_person_name,contact_person_mobile_no,contact_person_email_id,client_code_id,clerkage,clerkage_type FROM ".$this->table_name." WHERE is_active < :is_active";  
            if (isset($Request->search->value) && $Request->search->value != '') {
                $data['search_value'] = '%'.$Request->search->value.'%';
                $query .= " AND (client_code LIKE :search_value";
                $query .= " OR client_name LIKE :search_value";
                $query .= " OR client_address LIKE :search_value";
                $query .= " OR contact_person_name LIKE :search_value";
                $query .= " OR contact_person_mobile_no	 LIKE :search_value";
                $query .= " OR contact_person_email_id	 LIKE :search_value)";
            } 
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY ".$Request->order['0']->column." ".$Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY client_name asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start. ', ' . $Request->length;
            }
        }else{
            $query = "SELECT * FROM ".$this->table_name." WHERE is_active < :is_active";    
        }
        // print_r($data);exit;
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // print_r($debug_query);exit;
        $stmt->closeCursor();
        if($count>0) {
            foreach($results as $row) {
                $output[] = [
                    'client_code_id'            => $row['client_code_id'],
                    'client_code'               => $row['client_code'],
                    'client_name'               => $row['client_name'],
                    'client_address'            => $row['client_address'],
                    'state_id'                  => $row['state_id'],
                    'gst_no'                    => $row['gst_no'],
                    'clerkage'                  => $row['clerkage'],
                    'clerkage_type'             => $row['clerkage_type'],
                    'gst_on_bill'               => $row['gst_on_bill'],
                    'contact_person_name'       => $row['contact_person_name'],
                    'contact_person_mobile_no'  => $row['contact_person_mobile_no'],
                    'contact_person_email_id'   => $row['contact_person_email_id']
                ];
            }
        }
        return $output;
    }

    // Fetch single Record
    function getSingleRecord(){
        $output = [];
        $data = [
            'client_code_id' => $this->client_code_id,
            'is_active' => 1
        ];
        $stmt = $this->conn->prepare("SELECT * FROM ".$this->table_name." WHERE client_code_id=:client_code_id AND is_active=:is_active");
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        if($count>0) {
            foreach($results as $row) {
                $output[] = [
                    'client_code_id'            => $row['client_code_id'],
                    'client_code'               => $row['client_code'],
                    'client_name'               => $row['client_name'],
                    'client_address'            => $row['client_address'],
                    'state_id'                  => $row['state_id'],
                    'gst_no'                    => $row['gst_no'],
                    'clerkage'                  => $row['clerkage'],
                    'clerkage_type'             => $row['clerkage_type'],
                    'gst_on_bill'               => $row['gst_on_bill'],
                    'contact_person_name'       => $row['contact_person_name'],
                    'contact_person_mobile_no'  => $row['contact_person_mobile_no'],
                    'contact_person_email_id'   => $row['contact_person_email_id']
                ];
            }
        }
        return $output;
    }

    function check() {

        $data = [
            'client_code'   => $this->client_code,
            'is_active'     => 1
        ];
        $stmt = $this->conn->prepare("SELECT client_code_id FROM ".$this->table_name." WHERE client_code = :client_code AND is_active=:is_active");
        $stmt->execute($data);
        $count = $stmt->rowCount();
        if($count>0) {
            $row = $stmt->fetch();
            $this->client_code_id = $row['client_code_id'];
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