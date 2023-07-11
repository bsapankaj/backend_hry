<?php require_once '../config/DBConnection.php';
class lawyer_fee
{
    public $lawyer_fee_id, $case_id, $user_id, $task, $type, $fee, $is_active, $created_by, $created_on, $updated_by, $updated_on, $conn, $table_name, $db, $task_id;
    function __construct()
    {
        $this->lawyer_fee_id = 0;
        $this->case_id = 0;
        $this->user_id = 0;
        $this->task_id = 0;
        $this->type = "";
        $this->fee = 0.00;
        $this->is_active = 0;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->created_on = NULL;
        $this->updated_on = NULL;
        $this->table_name = 'lawyer_fee';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }
    function check($Request = [])
    {
        $output = [];
        $data = [
            'is_active' => 1,
            'case_id'   => $this->case_id,
            'user_id'   => $this->user_id,
            'task_id'   => $this->task_id
        ];

        $query = "SELECT tt.task_type FROM lawyer_fee lf INNER JOIN task_type tt ON (lf.task_id=tt.task_id) WHERE lf.user_id =:user_id AND lf.task_id =:task_id ANd lf.case_id=:case_id AND lf.is_active =:is_active LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // print_r($debug_query);exit;
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();

        if ($count > 0) {
            return $output = [
                'status' => false,
                'msg' => $results[0]['task_type']
            ];
        } else {
            return $output = [
                'status' => true,
                'msg' => 'You can add this task fee'
            ];
        }
    }
    function get($Request = [])
    {
        $output = [];
        $data = [
            'is_active' => 1
        ];
        if (!empty($Request)) {
            $query = "SELECT lf.lawyer_fee_id,lf.case_id, lf.user_id,lf.fee,lf.type,lf.task_id,tt.task_type,task_description, cm.case_no,u.name FROM " . $this->table_name . " lf INNER JOIN task_type tt ON (lf.task_id=tt.task_id) INNER JOIN case_master cm ON (lf.case_id=cm.case_id) INNER JOIN user u ON (lf.user_id=u.user_id) WHERE u.user_type_id=1 AND lf.is_active = :is_active";
            if ($this->lawyer_fee_id > 0) {
                $data['lawyer_fee_id'] = $this->lawyer_fee_id;
                $query .= " AND lf.lawyer_fee_id = :lawyer_fee_id";
            }
            if ($this->case_id > 0 && $this->user_id) {
                $data['case_id'] = $this->case_id;
                $data['user_id'] = $this->user_id;
                $query .= " AND lf.case_id=:case_id AND lf.user_id=:user_id";
            }
        } else {
            if ($this->lawyer_fee_id > 0) {
                $data = [
                    'lawyer_fee_id'   => $this->lawyer_fee_id
                ];
                $query = "SELECT lf.lawyer_fee_id,lf.case_id, lf.user_id,lf.fee,lf.type,lf.task_id,tt.task_type,task_description, cm.case_no,u.name FROM " . $this->table_name . " lf INNER JOIN task_type tt ON (lf.task_id=tt.task_id) INNER JOIN case_master cm ON (lf.case_id=cm.case_id) INNER JOIN user u ON (lf.user_id=u.user_id) WHERE u.user_type_id=1 AND lf.is_active = :is_active AND lf.lawyer_fee_id=:lawyer_fee_id";
            } else {
                $query = "SELECT lf.lawyer_fee_id,lf.case_id, lf.user_id,lf.fee,lf.type,lf.task_id,tt.task_type,task_description, cm.case_no,u.name FROM " . $this->table_name . " lf INNER JOIN task_type tt ON (lf.task_id=tt.task_id) INNER JOIN case_master cm ON (lf.case_id=cm.case_id) INNER JOIN user u ON (lf.user_id=u.user_id) WHERE u.user_type_id=1 AND lf.is_active = :is_active";
            }
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $last_query = $stmt->queryString;
        $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'lawyer_fee_id'     => $row['lawyer_fee_id'],
                    'case_id'           => $row['case_id'],
                    'user_id'           => $row['user_id'],
                    'case_no'           => $row['case_no'],
                    'name'              => $row['name'],
                    'fee'               => $row['fee'],
                    'type'              => $row['type'],
                    'task_id'           => $row['task_id'],
                    'task_type'         => $row['task_type'],
                    'task_description'  => $row['task_description']
                ];
            }
        }
        return $output;
    }
    function insert()
    {
        $data = [
            'case_id'                  => $this->case_id,
            'user_id'                  => $this->user_id,
            'task_id'                  => $this->task_id,
            'type'                     => $this->type,
            'fee'                      => $this->fee,
            'created_by'               => $this->created_by,
        ];
        $sql = "INSERT INTO " . $this->table_name . " (case_id, user_id, task_id, type, fee, created_by) VALUES (:case_id, :user_id, :task_id, :type, :fee, :created_by)";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        $debug_query = $stmt->_debugQuery();
        // print_r($debug_query);exit;
        return true;
    }
    function update()
    {
        $data = [
            'lawyer_fee_id'      => $this->lawyer_fee_id,
            'case_id'            => $this->case_id,
            'user_id'            => $this->user_id,
            'task_id'            => $this->task_id,
            'type'               => $this->type,
            'fee'                => $this->fee,
            'is_active'          => 1,
            'updated_by'         => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET case_id=:case_id, user_id=:user_id, task_id=:task_id, type=:type, fee=:fee, is_active=:is_active, updated_by=:updated_by WHERE lawyer_fee_id=:lawyer_fee_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $debug_query = $stmt->_debugQuery();
        // echo $debug_query; exit;
        $stmt->closeCursor();
        return true;
    }
    function delete()
    {
        $data = [
            'lawyer_fee_id'     => $this->lawyer_fee_id,
            'is_active'         => 2,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active=:is_active, updated_by=:updated_by WHERE lawyer_fee_id=:lawyer_fee_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        $stmt->closeCursor();
        return $last_query;
    }
    function fetch_by_case_id($Request = [])
    {
        $output = [];
        $data = [
            'is_active' => 1,
            'case_id' => $this->case_id
        ];
        $query = "SELECT lf.lawyer_fee_id,lf.case_id, lf.user_id,lf.fee,lf.type,lf.task_id,tt.task_type, cm.case_no,u.name FROM " . $this->table_name . " lf INNER JOIN task_type tt ON (lf.task_id=tt.task_id) INNER JOIN case_master cm ON (lf.case_id=cm.case_id) INNER JOIN user u ON (lf.user_id=u.user_id) WHERE u.user_type_id=1 AND lf.is_active = :is_active AND lf.case_id =:case_id GROUP BY u.user_id ORDER BY lf.task_id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $last_query = $stmt->queryString;
        $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'user_id'           => $row['user_id'],
                    'name'              => $row['name'],
                    'task_id'           => $row['task_id'],
                    'task_type'         => $row['task_type']
                ];
            }
        }
        return $output;
    }
    function fetch_by_lawyer_id($Request = [])
    {
        $output = [];
        $data = [
            'is_active' => 1,
            'user_id' => $this->user_id
        ];
        $query = "SELECT lf.lawyer_fee_id, lf.user_id, lf.case_id,lf.fee,lf.type,lf.task_id,tt.task_type, cm.case_no,u.name FROM " . $this->table_name . " lf INNER JOIN task_type tt ON (lf.task_id=tt.task_id) INNER JOIN case_master cm ON (lf.case_id=cm.case_id) INNER JOIN user u ON (lf.user_id=u.user_id) WHERE u.user_type_id=1 AND lf.is_active = :is_active AND lf.user_id =:user_id GROUP BY cm.case_no";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $last_query = $stmt->queryString;
        $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'task_id'  => $row['task_id'],
                    'case_no'  => $row['case_no'],
                    'case_id'  => $row['case_id']
                ];
            }
        }
        return $output;
    }
    function fetch_by_case_id_and_user_id()
    {
        $output = [];
        $data = [
            'is_active' => 1,
            'case_id' => $this->case_id,
            'user_id' => $this->user_id
        ];
        $query = "SELECT lf.lawyer_fee_id,lf.case_id, lf.user_id,lf.fee,lf.type,lf.task_id,tt.task_type, cm.case_no,u.name FROM " . $this->table_name . " lf INNER JOIN task_type tt ON (lf.task_id=tt.task_id) INNER JOIN case_master cm ON (lf.case_id=cm.case_id) INNER JOIN user u ON (lf.user_id=u.user_id) WHERE u.user_type_id=1 AND lf.is_active = :is_active AND lf.case_id =:case_id GROUP BY u.user_id ORDER BY lf.task_id";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $last_query = $stmt->queryString;
        $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'user_id'           => $row['user_id'],
                    'name'              => $row['name'],
                    'task_id'           => $row['task_id'],
                    'task_type'         => $row['task_type']
                ];
            }
        }
        return $output;
    }
    function validation_lawyer_fee()
    {
        $output = [];
        $data = [
            'is_active' => 1,
            'case_id'   => $this->case_id,
            'user_id'   => $this->user_id,
            'task_id'   => $this->task_id
        ];

        $query = "SELECT tt.task_type FROM lawyer_fee lf INNER JOIN task_type tt ON (lf.task_id=tt.task_id) WHERE lf.user_id =:user_id AND lf.task_id =:task_id ANd lf.case_id=:case_id AND lf.is_active =:is_active LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // print_r($debug_query);exit;
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();

        if ($count > 0) {
           return $output = [
                'status' => true,
                'msg' => 'You can add this lawyer'
            ];
        } else {
            return $output = [
                'status' => false,
                'msg' => "You can't add this lawyer"
            ];
        }
    }
}
