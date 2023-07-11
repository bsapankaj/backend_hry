<?php require_once '../config/DBConnection.php';
	
	class Task_type {
    public $task_id, $task_type, $table_name, $sort_code, $sort_order, $task_description, $is_active, $created_by,$created_on, $updated_by,$updated_on, $db, $conn;

    function __construct(){
        $this->task_id = "";
        $this->task_type = "";
        $this->sort_code = "";
        $this->task_description = "";
        $this->sort_order = 0;
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'task_type';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert(){
        $data = [
            'task_type'        => $this->task_type,
            'sort_code'        => $this->sort_code,
            'sort_order'       => $this->sort_order,
            'task_description' => $this->task_description,
            'is_active'        => $this->is_active,
            'created_by'       => $this->created_by
        ];
        $sql = "INSERT INTO ".$this->table_name." (task_type, sort_code, sort_order, task_description,  is_active, created_by) VALUES (:task_type, :sort_code, :sort_order, :task_description, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function update(){
        $data = [
            'task_id'          => $this->task_id,
            'task_type'        => $this->task_type,
            'sort_code'        => $this->sort_code,
            'sort_order'       => $this->sort_order,
            'task_description' => $this->task_description,
            'is_active'        => $this->is_active,
            'updated_by'       => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET task_type=:task_type, sort_code=:sort_code, task_description=:task_description, sort_order=:sort_order, is_active=:is_active, updated_by=:updated_by WHERE task_id=:task_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function delete(){
        $data = [
            'task_id'           => $this->task_id,
            'is_active'         => 2,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET is_active=:is_active, updated_by=:updated_by WHERE task_id=:task_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        $stmt->closeCursor();
        return $last_query;
    }

    function get($Request = []){
        $output = [];
        $data = [
            'is_active'  => 2
        ];
        if(!empty($Request)){
            $query = "SELECT task_id, task_type, cause_task, sort_code, sort_order, task_description FROM ".$this->table_name." WHERE is_active < :is_active";
            if (isset($Request->search->value)) {
                $data['search_value'] = '%'.$Request->search->value.'%';
                $query .= " AND (task_id LIKE :search_value";
                $query .= " OR task_type LIKE :search_value";
                $query .= " OR sort_code LIKE :search_value";
                $query .= " OR sort_order LIKE :search_value)";
            } 
            if($this->task_id>0) {
                $data['task_id'] = $this->task_id;
                $query .= " AND task_id = :task_id";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY ".$Request->order['0']->column." ".$Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY task_type asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start. ', ' . $Request->length;
            }
        }else{
            if($this->task_id>0) {
                $data = [
                    'task_id'   => $this->task_id
                ];
                $query = "SELECT * FROM ".$this->table_name." WHERE task_id  :task_id";
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
                    'task_id'            => $row['task_id'],
                    'task_type'          => $row['task_type'],
                    'cause_task'         => $row['cause_task'],
                    'sort_code'          => $row['sort_code'],
                    'sort_order'         => $row['sort_order'],
                    'task_description'   => $row['task_description']
                ];
            }
        }
        return $output;
    }

    function check() {
        $data = [
            'task_type'       => $this->task_type,
            'is_active'       => 1
        ];
        $query = "SELECT task_id FROM ".$this->table_name." WHERE task_type=:task_type && is_active=:is_active";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if($count>0) {
            $this->task_id = $row['task_id'];
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
