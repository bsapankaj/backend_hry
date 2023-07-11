<?php require_once '../config/DBConnection.php';

class User_login {
    
    Public $user_login_id, $user_id, $username, $password, $last_login_time, $new_password,$last_login_ip, $default_password_change, $password_change_time, $is_active, $created_by, $created_on, $updated_by, $updated_on, $table_name, $db, $conn;

    function __construct(){
        $this->user_login_id = "";
        $this->user_id = 0;
        $this->username = "";
        $this->password = "";
        $this->last_login_time = NULL;
        $this->last_login_ip = "";
        $this->default_password_change = 0;
        $this->password_change_time = NULL;
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = "user_login";
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert(){
        $data = [
            'user_id'                  => $this->user_id,
            'username'                 => $this->username,
            'password'                 => $this->generate_password($this->password),
            'last_login_time'          => $this->last_login_time,
            'last_login_ip'            => $this->last_login_ip,
            'default_password_change'  => $this->default_password_change,
            'password_change_time'     => $this->password_change_time,
            'is_active'                => $this->is_active,
            'created_by'               => $this->created_by
        ];
        
        $sql = "INSERT INTO ".$this->table_name." (user_id, username, password, last_login_time, last_login_ip, default_password_change, password_change_time, is_active, created_by) VALUES (:user_id, :username, :password, :last_login_time , :last_login_ip, :default_password_change, :password_change_time, :is_active, :created_by)";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function update() {
        $data = [
            'user_login_id'     => $this->user_login_id,
            'user_id'           => $this->user_id,
            'username'          => $this->username,
            'password'          => $this->generate_password($this->password),
            'is_active'         => $this->is_active,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET user_id=:user_id, username=:username, password=:password,  is_active=:is_active, updated_by=:updated_by WHERE user_id=:user_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function delete() {
        $data = [
            'user_login_id'     => $this->user_login_id,
            'is_active'         => 2,
            'updated_by'        => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET is_active=:is_active, updated_by=:updated_by WHERE user_login_id=:user_login_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        $last_query = $stmt->queryString;
        return $last_query;
    }

    function remove() {
        $data = [
            'user_id'    => $this->user_id,
            'is_active'  => 2,
            'updated_by' => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET is_active=:is_active, updated_by=:updated_by WHERE user_id=:user_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function get($Request) {
        $output = [];
        $data = [
            'is_active' => 2
        ];
        if(!empty($Request)){
            $query = "SELECT user_id,username,password,last_login_time ,last_login_ip,default_password_change,user_id_id FROM ".$this->table_name." WHERE is_active < :is_active";  
            if (isset($Request->search->value)) {
                $data['search_value'] = '%'.$Request->search->value.'%';
                $query .= " AND (user_id LIKE :search_value";
                $query .= " OR username LIKE :search_value";
                $query .= " OR password LIKE :search_value";
                $query .= " OR last_login_time LIKE :search_value";
                $query .= " OR last_login_ip LIKE :search_value";
                $query .= " OR default_password_change	 LIKE :search_value)";
            } 
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY ".$Request->order['0']->column." ".$Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY username asc ';
            }
            if ($Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start. ', ' . $Request->length;
            }
        }else{
            $query = "SELECT * FROM ".$this->table_name." WHERE is_active < :is_active";    
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery(); 
        $stmt->closeCursor();
        if($count>0) {
            foreach($results as $row) {
                $output[] = [
                    'user_login_id'             => $row['user_login_id'],
                    'user_id'                   => $row['user_id'],
                    'username'                  => $row['username'],
                    'password'                  => $row['password'],
                    'last_login_time'           => $row['last_login_time'],
                    'last_login_ip'             => $row['last_login_ip'],
                    'default_password_change'   => $row['default_password_change']
                ];
            }
        }
        return $output;
    }

    function check() {
        $data = [
            'user_id'   => $this->user_id,
            'is_active'     => 1
        ];
        $stmt = $this->conn->prepare("SELECT user_login_id FROM ".$this->table_name." WHERE user_id = :user_id AND is_active=:is_active");
        $stmt->execute($data);
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if($count>0) {
            $this->user_login_id = $row['user_login_id'];
            return true;
        } else 
            return false;
    }

    function check_username() {
        $data = [
            'username'   => $this->username,
            'is_active'     => 1
        ];
        $stmt = $this->conn->prepare("SELECT user_login_id FROM ".$this->table_name." WHERE username = :username AND is_active=:is_active");
        $stmt->execute($data);
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if($count>0) {
            $this->user_login_id = $row['user_login_id'];
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

    function validate_login() {
        $data = [
            'username'  => $this->username,
            'is_active' => 1
        ];
        $stmt = $this->conn->prepare("SELECT user_login_id, ".$this->table_name.".user_id, name, email_id, mobile_no, username,user_type.user_type, password, default_password_change FROM ".$this->table_name." INNER JOIN user ON (".$this->table_name.".user_id=user.user_id) INNER JOIN user_type ON (user.user_type_id=user_type.user_type_id) WHERE username = :username AND ".$this->table_name.".is_active=:is_active");
        $stmt->execute($data);
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query;exit;
        // print_r($row);exit;
        $stmt->closeCursor();
        if($count>0) {  
            
            // print_r($row['password']);exit;
            if($this->validate_password($this->password, $row['password'])) {
                if($row['default_password_change']==1) {
                    $data = [
                        'user_login_id'     => $row['user_login_id'],
                        'last_login_time'   => date('Y-m-d H:i:s')
                    ];
                    $sql = "UPDATE ".$this->table_name." SET last_login_time =:last_login_time WHERE user_login_id=:user_login_id";
                    $stmt= $this->conn->prepare($sql);
                    $stmt->execute($data);
                    // print_r($stmt);exit; 
                    $stmt->closeCursor();

                    session_start();
                    $_SESSION["hryS_session_status"] = true;
                    $_SESSION["hryS_user_id"]        = $row['user_id'];
                    $_SESSION["hryS_user_login_id"]  = $row['user_login_id'];
                    $_SESSION["hryS_name"]           = $row['name'];
                    $_SESSION["hryS_email_id"]       = $row['email_id'];
                    $_SESSION["hryS_mobile_no"]      = $row['mobile_no'];
                    $_SESSION["hryS_user_type"]      = $row['user_type'];
                    $_SESSION["hryS_username"]       = $row['username'];

                    return true;
                } else {
                    throw new Exception('Please change your default password', 402);
                }
            } else {
                throw new Exception("Invalid Password",401);
            }
        } else {
            throw new Exception('User does not exists',401);
        }
    }

    function validate_user() {
        $data = [
            'username'  => $this->username,
            'is_active' => 1
        ];
        $stmt = $this->conn->prepare("SELECT user_login_id, ".$this->table_name.".user_id, name, email_id, mobile_no, username,user_type.user_type, password, default_password_change FROM ".$this->table_name." INNER JOIN user ON (".$this->table_name.".user_id=user.user_id) INNER JOIN user_type ON (user.user_type_id=user_type.user_type_id) WHERE username = :username AND ".$this->table_name.".is_active=:is_active");
        $stmt->execute($data);
        $count = $stmt->rowCount();
        $row = $stmt->fetch();
        $stmt->closeCursor();
        if($count>0) {
            if($this->validate_password($this->password, $row['password'])) {
                return $row['user_id'];
            } else {
                throw new Exception("Invalid Password");
            }
        } else {
            throw new Exception("User does not exist");
        }
    }

    function modify_password() {
        $data = [
            'user_id'                     => $this->user_id,
            'password'                    => $this->generate_password($this->password),
            'is_active'                   => $this->is_active,
            'updated_by'                  => $this->updated_by
        ];
        $sql = "UPDATE ".$this->table_name." SET password=:password,  is_active=:is_active, updated_by=:updated_by WHERE user_id=:user_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function change_password() {
        $data = [
            'user_id'                    => $this->user_id,
            'password'                   => $this->generate_password($this->new_password),
            'is_active'                  => $this->is_active,
            'updated_by'                 => $this->user_id
        ];
        $sql = "UPDATE ".$this->table_name." SET password=:password,  is_active=:is_active, default_password_change='1',updated_by=:updated_by WHERE user_id=:user_id";
        $stmt= $this->conn->prepare($sql);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query;exit;
        $stmt->closeCursor();
        return true;
    }

    function validate_password($password='', $db_password='') {
        if(password_verify($password, $db_password)) {
            return true;
        }
        return false;
    }

    function generate_password() {
        return password_hash($this->password, PASSWORD_BCRYPT, ["cost"=>12]);
    }

}
?>