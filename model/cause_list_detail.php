<?php require_once '../config/DBConnection.php';

class Cause_list_detail
{
    public $table_name, $cause_list_id, $cause_list_detail_id, $to_time, $from_time, $user_id, $is_active, $created_by, $updated_by, $db, $conn;

    function __construct()
    {
        $this->cause_list_detail_id = 0;
        $this->cause_list_id = 0;
        $this->user_id = 0;
        $this->to_time = date('Y-m-d h:i', strtotime("0000-00-00 00:00"));
        $this->from_time = date('Y-m-d h:i', strtotime("0000-00-00 00:00"));
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'cause_list_detail';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert_lawyers()
    {
        $data = [
            'cause_list_id'         => $this->cause_list_id,
            'user_id'               => $this->user_id,
            'is_active'             => $this->is_active,
            'created_by'            => $this->created_by
        ];
        $sql = "INSERT INTO " . $this->table_name . " (cause_list_id, user_id, is_active, created_by) VALUES (:cause_list_id, :user_id, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    function update_period()
    {
        $data = [
            'cause_list_detail_id'      => $this->cause_list_detail_id,
            'to_time'                   => $this->to_time,
            'from_time'                 => $this->from_time,
            'updated_by'                => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET to_time=:to_time, from_time=:from_time, updated_by=:updated_by WHERE cause_list_detail_id=:cause_list_detail_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    function delete()
    {
        $data = [
            'cause_list_detail_id'      => $this->cause_list_detail_id,
            'is_active'                 => 2,
            'updated_by'                => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active=:is_active, updated_by=:updated_by WHERE cause_list_detail_id=:cause_list_detail_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        return $last_query;
    }

    function get($Request)
    {
        $output = [];
        $data = [
            'is_active' => 2
        ];
        if (!empty($Request)) {
            $query = "SELECT cld.cause_list_detail_id, cld.cause_list_id,cld.user_id, u.name, cld.to_time, cld.from_time, cld.created_by, FROM " . $this->table_name . " cld 
            INNER JOIN users u ON cld.user_id = u.user_id and co.is_active = 1
            WHERE cld.is_active < :is_active";
            if (isset($Request->search->value) && $Request->search->value != '') {
                $data['search_value'] = '%' . $Request->search->value . '%';
                $query .= " AND (court_name LIKE :search_value";
                $query .= " OR u.name LIKE :search_value";
                $query .= " OR cld.to_time LIKE :search_value";
                $query .= " OR cld.from_time, LIKE :search_value)";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY " . $Request->order['0']->column . " " . $Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY name asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start . ', ' . $Request->length;
            }
        } else {
            $query = "SELECT * FROM " . $this->table_name . " WHERE is_active < :is_active";
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
                    'cause_list_detail_id'  => $row['cause_list_id'],
                    'cause_list_id'         => $row['cause_list_id'],
                    'user_id'               => $row['user_id'],
                    'name'                  => $row['name'],
                    'to_time'               => $row['to_time'],
                    'from_time'             => $row['from_time']
                ];
            }
        }
        return $output;
    }

    function getSingleRecord()
    {
        $output = [];
        $data = [
            'cause_list_detail_id'  => $this->cause_list_detail_id,
            'is_active'             => 1
        ];
        $stmt = $this->conn->prepare("SELECT cld.cause_list_detail_id, cld.cause_list_id,cld.user_id, u.name, cld.to_time, cld.from_time, cld.created_by, FROM " . $this->table_name . " cld 
        INNER JOIN users u ON cld.user_id = u.user_id and co.is_active = 1
        WHERE cause_list_detail_id =:cause_list_detail_id AND cld.is_active < :is_active");
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'cause_list_detail_id'  => $row['cause_list_id'],
                    'cause_list_id'         => $row['cause_list_id'],
                    'user_id'               => $row['user_id'],
                    'name'                  => $row['name'],
                    'to_time'               => $row['to_time'],
                    'from_time'             => $row['from_time']
                ];
            }
        }
        return $output;
    }

    function get_lawyers_by_cause_list_id()
    {
        $output = [];
        $data = [
            'cause_list_id'     => $this->cause_list_id,
            'is_active'         => 1
        ];
        $stmt = $this->conn->prepare("SELECT cld.cause_list_detail_id, cld.cause_list_id,cld.user_id, u.name, cld.to_time, cld.from_time, cld.created_by FROM " . $this->table_name . " cld 
        INNER JOIN user u ON cld.user_id = u.user_id and u.is_active = 1
        WHERE cause_list_id =:cause_list_id AND cld.is_active = :is_active");
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'cause_list_detail_id'  => $row['cause_list_detail_id'],
                    'cause_list_id'         => $row['cause_list_id'],
                    'user_id'               => $row['user_id'],
                    'name'                  => $row['name'],
                    'to_time'               => date('H:i', strtotime($row['to_time'])),
                    'from_time'             => date('H:i', strtotime($row['from_time']))
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

    function last_insert_id()
    {
        $stmt = $this->conn->prepare("SELECT LAST_INSERT_ID() as last_id FROM " . $this->table_name);
        $stmt->execute();
        $result = $stmt->fetch();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $result['last_id'];
    }

    function detective_lawyers_by_cause_list_id()
    {
        $data = [
            'cause_list_id' => $this->cause_list_id,
            'is_active'     => 2,
            'updated_by'    => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active=:is_active, updated_by=:updated_by WHERE cause_list_id=:cause_list_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    function check_exist_lawyer_on_casue()
    {
        $data = [
            'cause_list_id'         => $this->cause_list_id,
            'user_id'               => $this->user_id,
            'is_active'             => 2
        ];
        $sql = "SELECT * FROM " . $this->table_name . " WHERE cause_list_id=:cause_list_id AND user_id=:user_id AND is_active=:is_active";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $count = $stmt->rowCount();
        $result = $stmt->fetch();
        if ($count > 0) {
            return $result['cause_list_detail_id'];
        } else {
            return 0;
        }
    }

    function active_lawyers_by_cause_list_detail_id()
    {
        $data = [
            'cause_list_detail_id' => $this->cause_list_detail_id,
            'is_active'     => 1,
            'updated_by'    => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active=:is_active, updated_by=:updated_by WHERE cause_list_detail_id=:cause_list_detail_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    function get_cause_list_by_time($Obj)
    {
        $output = [];
        if (!empty($Obj)) {
            $data = [
                'is_active'     => 1,
                'file_id'       => $Obj->file_id,
                'start_date'    => date('Y-m-d', strtotime($Obj->start_date)),
                'end_date'      => date('Y-m-d', strtotime($Obj->end_date))
            ];
            $query = "select cld.from_time,cl.cause_list_id,cld.cause_list_detail_id,cld.to_time,cld.user_id,DATE_FORMAT(cl.cause_date, '%Y-%m-%d') as cause_date,cc.client_code,cm.case_id,tt.task_type,lf.fee,lf.type as fee_type,ct.court_name
            from cause_list_detail cld
            inner join cause_list cl on (cl.cause_list_id = cld.cause_list_id and cl.is_active =1)
            inner join user u on (u.user_id = cld.user_id and u.is_active =1)
            inner join court ct on (ct.court_id = cl.court_id and ct.is_active =1)
            inner join task_type tt on (tt.task_id = cl.activity_type and cl.is_active =1)
            inner join case_master cm on (cm.case_id = cl.case_id and cm.is_active =1)
            inner join lawyer_fee lf on (lf.user_id = cld.user_id and lf.task_id = cl.activity_type and lf.case_id = cl.case_id and lf.is_active =1)  
            inner join client_code cc on (cc.client_code_id = cl.client_code_id and cc.is_active =1)
            where cl.file_id = :file_id and (DATE(cause_date) >= :start_date AND DATE(cause_date) <= :end_date) and cld.is_active=:is_active";
            $stmt = $this->conn->prepare($query);
            $stmt->execute($data);
            $results = $stmt->fetchAll();
            $count = $stmt->rowCount();
            $stmt->closeCursor();
            if ($count > 0) {
                foreach ($results as $row) {
                    $output[] = [
                        'cause_list_detail_id'  =>  $row['cause_list_detail_id'],
                        'cause_list_id'         =>  $row['cause_list_id'],
                        'from_time'             =>  $row['from_time'],
                        'to_time'               =>  $row['to_time'],
                        'user_id'               =>  $row['user_id'],
                        'cause_date'            =>  $row['cause_date'],
                        'client_code'           =>  $row['client_code'],
                        'case_id'               =>  $row['case_id'],
                        'task_type'             =>  $row['task_type'],
                        'court_name'            =>  $row['court_name'],
                        'fee'                   =>  $row['fee'],
                        'fee_type'              =>  $row['fee_type']
                    ];
                }
            }
        }
        return $output;
    }

    function get_pending($Request)
    {
        $output = [];
        $query = "SELECT u.name,file_no,ts.time_sheet_id, short_title, cm.case_no,ts.description as timesheet_description, tt.task_type,cr.court_name, cl.cause_desc, cl.activity_type, cl.cause_date, cl.justice,cl.item_no, cl.court_no,cl.cause_list_id 
        FROM " . $this->table_name . " cld
        INNER JOIN cause_list cl ON (cl.cause_list_id = cld.cause_list_id AND cl.is_active =1)
        INNER JOIN file_master fl ON (fl.file_id = cl.file_id AND cl.is_active =1)
        INNER JOIN task_type tt ON (tt.task_id = cl.activity_type AND tt.is_active =1)
        INNER JOIN court cr ON (cr.court_id = cl.court_id AND cr.is_active =1)
        INNER JOIN user u ON (u.user_id = cld.user_id AND u.is_active = 1)
        LEFT JOIN case_master cm ON (cm.file_id = fl.file_id AND cm.is_active = 1)
        LEFT JOIN time_sheet ts ON (cld.cause_list_id = ts.cause_list_id AND ts.user_id = cld.user_id AND ts.is_active = 1)
        WHERE cld.is_active = 1";

        if (isset($Request->pending_type)) {

            //billing status
            if ($Request->pending_type == 1) {
                $query .= " AND time_sheet_id IS NULL";
            } else if ($Request->pending_type == 2) {
                $query .= " AND time_sheet_id IS NOT NULL";
            }

            // lawyers
            if ($Request->user_id > 0) {
                $query .= " AND u.user_id = '$Request->user_id'";
            }

            // from date
            if (isset($Request->from_date) && !empty($Request->from_date)) {
                $query .= " AND DATE(cl.cause_date) >= '" . date('Y-m-d', strtotime($Request->from_date)) . "'";
            }

            // to date
            if (isset($Request->to_date) && !empty($Request->to_date)) {
                $query .= " AND DATE(cl.cause_date) <= '" . date('Y-m-d', strtotime($Request->to_date)) . "'";
            }
        } else if (isset($Request->date_format)) {
            $query .= " AND DATE(cl.cause_date) = '" . date('Y-m-d', strtotime($Request->date)) . "' ORDER BY court_name";
        }
        // echo $query;exit;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'cause_list_id'         => $row['cause_list_id'],
                    'court_no'              => $row['court_no'],
                    'item_no'               => $row['item_no'],
                    'name'                  => $row['name'],
                    'justice'               => $row['justice'],
                    'file_no'               => $row['file_no'],
                    'task_type'             => $row['task_type'],
                    'case_no'               => $row['case_no'],
                    'short_title'           => $row['short_title'],
                    'timesheet_description' => $row['timesheet_description'],
                    'court_name'            => $row['court_name'],
                    'cause_desc'            => $row['cause_desc'],
                    'activity_type'         => $row['activity_type'],
                    'cause_date'            => date('d-m-Y', strtotime($row['cause_date']))
                ];
            }
        }
        return $output;
    }

    function fetch_cause_for_time_sheet()
    {   $output = [];
        if (isset($this->time_sheet_id) && $this->time_sheet_id >0) {
            $data = [
                "file_id"           => $this->file_id,
                "user_id"           => $this->user_id,
                "is_active"         => $this->is_active,
                "time_sheet_id"     => $this->time_sheet_id
            ];
            $query = "SELECT cl.cause_list_id,cl.cause_date,cl.court_no,cl.file_id,cld.cause_list_detail_id,cl.item_no,ts.time_sheet_id,cl.file_id
            FROM " . $this->table_name . " cld
            INNER JOIN cause_list cl ON (cld.cause_list_id = cl.cause_list_id AND cld.is_active = 1)
            LEFT JOIN time_sheet ts ON (ts.cause_list_id = cl.cause_list_id AND ts.user_id = cld.user_id AND ts.time_sheet_id!=:time_sheet_id  AND ts.is_active =1)
            WHERE cl.is_active=:is_active AND cl.file_id=:file_id AND cld.user_id=:user_id AND time_sheet_id IS NULL";
        } else {
            $data = [
                "file_id"   => $this->file_id,
                "user_id"   => $this->user_id,
                "is_active" => $this->is_active
            ];
            $query = "SELECT cl.cause_list_id,cl.cause_date,cl.court_no,cl.file_id,cld.cause_list_detail_id,cl.item_no,ts.time_sheet_id,cl.file_id
            FROM " . $this->table_name . " cld
            INNER JOIN cause_list cl ON (cld.cause_list_id = cl.cause_list_id AND cld.is_active = 1)
            LEFT JOIN time_sheet ts ON (ts.cause_list_id = cl.cause_list_id AND ts.user_id = cld.user_id AND ts.is_active =1)
            WHERE cl.is_active=:is_active AND cl.file_id=:file_id AND cld.user_id=:user_id AND time_sheet_id IS NULL";
        }
        
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // echo $stmt->_debugQuery();
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'cause_list_id'         => $row['cause_list_id'],
                    'court_no'              => $row['court_no'],
                    'item_no'               => $row['item_no'],
                    'cause_date'            => date('d-m-Y', strtotime($row['cause_date']))
                ];
            }
        }
        return $output;
    }
}
