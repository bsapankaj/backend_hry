<?php require_once '../config/DBConnection.php';

class Justice
{
    public $table_name, $justice_id, $justice_name, $is_active, $created_by, $updated_by, $db, $conn;

    function __construct()
    {
        $this->justice_id = 0;
        $this->justice_name = "";
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'justice';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert()
    {
        $data = [
            'justice_name'    => $this->justice_name,
            'is_active'     => $this->is_active,
            'created_by'    => $this->created_by
        ];
        $sql = "INSERT INTO " . $this->table_name . " (justice_name, is_active, created_by) VALUES (:justice_name, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    function update()
    {
        $data = [
            'justice_id'      => $this->justice_id,
            'justice_name'    => $this->justice_name,
            'is_active'     => $this->is_active,
            'updated_by'    => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET justice_name=:justice_name, is_active=:is_active, updated_by=:updated_by WHERE justice_id=:justice_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }

    function delete()
    {
        $data = [
            'justice_id'      => $this->justice_id,
            'is_active'     => 2,
            'updated_by'    => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active=:is_active, updated_by=:updated_by WHERE justice_id=:justice_id";
        $stmt = $this->conn->prepare($sql);
        if($stmt->execute($data)){
            return true;
        } else {
            return false;
        }
    }

    function get($Request = [])
    {
        $output = [];
        $data = [
            'is_active' => 1
        ];
        if (!empty($Request)) {
            $query = "SELECT justice_id,justice_name FROM " . $this->table_name . " c 
            WHERE is_active = :is_active";
            if (isset($Request->search->value) && $Request->search->value != '') {
                $data['search_value'] = '%' . $Request->search->value . '%';
                $query .= " AND (justice_name LIKE :search_value)";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY " . $Request->order['0']->column . " " . $Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY justice_name asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start . ', ' . $Request->length;
            }
        } else {
            $query = "SELECT * FROM " . $this->table_name . " WHERE is_active <> :is_active";
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'justice_id'      => $row['justice_id'],
                    'justice_name'    => $row['justice_name']
                ];
            }
        }
        return $output;
    }
    
    function getSingleRecord()
    {
        $output = [];
        $data = [
            'justice_id' => $this->justice_id,
            'is_active' => 1
        ];
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE justice_id=:justice_id AND is_active=:is_active");
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'justice_id'      => $row['justice_id'],
                    'justice_name'    => $row['justice_name']
                ];
            }
        }
        return $output;
    }

    function check()
    {

        $data = [
            'justice_id'     => $this->justice_id,
            'justice_name'   => $this->justice_name,
            'is_active'     => 1
        ];

        $stmt = $this->conn->prepare("SELECT justice_id FROM " . $this->table_name . " WHERE justice_name = :justice_name AND justice_id = :justice_id AND is_active=:is_active");
        $stmt->execute($data);
        $count = $stmt->rowCount();
        if ($count > 0) {
            $row = $stmt->fetch();
            $this->justice_id = $row['justice_id'];
            return true;
        } else {
            return false;
        }
    }

    function get_total_count()
    {
        $stmt = $this->conn->prepare("SELECT * FROM " . $this->table_name . " WHERE is_active < 2");
        $stmt->execute();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $count;
    }
}
