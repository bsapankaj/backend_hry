<?php require_once '../config/DBConnection.php';

class Cause_list
{
    public $table_name, $cause_list_id, $file_id,$court_no, $cause_desc, $court_id, $item_no, $justice, $case_id, $client_code_id, $short_title, $cause_date, $activity_type, $remarks, $is_active, $created_by, $updated_by, $db, $conn;

    function __construct()
    {
        $this->cause_list_id = 0;
        $this->court_no = 0;
        $this->court_id = 0;
        $this->item_no = "";
        $this->justice = 0;
        $this->file_id = 0;
        $this->cause_desc = '';
        $this->client_code_id = 0;
        $this->case_id = 0;
        $this->short_title = "";
        $this->cause_date = date('Y-m-d h:i', strtotime("0000-00-00 00:00"));
        $this->activity_type = "";
        $this->remarks = "";
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'cause_list';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert()
    {   
        $data = [
            'court_no'          => $this->court_no,
            'court_id'          => $this->court_id,
            'item_no'           => $this->item_no,
            'justice'           => $this->justice,
            'file_id'           => $this->file_id,
            'client_code_id'    => $this->client_code_id,
            'case_id'           => $this->case_id,
            'cause_desc'        => $this->cause_desc,
            'short_title'       => $this->short_title,
            'cause_date'        => $this->cause_date,
            'activity_type'     => $this->activity_type,
            'is_active'         => $this->is_active,
            'created_by'        => $this->created_by
        ];
        $sql = "INSERT INTO " . $this->table_name . " (court_no, court_id, item_no, justice, case_id, client_code_id, file_id, short_title, cause_desc,cause_date, activity_type, is_active,created_by) VALUES (:court_no, :court_id, :item_no, :justice, :case_id, :client_code_id, :file_id,:short_title, :cause_desc,:cause_date, :activity_type, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    function update()
    {
        $data = [
            'court_no'          => $this->court_no,
            'cause_list_id'     => $this->cause_list_id,
            'file_id'           => $this->file_id,     
            'court_id'          => $this->court_id,
            'item_no'           => $this->item_no,
            'justice'           => $this->justice,
            'client_code_id'    => $this->client_code_id,
            'case_id'           => $this->case_id,
            'short_title'       => $this->short_title,
            'cause_desc'        => $this->cause_desc,
            'cause_date'        => $this->cause_date,
            'activity_type'     => $this->activity_type,
            'is_active'         => $this->is_active,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET court_no=:court_no, court_id=:court_id, item_no=:item_no, justice=:justice, case_id=:case_id, file_id=:file_id ,client_code_id=:client_code_id, short_title=:short_title, cause_desc=:cause_desc, cause_date=:cause_date, activity_type=:activity_type, is_active=:is_active, updated_by=:updated_by WHERE cause_list_id=:cause_list_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    function delete()
    {
        $data = [
            'cause_list_id'     => $this->cause_list_id,
            'is_active'         => 2,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active=:is_active, updated_by=:updated_by WHERE cause_list_id=:cause_list_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    function get($Request = [])
    {
        $output = [];
        $data = [
            'is_active' => 2
        ];
        if (!empty($Request)) {
            $query = "SELECT cl.cause_list_id, cl.court_no, cl.court_id, cl.remarks, cl.cause_desc,cl.item_no, cl.client_code_id,activity_type,cl.file_id,f.file_no, cause_date,cl.justice, cl.case_id, cl.short_title,co.court_name,cm.case_detail,cc.client_code,cm.case_no FROM " . $this->table_name . " cl 
            INNER JOIN court co ON (co.court_id = cl.court_id and co.is_active = 1)
            INNER JOIN file_master f ON (f.file_id=cl.file_id AND f.is_active = 1) 
            INNER JOIN client_code cc ON (cc.client_code_id = cl.client_code_id and cc.is_active = 1)
            LEFT JOIN case_master cm ON (cl.case_id = cm.case_id and cm.is_active = 1)
            WHERE cl.is_active < :is_active";
            if (isset($Request->search->value) && $Request->search->value != '') {
                $data['search_value'] = '%' . $Request->search->value . '%';
                $query .= " AND (court_name LIKE :search_value";
                $query .= " OR cl.court_no LIKE :search_value";
                $query .= " OR cl.item_no LIKE :search_value";
                $query .= " OR cl.short_title LIKE :search_value";
                $query .= " OR j.justice_name LIKE :search_value";
                $query .= " OR co.court_name LIKE :search_value)";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY " . $Request->order['0']->column . " " . $Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY court_no asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start . ', ' . $Request->length;
            }
        } else {
            if (isset($this->file_id) && $this->file_id>0) {
                $data = [
                    'file_id'   => $this->file_id,
                    'is_active' => 2
                ];
                $query = "SELECT cl.cause_list_id, cl.court_no, cl.court_id, cl.remarks,cl.cause_desc,cl.item_no, cl.client_code_id,activity_type,cl.file_id,f.file_no, cause_date,cl.justice, cl.case_id, cl.short_title,co.court_name,cm.case_detail,cc.client_code,cm.case_no FROM " . $this->table_name . " cl 
                INNER JOIN court co ON (co.court_id = cl.court_id and co.is_active = 1)
                INNER JOIN file_master f ON (f.file_id=cl.file_id AND f.is_active = 1) 
                INNER JOIN client_code cc ON (cc.client_code_id = cl.client_code_id and cc.is_active = 1)
                LEFT JOIN case_master cm ON (cl.case_id = cm.case_id and cm.is_active = 1)
                WHERE cl.file_id=:file_id AND cl.is_active < :is_active";
            } else {
                $query = "SELECT cl.cause_list_id, cl.court_no, cl.court_id, cl.remarks,cl.item_no,cl.cause_desc, cl.client_code_id,activity_type,cl.file_id,f.file_no, cause_date,cl.justice, cl.case_id, cl.short_title,co.court_name,cm.case_detail,cc.client_code,cm.case_no FROM " . $this->table_name . " cl 
                INNER JOIN court co ON (co.court_id = cl.court_id and co.is_active = 1)
                INNER JOIN file_master f ON (f.file_id=cl.file_id AND f.is_active = 1) 
                INNER JOIN client_code cc ON (cc.client_code_id = cl.client_code_id and cc.is_active = 1)
                LEFT JOIN case_master cm ON (cl.case_id = cm.case_id and cm.is_active = 1)
                WHERE cl.is_active < :is_active";
            }
            
            
        }
        // print_r($data);exit;
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'cause_list_id'         => $row['cause_list_id'],
                    'court_id'              => $row['court_id'],
                    'court_no'              => $row['court_no'],
                    'court_name'            => $row['court_name'],
                    'client_code'           => $row['client_code'],
                    'item_no'               => $row['item_no'],
                    'case_no'               => $row['case_no'],
                    'justice'               => $row['justice'],
                    'client_code_id'        => $row['client_code_id'],
                    'file_id'               => $row['file_id'],
                    'cause_desc'               => $row['cause_desc'],
                    'file_no'               => $row['file_no'],
                    'client_code'           => $row['client_code'],
                    'case_id'               => $row['case_id'],
                    'short_title'           => $row['short_title'],
                    'case_detail'           => $row['case_detail'],
                    'remarks'               => $row['remarks'],
                    'activity_type'         => $row['activity_type'],
                    'cause_date'            => date('Y-m-d', strtotime($row['cause_date']))
                ];
            }
        }
        return $output;
    }

    function getSingleRecord()
    {
        $output = [];
        $data = [
            'cause_list_id'     => $this->cause_list_id,
            'is_active'         => 1
        ];
        $stmt = $this->conn->prepare("SELECT cl.cause_list_id, cl.remarks, cl.court_no,cl.cause_desc, cl.court_id, cl.item_no, cl.client_code_id,cl.file_id,f.file_no,activity_type, cause_date,cl.justice, cl.case_id, cl.short_title,co.court_name,cm.case_detail,cc.client_code,cm.case_no FROM " . $this->table_name . " cl 
        INNER JOIN court co ON (co.court_id = cl.court_id and co.is_active = 1)
        INNER JOIN file_master f ON (f.file_id=cl.file_id AND f.is_active = 1) 
        INNER JOIN client_code cc ON (cc.client_code_id = cl.client_code_id and cc.is_active = 1)
        LEFT JOIN case_master cm ON (cl.case_id = cm.case_id and cm.is_active = 1)
        WHERE cause_list_id=:cause_list_id and cl.is_active = :is_active");
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'cause_list_id'         => $row['cause_list_id'],
                    'court_id'              => $row['court_id'],
                    'court_no'              => $row['court_no'],
                    'court_name'            => $row['court_name'],
                    'client_code'           => $row['client_code'],
                    'item_no'               => $row['item_no'],
                    'case_no'               => $row['case_no'],
                    'justice'               => $row['justice'],
                    'client_code_id'        => $row['client_code_id'],
                    'file_id'               => $row['file_id'],
                    'cause_desc'            => $row['cause_desc'],
                    'file_no'               => $row['file_no'],
                    'client_code'           => $row['client_code'],
                    'case_id'               => $row['case_id'],
                    'short_title'           => $row['short_title'],
                    'case_detail'           => $row['case_detail'],
                    'remarks'               => $row['remarks'],
                    'activity_type'         => $row['activity_type'],
                    'cause_date'            => date('d-m-Y', strtotime($row['cause_date']))
                ];
            }
        }
        return $output;
    }

    function get_total_count()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE is_active < 2");
        $stmt->execute();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $count;
    }

    function get_justice($Request)
    {
        $output = [];
        $data = [
            'is_active' => 2
        ];
        if (!empty($Request) && isset($Request->text)) {
            $query = 'select distinct justice from ' . $this->table_name . ' where is_active <> :is_active and justice like "%' . $Request->text . '%"';
        } else {
            $query = "select distinct justice from " . $this->table_name . " where is_active <> :is_active";
        }
        // echo $query;exit;
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        // $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'justice'      => $row['justice']
                ];
            }
        }
        return $output;
    }

    function last_insert_id(){
        $stmt = $this->conn->prepare("SELECT LAST_INSERT_ID() as last_id FROM " . $this->table_name);
        $stmt->execute();
        $result = $stmt->fetch();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $result['last_id'];
    }

    function upadte_remarks()
    {
        $data = [
            'cause_list_id'    => $this->cause_list_id,
            'remarks'          => $this->remarks,
            'updated_by'       => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET remarks=:remarks, updated_by=:updated_by WHERE cause_list_id=:cause_list_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

}
