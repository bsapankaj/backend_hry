<?php require_once '../config/DBConnection.php';

class Time_sheet
{
    public     $time_sheet_id, $user_id, $file_id, $cause_list_id, $table_name, $start_time, $end_time, $total_time, $case_id, $task_id, $billable, $description, $is_active, $created_by, $fee_id, $created_on, $updated_by, $updated_on, $db, $conn, $invoice_id, $lawyer_id, $amount;
    function __construct()
    {
        $this->time_sheet_id = 0;
        $this->user_id = "";
        $this->start_time = NULL;
        $this->end_time = NULL;
        $this->total_time = "";
        $this->case_id = NULL;
        $this->task_id = "";
        $this->description = NULL;
        $this->invoice_id = 0;
        $this->is_active = 1;
        $this->amount = 0;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'time_sheet';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert()
    {
        $data = [
            'user_id'            => $this->created_by,
            'file_id'            => $this->file_id,
            'cause_list_id'      => $this->cause_list_id,
            'start_time'         => $this->start_time,
            'end_time'           => $this->end_time,
            'total_time'         => $this->total_time,
            'case_id'            => $this->case_id,
            'amount'             => $this->amount,
            'task_id'            => $this->task_id,
            'description'        => $this->description,
            'fee_id'             => $this->fee_id,
            'billable'           => $this->billable,
            'is_active'          => $this->is_active,
            'created_by'         => $this->created_by
        ];
        $sql = "INSERT INTO " . $this->table_name . " (user_id, start_time, file_id, cause_list_id, end_time, case_id,task_id, total_time, amount, description,fee_id, billable, is_active, created_by) VALUES (:user_id, :start_time, :file_id, :cause_list_id, :end_time,:case_id, :task_id, :total_time, :amount, :description,:fee_id, :billable, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // return $debug_query;
        $stmt->closeCursor();
        return true;
    }

    function update()
    {
        $data = [
            'time_sheet_id'      => $this->time_sheet_id,
            'start_time'         => $this->start_time,
            'end_time'           => $this->end_time,
            'file_id'            => $this->file_id,
            'cause_list_id'      => $this->cause_list_id,
            'total_time'         => $this->total_time,
            'case_id'            => $this->case_id,
            'amount'             => $this->amount,
            'task_id'            => $this->task_id,
            'description'        => $this->description,
            'fee_id'              => $this->fee_id,
            'billable'           => $this->billable,
            'is_active'          => $this->is_active,
            'updated_by'         => $this->updated_by

        ];
        $sql = "UPDATE " . $this->table_name . " SET start_time=:start_time,end_time=:end_time, file_id=:file_id, cause_list_id=:cause_list_id, total_time=:total_time,case_id=:case_id, amount=:amount,task_id=:task_id,description=:description,fee_id=:fee_id,billable=:billable,is_active=:is_active, updated_by=:updated_by WHERE time_sheet_id=:time_sheet_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        return true;
    }

    function delete()
    {
        $data = [
            'time_sheet_id'     => $this->time_sheet_id,
            'is_active'         => 2,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active=:is_active, updated_by=:updated_by WHERE time_sheet_id=:time_sheet_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        return $last_query;
    }

    function get($Request = [])
    {
        @session_start();
        $login_id = $_SESSION["hryS_user_id"];
        $output = [];
        $data = [
            'is_active'     => 2,
            'invoice_id'    => 0,
            'user_id'       => $login_id
        ];
        $Request = (object)$Request;
        if (!empty($Request)) {
            $query = "SELECT ts.time_sheet_id, ts.start_time, fm.particulars,cc.client_name, ts.end_time, ts.amount, ts.total_time, ts.case_id, ts.task_id, ts.total_time, ts.description,ts.fee_id,ts.billable, cm.case_no, tt.task_type, f.file_no,ts.file_id,cc.client_code,fm.unit,f.client_code_id,ts.cause_list_id
            FROM " . $this->table_name . " ts 
            INNER JOIN file_master f ON (ts.file_id=f.file_id AND f.is_active=1) 
            INNER JOIN task_type tt ON (tt.task_id=ts.task_id AND tt.is_active=1) 
            INNER JOIN client_code cc ON (cc.client_code_id=f.client_code_id AND tt.is_active=1) 
            LEFT JOIN fee_master fm ON (fm.fee_master_id =ts.fee_id AND fm.is_active=1) 
            LEFT JOIN  case_master cm ON (ts.case_id=cm.case_id AND cm.is_active=1) 
            LEFT JOIN  cause_list cl ON (ts.cause_list_id=cl.cause_list_id AND cl.is_active=1) 
            WHERE (ts.is_active < :is_active AND ts.user_id = :user_id AND invoice_id=:invoice_id)";
            if (isset($Request->search->value) && !empty($Request->search->value)) {
                $data['search_value'] = '%' . $Request->search->value . '%';
                $query .= " AND (cm.case_no LIKE :search_value)";
            }
            if ($this->time_sheet_id > 0) {
                $data['time_sheet_id'] = $this->time_sheet_id;
                $query .= " AND time_sheet_id = :time_sheet_id";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY " . $Request->order['0']->column . " " . $Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY ts.start_time asc';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start . ',' . $Request->length;
            }
        } else {
            if ($this->time_sheet_id > 0) {
                $data = [
                    'time_sheet_id'   => $this->time_sheet_id,
                    'invoice_id'    => 0
                ];
                $query = "SELECT ts.time_sheet_id, ts.start_time, ts.end_time, ts.amount, ts.total_time, ts.case_id, ts.task_id, ts.total_time, ts.description,ts.fee_id, ts.billable, cm.case_no, tt.task_type, f.file_no,ts.file_id,cc.client_code,cc.unit,cc.client_code_id,ts.cause_list_id
                FROM " . $this->table_name . " ts 
                INNER JOIN file_master f ON (ts.file_id=f.file_id AND f.is_active=1)
                INNER JOIN task_type tt ON (tt.task_id=ts.task_id AND tt.is_active=1) 
                INNER JOIN client_code cc ON (cc.client_code_id=f.client_code_id AND tt.is_active=1) 
                LEFT JOIN fee_master fm ON (fm.fee_master_id =ts.fee_id AND fm.is_active=1)
                LEFT JOIN case_master cm ON (ts.case_id=cm.case_id AND cm.is_active=1) 
                LEFT JOIN cause_list cl ON (ts.cause_list_id=cm.cause_list_id AND cl.is_active=1) 
                WHERE (ts.is_active < :is_active AND ts.user_id = :user_id AND invoice_id=:invoice_id)";
            } else {
                $query = "SELECT ts.time_sheet_id, ts.start_time, ts.end_time, ts.amount, ts.total_time, ts.case_id, ts.task_id, ts.total_time, ts.description,ts.fee_id, ts.billable, cm.case_no, tt.task_type, f.file_no,ts.file_id,cc.client_code,cc.unit,cc.client_code_id,ts.cause_list_id
                FROM " . $this->table_name . " ts 
                INNER JOIN file_master f ON (ts.file_id=f.file_id AND f.is_active=1)
                INNER JOIN task_type tt ON (tt.task_id=ts.task_id AND tt.is_active=1) 
                INNER JOIN client_code cc ON (cc.client_code_id=f.client_code_id AND tt.is_active=1) 
                LEFT JOIN fee_master fm ON (fm.fee_master_id =ts.fee_id AND fm.is_active=1)
                LEFT JOIN case_master cm ON (ts.case_id=cm.case_id AND cm.is_active=1) 
                LEFT JOIN cause_list cl ON (ts.cause_list_id=cm.cause_list_id AND cl.is_active=1) 
                WHERE (ts.is_active < :is_active AND ts.user_id = :user_id AND invoice_id=:invoice_id)";
            }
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // echo $stmt->_debugQuery();exit;
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'time_sheet_id'     => $row['time_sheet_id'],
                    'start_time'        => $row['start_time'],
                    'file_id'           => $row['file_id'],
                    'particulars'       => $row['particulars'],
                    'client_name'       => $row['client_name'],
                    'file_no'           => $row['file_no'],
                    'unit'              => $row['unit'],
                    'cause_list_id'     => $row['cause_list_id'],
                    'end_time'          => $row['end_time'],
                    'case_no'           => $row['case_no'],
                    'task_type'         => $row['task_type'],
                    'case_id'           => $row['case_id'],
                    'task_id'           => $row['task_id'],
                    'total_time'        => $row['total_time'],
                    'amount'            => $row['amount'],
                    'description'       => $row['description'],
                    'fee_id'            => $row['fee_id'],
                    'billable'          => $row['billable']
                ];
            }
        }
        return $output;
    }

    function check()
    {
        $data = [
            'time_sheet_id'    => $this->time_sheet_id,
            'is_active'        => 1
        ];
        $stmt = $this->conn->prepare("SELECT time_sheet_id FROM " . $this->table_name . " WHERE time_sheet_id=:time_sheet_id AND is_active=:is_active");
        $stmt->execute($data);
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if ($count > 0) {
            $this->time_sheet_id = $row['time_sheet_id'];
            return true;
        } else
            return false;
    }

    function fetch($invoice_id = 0)
    {
        if ($invoice_id) {
            $data = [
                'file_id'       => $this->file_id,
                'is_active'     => 1,
                'billable'     => 'Y',
                'start_time'    => date("Y-m-d", strtotime($this->start_time)),
                'end_time'      => date("Y-m-d", strtotime($this->end_time))
            ];
            $query = "SELECT ts.time_sheet_id, ts.start_time, ts.end_time, ts.total_time, ts.case_id, ts.task_id, ts.total_time, ts.description,
            f.file_no, f.file_id,ts.billable, f.client_code_id, cm.case_no, tt.task_type, cm.case_detail, cc.clerkage, cc.clerkage_type, cm.case_vs_from, 
            cm.case_vs_to,fm.fee, fm.unit,fm.particulars, cm.client_code_id, cc.client_code 
            FROM " . $this->table_name . " ts 
            INNER JOIN file_master f ON (ts.file_id=f.file_id AND f.is_active=1)
            INNER JOIN task_type tt ON (tt.task_id=ts.task_id AND tt.is_active=1) 
            INNER JOIN client_code cc ON (cc.client_code_id=f.client_code_id AND tt.is_active=1) 
            LEFT JOIN fee_master fm ON (fm.fee_master_id =ts.fee_id AND fm.is_active=1)
            LEFT JOIN case_master cm ON (ts.case_id=cm.case_id AND cm.is_active=1) 
            LEFT JOIN cause_list cl ON (ts.cause_list_id=cl.cause_list_id AND cl.is_active=1) 
            WHERE ts.billable = :billable AND ts.is_active = :is_active AND ts.file_id = :file_id AND DATE(start_time) >= :start_time AND DATE(end_time) <= :end_time ORDER BY start_time ASC";
        } else {
            $data = [
                'file_id'       => $this->file_id,
                'is_active'     => 1,
                'billable'     => 'Y',
                'start_time'    => date("Y-m-d", strtotime($this->start_time)),
                'end_time'      => date("Y-m-d", strtotime($this->end_time)),
                'invoice_id'    => 0
            ];
            $query = "SELECT ts.time_sheet_id, ts.start_time, ts.end_time, ts.total_time, ts.case_id, ts.task_id, ts.total_time, f.file_no, f.file_id,
            ts.description, ts.billable, cm.case_no, fm.particulars, tt.task_type, cm.case_detail, cc.clerkage, cc.clerkage_type, cm.case_vs_from, cm.case_vs_to, fm.fee, 
            fm.unit, f.client_code_id, cc.client_code 
            FROM " . $this->table_name . " ts 
            INNER JOIN file_master f ON (ts.file_id=f.file_id AND f.is_active=1)
            INNER JOIN task_type tt ON (tt.task_id=ts.task_id AND tt.is_active=1) 
            INNER JOIN client_code cc ON (cc.client_code_id=f.client_code_id AND tt.is_active=1) 
            LEFT JOIN fee_master fm ON (fm.fee_master_id =ts.fee_id AND fm.is_active=1)
            LEFT JOIN case_master cm ON (ts.case_id=cm.case_id AND cm.is_active=1) 
            LEFT JOIN cause_list cl ON (ts.cause_list_id=cl.cause_list_id AND cl.is_active=1) 
            WHERE ts.billable = :billable AND ts.is_active = :is_active AND ts.file_id = :file_id AND DATE(start_time) >= :start_time AND DATE(end_time) <= :end_time AND invoice_id=:invoice_id ORDER BY start_time ASC";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query; exit;
        $stmt->closeCursor();
        $output = [];
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'client_code_id'    => $row['client_code_id'],
                    'file_no'           => $row['file_no'],
                    'file_id'           => $row['file_id'],
                    'client_code'       => $row['client_code'],
                    'time_sheet_id'     => $row['time_sheet_id'],
                    'start_time'        => $row['start_time'],
                    'fee'               => $row['fee'],
                    'unit'              => $row['unit'],
                    'particulars'       => $row['particulars'],
                    'end_time'          => $row['end_time'],
                    'case_no'           => $row['case_no'],
                    'task_type'         => $row['task_type'],
                    'case_id'           => $row['case_id'],
                    'task_id'           => $row['task_id'],
                    'total_time'        => $row['total_time'],
                    'case_detail'       => $row['case_detail'],
                    'clerkage'          => $row['clerkage'],
                    'clerkage_type'     => $row['clerkage_type'],
                    'description'       => $row['description'],
                    'case_vs_to'        => $row['case_vs_to'],
                    'case_vs_from'      => $row['case_vs_from'],
                    'per_hour_fee'      => $row['fee']
                ];
            }
        }
        return $output;
    }

    function get_total_count()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE is_active < 2 AND invoice_id=0");
        $stmt->execute();
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $count;
    }

    function update_invoice_no()
    {
        $data = [
            'invoice_id'    => $this->invoice_id,
            'start_time'    => $this->start_time,
            'end_time'      => $this->end_time,
            'file_id'       => $this->file_id,
            'updated_by'    => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET invoice_id=:invoice_id, updated_by=:updated_by WHERE file_id=:file_id AND DATE(start_time) >= :start_time AND DATE(end_time) <= :end_time AND is_active =1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // print_r($debug_query);exit;
        $stmt->closeCursor();
        return true;
    }

    function fetch_activity($user_id = 0)
    {
        $output = [];
        if ($user_id > 0) {
            $data = [
                'start_time'    => date("Y-m-d", strtotime($this->start_time)),
                'end_time'      => date("Y-m-d", strtotime($this->end_time)),
                'user_id'      => $this->user_id
            ];
            $query = "SELECT ts.time_sheet_id,lf.type, u.name, ts.start_time, ts.end_time, cc.client_code, ts.amount, ts.total_time, ts.case_id, ts.task_id, ts.total_time, ts.description, ts.billable, cm.case_no, tt.task_type, lf.task_id FROM " . $this->table_name . " ts INNER JOIN case_master cm ON (ts.case_id=cm.case_id) INNER JOIN client_code cc ON (cm.client_code_id=cc.client_code_id) INNER JOIN lawyer_fee lf ON (ts.task_id=lf.task_id) INNER JOIN user u ON (ts.user_id=u.user_id) INNER JOIN task_type tt ON (lf.task_id=tt.task_id)  WHERE (DATE(start_time) >= :start_time AND DATE(end_time) <= :end_time AND ts.user_id = :user_id) ORDER BY u.name,DATE(ts.start_time),cm.case_no";
        } else {
            $data = [
                'start_time'    => date("Y-m-d", strtotime($this->start_time)),
                'end_time'      => date("Y-m-d", strtotime($this->end_time)),
            ];
            $query = "SELECT ts.time_sheet_id,lf.type, u.name, ts.start_time, ts.end_time, cc.client_code, ts.amount, ts.total_time, ts.case_id, ts.task_id, ts.total_time, ts.description, ts.billable, cm.case_no, tt.task_type, lf.task_id FROM " . $this->table_name . " ts INNER JOIN case_master cm ON (ts.case_id=cm.case_id) INNER JOIN client_code cc ON (cm.client_code_id=cc.client_code_id) INNER JOIN user u ON (ts.user_id=u.user_id) INNER JOIN lawyer_fee lf ON (ts.task_id=lf.task_id) INNER JOIN task_type tt ON (lf.task_id=tt.task_id)  WHERE (DATE(start_time) >= :start_time AND DATE(end_time) <= :end_time) ORDER BY u.name,DATE(ts.start_time),cm.case_no";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // print_r($debug_query);exit;
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'time_sheet_id'     => $row['time_sheet_id'],
                    'start_time'        => $row['start_time'],
                    'end_time'          => $row['end_time'],
                    'case_no'           => $row['case_no'],
                    'name'              => $row['name'],
                    'client_code'       => $row['client_code'],
                    'task_type'         => $row['task_type'],
                    'case_id'           => $row['case_id'],
                    'task_id'           => $row['task_id'],
                    'total_time'        => $row['total_time'],
                    'amount'            => $row['amount'],
                    'description'       => $row['description'],
                    'billable'          => $row['billable']
                ];
            }
        }
        // print_r($output);exit;
        return $output;
    }

    function time_sheet_mis($Request)
    {
        $output = [];
        $query = "SELECT ts.time_sheet_id, ts.start_time, u.name, fl.file_no,ts.end_time, cl.cause_list_id,cc.client_code,ts.amount, cl.cause_date, cl.item_no,ts.total_time, ts.case_id, ts.task_id, ts.total_time, ts.description, ts.billable, cm.case_no, tt.task_type  
        FROM " . $this->table_name . " ts 
        INNER JOIN file_master fl ON (ts.file_id=fl.file_id AND fl.is_active= 1) 
        INNER JOIN client_code cc ON (fl.client_code_id=cc.client_code_id AND cc.is_active=1) 
        INNER JOIN task_type tt ON (ts.task_id=tt.task_id AND tt.is_active=1)  
        INNER JOIN user u ON (ts.user_id=u.user_id AND u.is_active=1)
        LEFT JOIN fee_master fm ON (fm.fee_master_id =ts.fee_id AND fm.is_active=1)
        LEFT JOIN case_master cm ON (ts.case_id=cm.case_id AND cm.is_active=1) 
        LEFT JOIN cause_list cl ON (ts.cause_list_id=cl.cause_list_id AND cl.is_active=1)";

        // billing_type
        if ($Request->billing_type == 0) {
            $query .= "WHERE ts.is_active = 1 ";
        } else if ($Request->billing_type == 1) {
            $query .= "WHERE ts.is_active = 1 AND ts.invoice_id=0";
        } else {
            $query .= "WHERE ts.is_active = 1 AND ts.invoice_id>0";
        }

        // lawyer
        if ($Request->user_id > 0) {
            $query .= " AND u.user_id = '$Request->user_id'";
        }

        // from date
        if (isset($Request->from_date) && !empty($Request->from_date)) {
            $query .= " AND DATE(ts.start_time) >= '" . date('Y-m-d', strtotime($Request->from_date)) . "'";
        }

        // to date
        if (isset($Request->to_date) && !empty($Request->to_date)) {
            $query .= " AND DATE(ts.end_time) <= '" . date('Y-m-d', strtotime($Request->to_date)) . "'";
        }

        $query .= " ORDER BY u.name,DATE(start_time)";

        // echo $query;exit;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // print_r($debug_query);exit;
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'time_sheet_id'     => $row['time_sheet_id'],
                    'start_time'        => $row['start_time'],
                    'end_time'          => $row['end_time'],
                    'case_no'           => $row['case_no'],
                    'task_type'         => $row['task_type'],
                    'client'            => $row['client_code'],
                    'case_id'           => $row['case_id'],
                    'name'              => $row['name'],
                    'task_id'           => $row['task_id'],
                    'file_no'           => $row['file_no'],
                    'total_time'        => $row['total_time'],
                    'cause_list_id'     => $row['cause_list_id'],
                    'amount'            => $row['amount'],
                    'cause'             => date('d-m-Y', strtotime($row['cause_date'])) . ' / ' . $row['item_no'],
                    'description'       => $row['description'],
                    'billable'          => $row['billable']
                ];
            }
        }
        return $output;
    }
}
