<?php
require_once '../config/DBConnection.php';

class File_master
{

    public $file_id, $file_no, $client_code_id, $file_title, $file_description, $is_active, $created_by, $created_on, $updated_by, $updated_on, $table_name, $db, $conn;

    function __construct()
    {
        $this->file_id = 0;
        $this->file_no = "";
        $this->client_code_id = 0;
        $this->file_title = "";
        $this->file_description = "";
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = "file_master";
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    public function insert()
    {
        $data = [
            "file_no"           => $this->file_no,
            "client_code_id"    => $this->client_code_id,
            "file_title"        => $this->file_title,
            "file_description"  => $this->file_description,
            "is_active"         => $this->is_active,
            "created_by"        => $this->created_by
        ];

        $sql = "INSERT INTO " . $this->table_name . " (file_no,client_code_id,file_title,file_description, is_active, created_by) VALUES(:file_no,:client_code_id,:file_title,:file_description, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $last_query = $stmt->queryString;
        return true;
    }

    // Update 
    public function update()
    {
        $data = [
            "file_id"           => $this->file_id,
            "file_no"           => $this->file_no,
            "client_code_id"    => $this->client_code_id,
            "file_title"        => $this->file_title,
            "file_description"  => $this->file_description,
            "is_active"         => $this->is_active,
            "updated_by"        => $this->updated_by,

        ];
        // print_r($data);exit;
        $sql = "UPDATE " . $this->table_name . " SET file_no=:file_no,client_code_id=:client_code_id,file_title=:file_title,file_description=:file_description, is_active = :is_active, updated_by = :updated_by WHERE file_id=:file_id AND is_active = :is_active";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }


    //Delete
    public function delete()
    {
        $data = [
            "file_id"  => $this->file_id,
            "is_active"     => 2,
            "updated_by"    => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active = :is_active, updated_by = :updated_by WHERE file_id = :file_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        return true;
    }


    // Get
    public function get($Request = [])
    {
        $output = [];
        $data = [
            "is_active"  => 2
        ];

        if (!empty($Request)) {
            $query = "SELECT f.file_id,f.file_no,f.client_code_id,f.file_title,f.file_description,f.is_active,cc.client_code FROM " . $this->table_name . " f
            INNER JOIN client_code cc ON (cc.client_code_id = f.client_code_id AND cc.is_active = 1)
            WHERE f.is_active < :is_active";

            if (isset($Request->search->value)) {
                $data['search_value'] = '%' . $Request->search->value . '%';
                $query .= " AND (f.file_no LIKE :search_value";
                $query .= " OR cc.client_code LIKE :search_value";
                $query .= " OR f.file_title LIKE :search_value)";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY " . $Request->order['0']->column . " " . $Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY f.file_no asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start . ', ' . $Request->length;
            }
        } else {
            if ($this->file_id > 0) {
                $data = [
                    "file_id"   => $this->file_id,
                    "is_active" => 2
                ];
    
                $query = "SELECT f.file_id,f.file_no,f.client_code_id,f.file_title,f.file_description,f.is_active,cc.client_code FROM " . $this->table_name . " f
                INNER JOIN client_code cc ON (cc.client_code_id = f.client_code_id AND cc.is_active = 1)
                WHERE file_id=:file_id AND f.is_active < :is_active";
            } else if ($this->client_code_id > 0) {
                $data = [
                    "client_code_id"   => $this->client_code_id,
                    "is_active" => 2
                ];
    
                $query = "SELECT f.file_id,f.file_no,f.client_code_id,f.file_title,f.file_description,f.is_active,cc.client_code FROM " . $this->table_name . " f
                INNER JOIN client_code cc ON (cc.client_code_id = f.client_code_id AND cc.is_active = 1)
                WHERE f.client_code_id=:client_code_id AND f.is_active < :is_active";
            } else {
                $data = [
                    "is_active"  => 2
                ];
                $query = "SELECT f.file_id,f.file_no,f.client_code_id,f.file_title,f.file_description,f.is_active,cc.client_code FROM " . $this->table_name . " f
                INNER JOIN client_code cc ON (cc.client_code_id = f.client_code_id AND cc.is_active = 1)
                WHERE f.is_active < :is_active";
            }
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query;exit;
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();

        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'file_id'           => $row['file_id'],
                    'file_no'           => $row['file_no'],
                    'client_code'       => $row['client_code'],
                    'client_code_id'    => $row['client_code_id'],
                    'file_title'        => $row['file_title'],
                    'file_description'  => $row['file_description'],
                    'is_active'         => $row['is_active']
                ];
            }
        }
        return $output;
    }

    // Check
    public function check()
    {
        if (isset($this->file_id) && $this->file_id > 0) {
            $data = [
                'file_id'       => $this->file_id,
                'file_no'       => $this->file_no,
                'is_active'     => 1
            ];
            $stmt = $this->conn->prepare("SELECT file_id FROM " . $this->table_name . " WHERE file_no = :file_no AND file_id !=:file_id AND is_active=:is_active");
        }else {
            $data = [
                'file_no'       => $this->file_no,
                'is_active'     => 1
            ];
            $stmt = $this->conn->prepare("SELECT file_id FROM " . $this->table_name . " WHERE file_no = :file_no AND is_active=:is_active");
        }

        $stmt->execute($data);
        $count = $stmt->rowCount();
        if ($count > 0) {
            $row = $stmt->fetch();
            return $row['file_id'];
        } else{
            return 0;
        }
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
}
