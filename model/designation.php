<?php require_once '../config/DBConnection.php';

class user_designation
{
    public $table_name, $user_designation_id, $user_designation, $user_type_id, $sort_code, $is_active, $created_by, $created_on, $updated_by, $updated_on, $db, $conn;

    function __construct()
    {
        $this->user_designation_id = 0;
        $this->user_designation = '';
        $this->user_type_id = 0;
        $this->sort_code = 0;
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'user_designation';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert()
    {
        $data = [
            'user_designation'       => $this->user_designation,
            'user_type_id'          => $this->user_type_id,
            'sort_code'             => $this->sort_code,
            'is_active'             => $this->is_active,
            'created_by'            => $this->created_by
        ];
        $sql = "INSERT INTO " . $this->table_name . " (user_designation, user_type_id, sort_code, is_active, created_by) VALUES (:user_designation, :user_type_id, :sort_code, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function update()
    {
        $data = [
            'user_designation_id'   => $this->user_designation_id,
            'user_designation'       => $this->user_designation,
            'user_type_id'          => $this->user_type_id,
            'sort_code'             => $this->sort_code,
            'is_active'             => $this->is_active,
            'updated_by'            => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET user_designation_id=:user_designation_id, user_designation=:user_designation, user_type_id=:user_type_id,sort_code=:sort_code, is_active=:is_active, updated_by=:updated_by WHERE user_designation_id=:user_designation_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function delete()
    {
        $data = [
            'user_designation_id'   => $this->user_designation_id,
            'is_active'             => 2,
            'updated_by'            => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active=:is_active, updated_by=:updated_by WHERE user_designation_id=:user_designation_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        $stmt->closeCursor();
        return $last_query;
    }

    function get($Request)
    {
        $output = [];
        $data = [
            'is_active'  => 2
        ];
        if (!empty($Request)) {
            $query = "SELECT ud.user_designation_id,ud.user_designation,ud.user_type_id,ud.sort_code,ud.is_active,ut.user_type FROM " . $this->table_name . " ud INNER JOIN user_type ut ON (ut.user_type_id = ud.user_type_id) WHERE ud.is_active < :is_active";
            if (isset($Request->search->value)) {
                $data['search_value'] = '%' . $Request->search->value . '%';
                $query .= " AND (user_designation_id LIKE :search_value";
                $query .= " OR user_designation LIKE :search_value";
                $query .= " OR ud.sort_code LIKE :search_value";
                $query .= " OR user_type LIKE :search_value)";
            }
            if ($this->user_designation_id > 0) {
                $data['user_designation_id'] = $this->user_designation_id;
                $query .= " AND user_designation_id = :user_designation_id";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY " . $Request->order['0']->column . " " . $Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY user_designation asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start . ', ' . $Request->length;
            }
        } else {
            if ($this->user_designation_id > 0) {
                $data = [
                    'user_designation_id'   => $this->user_designation_id
                ];
                $query = "SELECT ud.user_designation_id,ud.user_designation,ud.user_type_id,ud.sort_code,ud.is_active,ut.user_type FROM " . $this->table_name . " ud INNER JOIN user_type ut ON (ut.user_type_id = ud.user_type_id) WHERE user_designation_id =:user_designation_id AND ud.is_active < :is_active ";
            } else {
                $query = "SELECT ud.user_designation_id,ud.user_designation,ud.user_type_id,ud.sort_code,ud.is_active,ut.user_type FROM " . $this->table_name . " ud INNER JOIN user_type ut ON (ut.user_type_id = ud.user_type_id) WHERE is_active < :is_active";
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
                    'user_designation_id'   => $row['user_designation_id'],
                    'user_designation'      => $row['user_designation'],
                    'user_type'             => $row['user_type'],
                    'user_type_id'          => $row['user_type_id'],
                    'sort_code'             => $row['sort_code'],
                ];
            }
        }
        return $output;
    }

    function check()
    {
        $data = [
            'user_designation'       => $this->user_designation,
            'user_type_id'       => $this->user_type_id,
            'is_active'       => 1
        ];
        $query = "SELECT user_designation_id FROM " . $this->table_name . " WHERE user_designation=:user_designation AND user_type_id=:user_type_id AND is_active=:is_active ";
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if ($count > 0) {
            $this->user_designation_id = $row['user_designation_id'];
            return true;
        } else
            return false;
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

    function get_by_user_type_id()
    {    $output=[];
        if ($this->user_type_id > 1) {
            $data = [
                'user_type_id'      => $this->user_type_id,
                'is_active'         => 2
            ];
            $query = "SELECT ud.user_designation_id,ud.user_designation,ud.user_type_id,ud.sort_code,ud.is_active,ut.user_type FROM " . $this->table_name . " ud INNER JOIN user_type ut ON (ut.user_type_id = ud.user_type_id) WHERE ud.user_type_id =:user_type_id AND ud.is_active < :is_active ";
        } else {
            $data = [
                'is_active'         => 2
            ];
            $query = "SELECT ud.user_designation_id,ud.user_designation,ud.user_type_id,ud.sort_code,ud.is_active,ut.user_type FROM " . $this->table_name . " ud INNER JOIN user_type ut ON (ut.user_type_id = ud.user_type_id) WHERE ud.is_active < :is_active";
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
                    'user_designation_id'   => $row['user_designation_id'],
                    'user_designation'      => $row['user_designation'],
                    'user_type'             => $row['user_type'],
                    'user_type_id'          => $row['user_type_id'],
                    'sort_code'             => $row['sort_code'],
                ];
            }
        }
        return $output;
    }
}
