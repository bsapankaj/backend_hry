<?php require_once '../config/DBConnection.php';

class Lawyer_advisory
{
    public $table_name, $lawyer_advisory_id, $date, $file_id, $client_code_id, $case_id, $task_id, $lawyer_name, $fee, $sacn_bill_copy_path, $created_by, $is_active, $updated_by, $db, $conn;

    function __construct()
    {
        $this->lawyer_advisory_id = 0;
        $this->date = date('Y-m-d', strtotime("0000-00-00"));
        $this->file_id = 0;
        $this->client_code_id = 0;
        $this->case_id = 0;
        $this->lawyer_name = "";
        $this->fee = 0;
        $this->task_id = 0;
        $this->sacn_bill_copy_path = "";
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'lawyer_advisory';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert()
    {
        $data = [
            'client_code_id'            => $this->client_code_id,
            'date'                      => $this->date,
            'case_id'                   => $this->case_id,
            'file_id'                   => $this->file_id,
            'lawyer_name'               => $this->lawyer_name,
            'fee'                       => $this->fee,
            'task_id'                   => $this->task_id,
            'sacn_bill_copy_path'       => $this->sacn_bill_copy_path,
            'is_active'                 => $this->is_active,
            'created_by'                => $this->created_by
        ];
        $sql = "INSERT INTO " . $this->table_name . " (client_code_id, date, file_id, case_id, lawyer_name, fee, task_id, sacn_bill_copy_path, is_active,created_by) VALUES (:client_code_id, :date, :file_id, :case_id, :lawyer_name, :fee, :task_id, :sacn_bill_copy_path, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function update()
    {
        $data = [
            'lawyer_advisory_id'        => $this->lawyer_advisory_id,
            'date'                      => $this->date,
            'client_code_id'            => $this->client_code_id,
            'case_id'                   => $this->case_id,
            'file_id'                   => $this->file_id,
            'lawyer_name'               => $this->lawyer_name,
            'fee'                       => $this->fee,
            'task_id'                   => $this->task_id,
            'is_active'                 => $this->is_active,
            'updated_by'                => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET  client_code_id=:client_code_id, date=:date,file_id=:file_id,case_id=:case_id, lawyer_name=:lawyer_name, fee=:fee, task_id=:task_id, is_active=:is_active, updated_by=:updated_by WHERE lawyer_advisory_id=:lawyer_advisory_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function delete()
    {
        $data = [
            'lawyer_advisory_id'    => $this->lawyer_advisory_id,
            'is_active'             => 2,
            'updated_by'            => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active=:is_active, updated_by=:updated_by WHERE lawyer_advisory_id=:lawyer_advisory_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query; exit;
        $stmt->closeCursor();
        return true;
    }

    function get($Request)
    {
        $output = [];
        $data = [
            'is_active'  => 2
        ];
        if (!empty($Request)) {
            $query = "SELECT la.lawyer_advisory_id, la.date, la.file_id,f.file_no, la.client_code_id, cc.client_code, c.case_no, la.case_id, la.lawyer_name, la.fee, la.task_id, tt.task_type, la.sacn_bill_copy_path FROM " . $this->table_name . " la
            INNER JOIN file_master f ON (f.file_id=la.file_id) 
            INNER JOIN client_code cc ON cc.client_code_id = la.client_code_id and cc.is_active = 1
            LEFT JOIN case_master c ON c.case_id = la.case_id and c.is_active = 1
            INNER JOIN task_type tt ON tt.task_id = la.task_id and tt.is_active = 1
            WHERE la.is_active < :is_active";
            if (isset($Request->search->value)) {
                $data['search_value'] = '%' . $Request->search->value . '%';
                $query .= " AND (lawyer_advisory_id LIKE :search_value";
                $query .= " OR client_code LIKE :search_value";
                $query .= " OR case_no LIKE :search_value";
                $query .= " OR task_type LIKE :search_value";
                $query .= " OR fee LIKE :search_value)";
            }
            if ($this->lawyer_advisory_id > 0) {
                $data['lawyer_advisory_id'] = $this->lawyer_advisory_id;
                $query .= " AND lawyer_advisory_id = :lawyer_advisory_id";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY " . $Request->order['0']->column . " " . $Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY la.lawyer_name asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start . ', ' . $Request->length;
            }
        } else {
            if ($this->lawyer_advisory_id > 0) {
                $data = [
                    'lawyer_advisory_id'   => $this->lawyer_advisory_id
                ];
                $query = "SELECT la.lawyer_advisory_id, la.date, la.file_id,f.file_no, la.client_code_id, cc.client_code, c.case_no, la.case_id, la.lawyer_name, la.fee, la.task_id, tt.task_type, la.sacn_bill_copy_path FROM " . $this->table_name . " la
                INNER JOIN file_master f ON (f.file_id=la.file_id) 
                INNER JOIN client_code cc ON cc.client_code_id = la.client_code_id and cc.is_active = 1  
                LEFT JOIN case_master c ON c.case_id = la.case_id and c.is_active = 1
                INNER JOIN task_type tt ON tt.task_id = la.task_id and tt.is_active = 1
                WHERE lawyer_advisory_id =:lawyer_advisory_id";
            } else {
                $query = "SELECT la.lawyer_advisory_id, la.date, la.file_id,f.file_no, la.client_code_id, cc.client_code, c.case_no, la.case_id, la.lawyer_name, la.fee, la.task_id, tt.task_type, la.sacn_bill_copy_path FROM " . $this->table_name . " la
                INNER JOIN file_master f ON (f.file_id=la.file_id) 
                INNER JOIN client_code cc ON cc.client_code_id = la.client_code_id and cc.is_active = 1  
                LEFT JOIN case_master c ON c.case_id = la.case_id and c.is_active = 1
                INNER JOIN task_type tt ON tt.task_id = la.task_id and tt.is_active = 1
                WHERE la.is_active < :is_active";
            }
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'lawyer_advisory_id'        =>  $row['lawyer_advisory_id'],
                    'date'                      =>  date('d-m-Y', strtotime($row['date'])),
                    'client_code_id'            =>  $row['client_code_id'],
                    'client_code'               =>  $row['client_code'],
                    'file_id'                   =>  $row['file_id'],
                    'file_no'                   =>  $row['file_no'],
                    'case_id'                   =>  $row['case_id'],
                    'case_no'                   =>  $row['case_no'],
                    'lawyer_name'               =>  $row['lawyer_name'],
                    'fee'                       =>  $row['fee'],
                    'task_id'                   =>  $row['task_id'],
                    'task_type'                 =>  $row['task_type'],
                    'sacn_bill_copy_path'       =>  $row['sacn_bill_copy_path']
                ];
            }
        }
        return $output;
    }

    function get_total_count()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE is_active < 2");
        $stmt->execute();
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $count;
    }

    function last_insert_id()
    {
        $stmt = $this->conn->prepare("SELECT LAST_INSERT_ID() as last_id FROM " . $this->table_name);
        $stmt->execute();
        $result = $stmt->fetch();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $result['last_id'];
    }

    function update_bill_path()
    {
        $data = [
            'lawyer_advisory_id'        => $this->lawyer_advisory_id,
            'sacn_bill_copy_path'       => $this->sacn_bill_copy_path,
            'updated_by'                => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET sacn_bill_copy_path=:sacn_bill_copy_path ,updated_by=:updated_by WHERE lawyer_advisory_id=:lawyer_advisory_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function getSingle()
    {
        $output = [];

        if ($this->lawyer_advisory_id > 0) {
            $data = [
                'lawyer_advisory_id'   => $this->lawyer_advisory_id
            ];
            $query = "SELECT la.lawyer_advisory_id, la.date, la.file_id,f.file_no, la.client_code_id, cc.client_code, c.case_no, la.case_id, la.lawyer_name, la.fee, la.task_id, tt.task_type, la.sacn_bill_copy_path FROM " . $this->table_name . " la
            INNER JOIN file_master f ON (f.file_id=la.file_id) 
            INNER JOIN client_code cc ON cc.client_code_id = la.client_code_id and cc.is_active = 1  
            LEFT JOIN case_master c ON c.case_id = la.case_id and c.is_active = 1
            INNER JOIN task_type tt ON tt.task_id = la.task_id and tt.is_active = 1
            WHERE lawyer_advisory_id =:lawyer_advisory_id";
        } else {
            $query = "SELECT la.lawyer_advisory_id, la.date, la.client_code_id, cc.client_code, c.case_no, la.case_id, la.lawyer_name, la.fee, la.task_id, tt.task_type, la.sacn_bill_copy_path FROM " . $this->table_name . " la
            INNER JOIN client_code cc ON cc.client_code_id = la.client_code_id and cc.is_active = 1  
            LEFT JOIN case_master c ON c.case_id = la.case_id and c.is_active = 1
            INNER JOIN task_type tt ON tt.task_id = la.task_id and tt.is_active = 1
            WHERE la.is_active < :is_active";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'lawyer_advisory_id'        =>  $row['lawyer_advisory_id'],
                    'date'                      =>  date('d-m-Y', strtotime($row['date'])),
                    'client_code_id'            =>  $row['client_code_id'],
                    'client_code'               =>  $row['client_code'],
                    'file_id'                   =>  $row['file_id'],
                    'file_no'                   =>  $row['file_no'],
                    'case_id'                   =>  $row['case_id'],
                    'case_no'                   =>  $row['case_no'],
                    'lawyer_name'               =>  $row['lawyer_name'],
                    'fee'                       =>  $row['fee'],
                    'task_id'                   =>  $row['task_id'],
                    'task_type'                 =>  $row['task_type'],
                    'sacn_bill_copy_path'       =>  $row['sacn_bill_copy_path']
                ];
            }
        }
        return $output;
    }

    function get_lawyer_advisory_by_time($Obj)
    {
        $output = [];
        if (!empty($Obj)) {
            if (isset($Obj->invoice_id)) {
                if ($Obj->invoice_id > 0 && $Obj->is_final > 0) {
                    $data = [
                        'is_active'     => 1,
                        'invoice_id'    => $Obj->invoice_id,
                        'file_id'       => $Obj->file_id,
                        'start_date'    => date('Y-m-d', strtotime($Obj->start_date)),
                        'end_date'      => date('Y-m-d', strtotime($Obj->end_date))
                    ];
                    $query = "SELECT la.lawyer_advisory_id, la.date, la.file_id,f.file_no, la.client_code_id, la.invoice_id, cc.client_code, c.case_no, la.case_id, la.lawyer_name, la.fee, la.task_id, tt.task_type, la.sacn_bill_copy_path 
                    FROM " . $this->table_name . " la
                    INNER JOIN file_master f ON (f.file_id=la.file_id) 
                    INNER JOIN client_code cc ON (cc.client_code_id = la.client_code_id and cc.is_active = 1)  
                    LEFT JOIN case_master c ON (c.case_id = la.case_id and c.is_active = 1)
                    INNER JOIN task_type tt ON (tt.task_id = la.task_id and tt.is_active = 1)
                    WHERE la.file_id=:file_id AND (DATE(la.date) >= :start_date AND DATE(la.date) <= :end_date) and la.invoice_id=:invoice_id and la.is_active=:is_active";
                } else {
                    $data = [
                        'is_active'     => 1,
                        'file_id'       => $Obj->file_id,
                        'start_date'    => date('Y-m-d', strtotime($Obj->start_date)),
                        'end_date'      => date('Y-m-d', strtotime($Obj->end_date))
                    ];
                    $query = "SELECT la.lawyer_advisory_id, la.date, la.file_id,f.file_no, la.client_code_id, cc.client_code, c.case_no, la.case_id, la.lawyer_name, la.fee, la.task_id, tt.task_type, la.sacn_bill_copy_path 
                    FROM " . $this->table_name . " la
                    INNER JOIN file_master f ON (f.file_id=la.file_id) 
                    INNER JOIN client_code cc ON (cc.client_code_id = la.client_code_id and cc.is_active = 1)  
                    LEFT JOIN case_master c ON (c.case_id = la.case_id and c.is_active = 1)
                    INNER JOIN task_type tt ON (tt.task_id = la.task_id and tt.is_active = 1)
                    WHERE la.file_id=:file_id AND (DATE(la.date) >= :start_date AND DATE(la.date) <= :end_date) and la.is_active=:is_active";
                }
            } else {
                $data = [
                    'is_active'     => 1,
                    'file_id'       => $Obj->file_id,
                    'start_date'    => date('Y-m-d', strtotime($Obj->start_date)),
                    'end_date'      => date('Y-m-d', strtotime($Obj->end_date))
                ];
                $query = "SELECT la.lawyer_advisory_id, la.date, la.file_id,f.file_no, la.client_code_id, cc.client_code, c.case_no, la.case_id, la.lawyer_name, la.fee, la.task_id, tt.task_type, la.sacn_bill_copy_path 
                FROM " . $this->table_name . " la
                INNER JOIN file_master f ON (f.file_id=la.file_id) 
                INNER JOIN client_code cc ON (cc.client_code_id = la.client_code_id and cc.is_active = 1)  
                LEFT JOIN case_master c ON (c.case_id = la.case_id and c.is_active = 1)
                INNER JOIN task_type tt ON (tt.task_id = la.task_id and tt.is_active = 1)
                WHERE la.file_id=:file_id AND (DATE(la.date) >= :start_date AND DATE(la.date) <= :end_date) and la.is_active=:is_active";
            }

            $stmt = $this->conn->prepare($query);
            $stmt->execute($data);
            $results = $stmt->fetchAll();
            $count = $stmt->rowCount();
            // $last_query = $stmt->queryString;
            // $debug_query = $stmt->_debugQuery();
            // echo $debug_query; exit;
            $stmt->closeCursor();
            $stmt->closeCursor();
            if ($count > 0) {
                foreach ($results as $row) {
                    $output[] = [
                        'lawyer_advisory_id'        =>  $row['lawyer_advisory_id'],
                        'date'                      =>  date('Y-m-d', strtotime($row['date'])),
                        'client_code_id'            =>  $row['client_code_id'],
                        'client_code'               =>  $row['client_code'],
                        'file_id'                   =>  $row['file_id'],
                        'file_no'                   =>  $row['file_no'],
                        'case_id'                   =>  $row['case_id'],
                        'case_no'                   =>  $row['case_no'],
                        'lawyer_name'               =>  $row['lawyer_name'],
                        'fee'                       =>  $row['fee'],
                        'task_id'                   =>  $row['task_id'],
                        'task_type'                 =>  $row['task_type'],
                        'sacn_bill_copy_path'       =>  $row['sacn_bill_copy_path']
                    ];
                }
            }
        }
        return $output;
    }

    function update_invoice_no() {
        $data = [
            'invoice_id'    => $this->invoice_id,
            'start_time'    => $this->start_time,
            'end_time'      => $this->end_time,
            'file_id'       => $this->file_id,
            'updated_by'    => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET invoice_id=:invoice_id, updated_by=:updated_by WHERE file_id=:file_id AND DATE(date) >= :start_time AND DATE(date) <= :end_time AND is_active =1;";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        return true;
    }
}
