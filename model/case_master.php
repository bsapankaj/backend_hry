<?php require_once '../config/DBConnection.php';

class Case_master
{
    public $case_id, $client_code_id, $file_id, $case_no, $case_detail, $court_case_title, $case_description, $clerkage, $clerkage_type, $table_name, $case_vs_from, $case_vs_to, $case_start_date, $case_close_date, $is_active, $created_by, $created_on, $updated_by, $updated_on, $db, $conn;
    
    function __construct()
    {
        $this->case_id = 0;
        $this->file_id = 0;
        $this->client_code_id = 0;
        $this->case_no = "";
        $this->case_detail = NULL;
        $this->case_vs_from = "";
        $this->case_vs_to = "";
        $this->court_case_title = "";
        $this->case_description = "";
        $this->case_start_date = NULL;
        $this->case_close_date = NULL;
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'case_master';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert()
    {
        $data = [
            'file_id'            => $this->file_id,
            'client_code_id'     => $this->client_code_id,
            'case_no'            => $this->case_no,
            'case_detail'        => $this->case_detail,
            'court_case_title'   => $this->court_case_title,
            'case_description'   => $this->case_description,
            'case_vs_from'       => $this->case_vs_from,
            'case_vs_to'         => $this->case_vs_to,
            'case_start_date'    => $this->case_start_date,
            'case_close_date'    => $this->case_close_date,
            'is_active'          => $this->is_active,
            'created_by'         => $this->created_by
        ];
        $sql = "INSERT INTO " . $this->table_name . " (case_no, file_id, client_code_id, case_detail, court_case_title, case_description, case_vs_from, case_vs_to, case_start_date, case_close_date, is_active, created_by) VALUES (:case_no, :file_id, :client_code_id, :case_detail, :court_case_title, :case_description, :case_vs_from, :case_vs_to, :case_start_date, :case_close_date, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function update()
    {
        $data = [
            'file_id'            => $this->file_id,
            'case_id'            => $this->case_id,
            'client_code_id'     => $this->client_code_id,
            'case_no'            => $this->case_no,
            'case_detail'        => $this->case_detail,
            'court_case_title'   => $this->court_case_title,
            'case_description'   => $this->case_description,
            'case_vs_from'       => $this->case_vs_from,
            'case_vs_to'         => $this->case_vs_to,
            'case_start_date'    => $this->case_start_date,
            'case_close_date'    => $this->case_close_date,
            'is_active'          => $this->is_active,
            'updated_by'         => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET client_code_id=:client_code_id, file_id=:file_id, case_no=:case_no, case_detail=:case_detail, court_case_title=:court_case_title, case_description=:case_description, case_vs_from=:case_vs_from, case_vs_to=:case_vs_to, case_start_date=:case_start_date,  case_close_date=:case_close_date,is_active=:is_active, updated_by=:updated_by WHERE case_id=:case_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query; exit;
        $stmt->closeCursor();
        return true;
    }

    function delete()
    {
        $data = [
            'case_id'           => $this->case_id,
            'is_active'         => 2,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active=:is_active, updated_by=:updated_by WHERE case_id=:case_id";
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
            'is_active' => 1
        ];
        if (!empty($Request)) {
            $query = "SELECT c.case_id, c.client_code_id, c.file_id,f.file_no, c.case_no, c.case_detail, c.court_case_title, c.case_description, c.case_vs_from, c.case_vs_to, c.case_start_date, c.case_close_date, cc.client_name, cc.client_code FROM " . $this->table_name . " c 
            INNER JOIN file_master f ON (f.file_id=c.file_id) 
            INNER JOIN client_code cc ON (c.client_code_id=cc.client_code_id) 
            WHERE c.is_active = :is_active";
            if (isset($Request->search->value) && trim($Request->search->value) != '') {
                $data['search_value'] = '%' . trim($Request->search->value) . '%';
                $query .= " AND (c.case_no LIKE :search_value";
                $query .= " OR c.case_vs_from LIKE :search_value";
                $query .= " OR f.file_no LIKE :search_value";
                $query .= " OR c.case_vs_to LIKE :search_value";
                $query .= " OR c.case_start_date LIKE :search_value)";
            }
            if ($this->case_id > 0) {
                $data['case_id'] = $this->case_id;
                $query .= " AND c.case_id = :case_id";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY " . $Request->order['0']->column . " " . $Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY c.case_detail asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start . ', ' . $Request->length;
            }
        } else {
            if ($this->case_id > 0) {
                $data = [
                    'case_id'   => $this->case_id
                ];
                $query = "SELECT c.case_id, c.client_code_id, c.file_id,f.file_no, c.case_no, c.case_detail, c.court_case_title, c.case_description, c.case_vs_from, c.case_vs_to, c.case_start_date, c.case_close_date, cc.client_name, cc.client_code FROM " . $this->table_name . " c 
                INNER JOIN file_master f ON (f.file_id=c.file_id) 
                INNER JOIN client_code cc ON (c.client_code_id=cc.client_code_id) 
                WHERE case_id  :case_id";
            } else {
                $query = "SELECT c.case_id, c.client_code_id, c.file_id,f.file_no, c.case_no, c.case_detail, c.court_case_title, c.case_description, c.case_vs_from, c.case_vs_to, c.case_start_date, c.case_close_date, cc.client_name, cc.client_code FROM " . $this->table_name . " c 
                INNER JOIN file_master f ON (f.file_id=c.file_id) 
                INNER JOIN client_code cc ON (c.client_code_id=cc.client_code_id) 
                WHERE c.is_active<= :is_active";
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
                    'case_id'            => $row['case_id'],
                    'file_id'            => $row['file_id'],
                    'file_no'            => $row['file_no'],
                    'client_code_id'     => $row['client_code_id'],
                    'client_name'        => $row['client_name'],
                    'client_code'        => $row['client_code'],
                    'case_no'            => $row['case_no'],
                    'case_detail'        => $row['case_detail'],
                    'court_case_title'   => $row['court_case_title'],
                    'case_description'   => $row['case_description'],
                    'case_vs_from'       => $row['case_vs_from'],
                    'case_vs_to'         => $row['case_vs_to'],
                    'case_start_date'    => $row['case_start_date'],
                    'case_close_date'    => $row['case_close_date']
                ];
            }
        }
        return $output;
    }

    function fatch_by_lawyer_id()
    {
        @session_start();
        $lawyer_id = $_SESSION["rsp_user_id"];
        $output = [];
        $data = [
            'is_active' => 1
        ];
        $query = "SELECT c.case_id, c.client_code_id, c.case_no, c.case_detail, c.file_id, f.file_no, c.case_vs_from, c.case_vs_to, c.case_start_date, c.case_close_date, cc.client_name, cc.client_code FROM " . $this->table_name . " c INNER JOIN file_master f ON (f.file_id=c.file_id)  INNER JOIN client_code cc ON (c.client_code_id=cc.client_code_id) INNER JOIN case_lawyer cl ON (c.case_id=cl.case_id) WHERE c.is_active = :is_active AND cl.user_id = " . $lawyer_id;

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
                    'case_id'            => $row['case_id'],
                    'client_code_id'     => $row['client_code_id'],
                    'client_name'        => $row['client_name'],
                    'client_code'        => $row['client_code'],
                    'case_no'            => $row['case_no'],
                    'case_detail'        => $row['case_detail'],
                    'case_vs_from'       => $row['case_vs_from'],
                    'case_vs_to'         => $row['case_vs_to'],
                    'case_start_date'    => $row['case_start_date'],
                    'case_close_date'    => $row['case_close_date']
                ];
            }
        }
        return $output;
    }

    function fatch_by_client_code($Request = [])
    {
        $output = [];
        $data = [
            'client_code_id'    => $this->client_code_id,
            'is_active'         => 1
        ];

        $query = "SELECT c.case_id, c.client_code_id, c.case_no, c.case_detail, c.court_case_title, c.case_description, c.file_id,f.file_no, c.case_vs_from, c.case_vs_to, c.case_start_date, c.case_close_date, cc.client_name, cc.client_code FROM " . $this->table_name . " c INNER JOIN file_master f ON (f.file_id=c.file_id) 
        INNER JOIN client_code cc ON (c.client_code_id=cc.client_code_id) WHERE c.client_code_id=:client_code_id and c.is_active =:is_active";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'file_id'            => $row['file_id'],
                    'file_no'            => $row['file_no'],
                    'case_id'            => $row['case_id'],
                    'client_code_id'     => $row['client_code_id'],
                    'client_name'        => $row['client_name'],
                    'client_code'        => $row['client_code'],
                    'case_no'            => $row['case_no'],
                    'case_detail'        => $row['case_detail'],
                    'court_case_title'   => $row['court_case_title'],
                    'case_description'   => $row['case_description'],
                    'case_vs_from'       => $row['case_vs_from'],
                    'case_vs_to'         => $row['case_vs_to'],
                    'case_start_date'    => $row['case_start_date'],
                    'case_close_date'    => $row['case_close_date']
                ];
            }
        }
        return $output;
    }

    function check()
    {
        if (isset($this->case_id) && $this->case_id > 0) {
            $data = [
                'case_id'   => $this->case_id,
                'case_no'   => $this->case_no,
                'is_active'     => 1
            ];
            $stmt = $this->conn->prepare("SELECT case_id FROM " . $this->table_name . " WHERE case_no = :case_no AND is_active=:is_active AND case_id !=:case_id");
        } else {
            $data = [
                'case_no'   => $this->case_no,
                'is_active'     => 1
            ];
            $stmt = $this->conn->prepare("SELECT case_id FROM " . $this->table_name . " WHERE case_no = :case_no AND is_active=:is_active");
        }
        $stmt->execute($data);
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if ($count > 0) {
            $this->case_id = $row['case_id'];
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

    function check_case_with_file_id()
    {   
        if (isset($this->case_id) && $this->case_id > 0) {
            $data = [
                'case_id'   => $this->case_id,
                'file_id'   => $this->file_id,
                'is_active'     => 1
            ];
            $stmt = $this->conn->prepare("SELECT case_id FROM " . $this->table_name . " WHERE file_id = :file_id AND is_active=:is_active AND case_id !=:case_id");
        } else {
            $data = [
                'file_id'   => $this->file_id,
                'is_active'     => 1
            ];
            $stmt = $this->conn->prepare("SELECT case_id FROM " . $this->table_name . " WHERE file_id = :file_id AND is_active=:is_active");
        }
        
        
        $stmt->execute($data);
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            return true;
        } else
            return false;
    }
    function fatch_by_file_id()
    {
        $output = [];
        $data = [
            'file_id'           => $this->file_id,
            'is_active'         => 1
        ];

        $query = "SELECT c.case_id, c.client_code_id, c.case_no, c.case_detail, c.court_case_title, c.case_description, c.file_id,f.file_no, c.case_vs_from, c.case_vs_to, c.case_start_date, c.case_close_date, cc.client_name, cc.client_code FROM " . $this->table_name . " c INNER JOIN file_master f ON (f.file_id=c.file_id) 
        INNER JOIN client_code cc ON (c.client_code_id=cc.client_code_id) WHERE c.file_id=:file_id and c.is_active =:is_active";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'file_id'            => $row['file_id'],
                    'file_no'            => $row['file_no'],
                    'case_id'            => $row['case_id'],
                    'client_code_id'     => $row['client_code_id'],
                    'client_name'        => $row['client_name'],
                    'client_code'        => $row['client_code'],
                    'case_no'            => $row['case_no'],
                    'case_detail'        => $row['case_detail'],
                    'court_case_title'   => $row['court_case_title'],
                    'case_description'   => $row['case_description'],
                    'case_vs_from'       => $row['case_vs_from'],
                    'case_vs_to'         => $row['case_vs_to'],
                    'case_start_date'    => $row['case_start_date'],
                    'case_close_date'    => $row['case_close_date']
                ];
            }
        }
        return $output;
    }
}
