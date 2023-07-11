<?php require_once '../config/DBConnection.php';

class Case_lawyer{
    public $case_lawyer_id, $case_id, $user_id, $per_hour_fee, $is_active, $created_by, $created_on, $updated_by, $updated_on, $db, $conn;

    function __construct(){
        $this->case_lawyer_id = 0;
        $this->case_id = 0;
        $this->user_id = 0;
        $this->per_hour_fee = 0;
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'case_lawyer';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert(){
        $data = [
            'case_id'            => $this->case_id,
            'user_id'            => $this->user_id,
            'per_hour_fee'       => $this->per_hour_fee,
            'is_active'          => $this->is_active,
            'created_by'         => $this->created_by
        ];
        $sql = "INSERT INTO ".$this->table_name." (case_id, user_id, per_hour_fee, is_active, created_by) VALUES (:case_id, :user_id, :per_hour_fee, :is_active, :created_by)";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function update() {
        $data = [
            'case_lawyer_id'     => $this->case_lawyer_id,
            'case_id'            => $this->case_id,
            'user_id'            => $this->user_id,
            'per_hour_fee'       => $this->per_hour_fee,
            'is_active'          => $this->is_active,
            'updated_by'         => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET case_id=:case_id, user_id=:user_id, per_hour_fee=:per_hour_fee, is_active=:is_active, updated_by=:updated_by WHERE case_lawyer_id=:case_lawyer_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function delete() {
        $data = [
            'case_lawyer_id'    => $this->case_lawyer_id,
            'is_active'         => 2,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET is_active=:is_active, updated_by=:updated_by WHERE case_lawyer_id=:case_lawyer_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        $stmt->closeCursor();
        return true;
    }

    function get($Request = []) {
        $output = [];
        $data = [
            'is_active' => 2
        ];
        if(!empty($Request)){
            $query = "SELECT cl.case_lawyer_id, cl.case_id, cl.user_id, cl.per_hour_fee, cm.case_no, u.name FROM ".$this->table_name." cl INNER JOIN case_master cm ON (cl.case_id=cm.case_id) INNER JOIN user u ON (cl.user_id=u.user_id) WHERE u.user_type_id=1 AND cl.is_active < :is_active";
            if (isset($Request->search->value) && trim($Request->search->value) != '') {
                $data['search_value'] = '%'.trim($Request->search->value).'%';
                $query .= " AND (cl.case_lawyer_id LIKE :search_value";
                $query .= " OR cl.case_id LIKE :search_value)";
                $query .= " OR cl.user_id LIKE :search_value";
                $query .= " OR cl.per_hour_fee LIKE :search_value";
            } 
            if($this->case_lawyer_id>0) {
                $data['case_lawyer_id'] = $this->case_lawyer_id;
                $query .= " AND cl.case_lawyer_id = :case_lawyer_id";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY ".$Request->order['0']->column." ".$Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY cl.user_id asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start. ', ' . $Request->length;
            }
        }else{
            if($this->case_lawyer_id>0) {
                $data = [
                    'case_lawyer_id'   => $this->case_lawyer_id
                ];
                $query = "SELECT * FROM ".$this->table_name." WHERE case_lawyer_id :case_lawyer_id";
            } else {
                $query = "SELECT * FROM ".$this->table_name." WHERE is_active < :is_active";
            }
                
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $last_query = $stmt->queryString;
        $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        if($count>0) {
            foreach($results as $row) {
                $output[] = [
                    'case_lawyer_id'     => $row['case_lawyer_id'],
                    'case_id'            => $row['case_id'],
                    'user_id'            => $row['user_id'],
                    'per_hour_fee'       => $row['per_hour_fee'],
                    'case_no'            => $row['case_no'],
                    'name'               => $row['name'],
                ];
            }
        }
        return $output;
    }

    function check() {
        $data = [
            'per_hour_fee'   => $this->per_hour_fee,
            'is_active'      => 1
        ];
        $stmt = $this->conn->prepare("SELECT case_lawyer_id FROM ".$this->table_name." WHERE per_hour_fee = :per_hour_fee AND is_active=:is_active");
        $stmt->execute($data);
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if($count>0) {
            $this->case_lawyer_id = $row['case_lawyer_id'];
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