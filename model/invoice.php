<?php require_once '../config/DBConnection.php';

class Invoice{
    public $invoice_id,$client_id,$case_lawyer_id,$file_id,$int_courier_charges,$table_name,$start_date,$end_date,$clerkage,$photocopy_charges,$int_courire_charges,$description,$is_active,$created_by,$created_on,$updated_by,$updated_on, $total, $other_charge, $grand_total, $bill_no, $bill_date,$db,$conn;

    function __construct(){
        $this->invoice_id = 0;
        $this->client_id = "";
        $this->file_id = "";
        $this->start_date = '';
        $this->end_date = '';
        $this->clerkage = 0.00;
        $this->bill_no = '';
        $this->bill_date = NULL;
        $this->total = 0;
        $this->grand_total = 0;
        $this->photocopy_charges = 0.00;
        $this->int_courier_charges = 0.00;
        $this->other_charge = 0;
        $this->description = "";
        $this->is_active = 0;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->created_on = 0;
        $this->updated_on = 0;
        $this->table_name = 'invoice_master';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert(){
        $data = [
            'client_id'                  => $this->client_id,
            'file_id'                    => $this->file_id,
            'start_date'                 => date("Y-m-d",strtotime($this->start_date)),
            'end_date'                   => date("Y-m-d",strtotime($this->end_date)),
            'total'                     => $this->total,
            'bill_no'                   => $this->bill_no,
            'bill_date'                 => $this->bill_date,
            'clerkage'                   => $this->clerkage,
            'photocopy_charges'         => $this->photocopy_charges,
            'int_courier_charges'       => $this->int_courier_charges,
            'other_charge'              => $this->other_charge,
            'grand_total'               => $this->grand_total,
            'created_by'                => $this->created_by
        ];

        $sql = "INSERT INTO ".$this->table_name." (client_id, file_id, start_date, end_date, total, bill_no, bill_date, clerkage, photocopy_charges, int_courier_charges, other_charge, grand_total, created_by) VALUES (:client_id,  :file_id, :start_date, :end_date, :total, :bill_no, :bill_date, :clerkage, :photocopy_charges, :int_courier_charges, :other_charge, :grand_total, :created_by)";

        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    function check() {
        $data = [
            'file_id'       => $this->file_id,
            'start_date'    => date("Y-m-d",strtotime($this->start_date)),
            'end_date'      => date("Y-m-d",strtotime($this->end_date)),
            'is_active'     => 1
        ];
        $stmt = $this->conn->prepare("SELECT invoice_id FROM ".$this->table_name." WHERE file_id = :file_id AND (( end_date >= :start_date AND end_date <= :end_date) OR (start_date >= :start_date AND start_date <= :end_date)) AND is_active=:is_active");

        $stmt->execute($data);
        $count = $stmt->rowCount();
        

        if($count>0) {
            $row = $stmt->fetch();
            $this->invoice_id = $row['invoice_id'];
            return true;
        } else 
            return false;
    }

    function get() {
        $data = [
            'is_active'     => 1
        ];
        
        $query = "SELECT im.*, cm.case_no,fm.file_no, fm.file_title,cm.client_code_id, cc.client_code, cc.client_name, cc.client_address, cm.case_detail,cc.clerkage_type as case_clerkage, cc.clerkage as clerkage_rate, cc.gst_no,cc.gst_on_bill  
        FROM ".$this->table_name." im 
        INNER JOIN file_master fm ON (im.file_id=fm.file_id AND fm.is_active=1) 
        INNER JOIN client_code cc ON (cc.client_code_id=im.client_id AND cc.is_active=1) 
        LEFT JOIN case_master cm ON (fm.file_id=cm.file_id AND cm.is_active=1) 
        WHERE im.is_active=:is_active";
        if($this->invoice_id>0) {
            $data['invoice_id'] = $this->invoice_id;
            $query .= ' AND invoice_id=:invoice_id';
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query;exit;
        $stmt->closeCursor();
        $output = [];
        if($count>0) {
            foreach($results as $row) {
                $output[] = [
                    'invoice_id'            => $row['invoice_id'],
                    'file_id'               => $row['file_id'],
                    'file_title'               => $row['file_title'],
                    'gst_no'                => $row['gst_no'],
                    'gst_on_bill'           => $row['gst_on_bill'],
                    'client_address'        => $row['client_address'],
                    'case_detail'           => $row['case_detail'],
                    'case_no'               => $row['case_no'],
                    'file_no'               => $row['file_no'],
                    'client_id'             => $row['client_id'],
                    'client_code'           => $row['client_code'],
                    'client_name'           => $row['client_name'],
                    'start_date'            => date("d-m-Y",strtotime($row['start_date'])),
                    'end_date'              => date("d-m-Y",strtotime($row['end_date'])),
                    'total'                 => $row['total'],
                    'bill_no'               => $row['bill_no'],
                    'bill_date'             => date("d-m-Y",strtotime($row['bill_date'])),
                    'case_clerkage'         => $row['case_clerkage'],
                    'clerkage_rate'         => $row['clerkage_rate'],
                    'clerkage'              => $row['clerkage'],
                    'photocopy_charges'     => $row['photocopy_charges'],
                    'int_courier_charges'   => $row['int_courier_charges'],
                    'other_charge'          => $row['other_charge'],
                    'grand_total'           => $row['grand_total'],
                    'is_final'              => $row['is_final']              
                ];
            }
        }
        return $output;
    }

    function update(){
        $data = [
            'invoice_id'                => $this->invoice_id,
            'total'                     => $this->total,
            'bill_no'                   => $this->bill_no,
            'bill_date'                 => $this->bill_date,
            'clerkage'                  => $this->clerkage,
            'photocopy_charges'         => $this->photocopy_charges,
            'int_courier_charges'       => $this->int_courier_charges,
            'other_charge'              => $this->other_charges,
            'grand_total'               => $this->grand_total,
            'updated_by'                => $this->updated_by

        ];
        $sql = "UPDATE ".$this->table_name." SET total = :total, bill_no = :bill_no, bill_date = :bill_date, clerkage = :clerkage, photocopy_charges = :photocopy_charges, int_courier_charges=:int_courier_charges, other_charge = :other_charge, grand_total=:grand_total, updated_by = :updated_by WHERE invoice_id = :invoice_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    function max_id(){
        $sql = "SELECT max(invoice_id) as id from ".$this->table_name;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $last_query = $stmt->queryString;
        $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        return $result[0]['id'];
    }
    function final_invoice(){
        $data = [
            'invoice_id'    => $this->invoice_id,
            'is_final'      => 1
        ];
        $sql= 'UPDATE invoice_master SET is_final=:is_final WHERE invoice_id=:invoice_id';
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        return true;
    }
}

?>