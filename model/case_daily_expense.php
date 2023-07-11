<?php require_once '../config/DBConnection.php';

class Case_daily_expenses
{
    public $table_name, $case_daily_expense_id, $expense_date, $file_id, $client_code_id, $case_id, $photocopy, $courier_domestic, $courier_international, $stay_place, $stayWithAss, $hotelNarration, $hotelCalculat_bas, $hotel_stay, $conveyance, $oth_expense, $airStay, $airAss, $airNarration, $airCalculat_bas, $air_ticket, $bill_path, $created_by, $is_active, $updated_by, $db, $conn;

    function __construct()
    {
        $this->case_daily_expense_id = 0;
        $this->expense_date = '0000-00-00';
        $this->file_id = 0;
        $this->client_code_id = 0;
        $this->case_id = 0;
        $this->photocopy = 0;
        $this->courier_domestic = 0;
        $this->courier_international = 0;
        $this->hotel_stay = 0;
        $this->stay_place = 0;
        $this->stayWithAss = 0;
        $this->hotelNarration = '';
        $this->hotelCalculat_bas = '';
        $this->conveyance = 0;
        $this->air_ticket = 0;
        $this->airStay = 0;
        $this->airAss = 0;
        $this->airNarration = '';
        $this->airCalculat_bas = '';
        $this->oth_expense = 0;
        $this->bill_path = 0;
        $this->created_by = '';
        $this->is_active = 1;
        $this->created_by = 0;
        $this->updated_by = 0;
        $this->table_name = 'case_daily_expenses';
        $this->db = new DBConnection();
        $this->conn = $this->db->connect();
    }

    function insert()
    {
        $data = [
            'expense_date'              =>  $this->expense_date,
            'file_id'                   =>  $this->file_id,
            'client_code_id'            =>  $this->client_code_id,
            'case_id'                   =>  $this->case_id,
            'photocopy'                 =>  $this->photocopy,
            'courier_domestic'          =>  $this->courier_domestic,
            'courier_international'     =>  $this->courier_international,
            'hotel_stay'                =>  $this->hotel_stay,
            'stay_place'                =>  $this->stay_place,
            'stayWithAss'               =>  $this->stayWithAss,
            'hotelNarration'            =>  $this->hotelNarration,
            'hotelCalculat_bas'         =>  $this->hotelCalculat_bas,
            'conveyance'                =>  $this->conveyance,
            'air_ticket'                =>  $this->air_ticket,
            'airStay'                   =>  $this->airStay,
            'airAss'                    =>  $this->airAss,
            'airNarration'              =>  $this->airNarration,
            'airCalculat_bas'           =>  $this->airCalculat_bas,
            'oth_expense'               =>  $this->oth_expense,
            'bill_path'                 =>  $this->bill_path,
            'is_active'                 =>  $this->is_active,
            'created_by'                =>  $this->created_by
        ];
        $sql = "INSERT INTO " . $this->table_name . " (expense_date, file_id, client_code_id, case_id, photocopy, courier_domestic, courier_international, stay_place, stayWithAss, hotelNarration, hotelCalculat_bas, hotel_stay, conveyance, oth_expense, airStay, airAss, airNarration, airCalculat_bas, air_ticket, bill_path, is_active,created_by) VALUES (:expense_date, :file_id, :client_code_id, :case_id, :photocopy, :courier_domestic, :courier_international, :stay_place, :stayWithAss, :hotelNarration, :hotelCalculat_bas, :hotel_stay, :conveyance, :oth_expense, :airStay, :airAss, :airNarration, :airCalculat_bas, :air_ticket, :bill_path, :is_active, :created_by)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function update()
    {
        $data = [
            'case_daily_expense_id'     =>  $this->case_daily_expense_id,
            'expense_date'              =>  $this->expense_date,
            'file_id'                   =>  $this->file_id,
            'client_code_id'            =>  $this->client_code_id,
            'case_id'                   =>  $this->case_id,
            'photocopy'                 =>  $this->photocopy,
            'courier_domestic'          =>  $this->courier_domestic,
            'courier_international'     =>  $this->courier_international,
            'hotel_stay'                =>  $this->hotel_stay,
            'stay_place'                =>  $this->stay_place,
            'stayWithAss'               =>  $this->stayWithAss,
            'hotelNarration'            =>  $this->hotelNarration,
            'hotelCalculat_bas'         =>  $this->hotelCalculat_bas,
            'conveyance'                =>  $this->conveyance,
            'air_ticket'                =>  $this->air_ticket,
            'airStay'                   =>  $this->airStay,
            'airAss'                    =>  $this->airAss,
            'airNarration'              =>  $this->airNarration,
            'airCalculat_bas'           =>  $this->airCalculat_bas,
            'oth_expense'               =>  $this->oth_expense,
            'bill_path'                 =>  $this->bill_path,
            'is_active'                 =>  1,
            'updated_by'                =>  $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET client_code_id=:client_code_id, expense_date=:expense_date,file_id=:file_id,client_code_id=:client_code_id, case_id=:case_id, photocopy=:photocopy, courier_domestic=:courier_domestic,  courier_international=:courier_international, stay_place=:stay_place, stayWithAss=:stayWithAss, hotelNarration=:hotelNarration, hotelCalculat_bas=:hotelCalculat_bas, hotel_stay=:hotel_stay, oth_expense=:oth_expense, conveyance=:conveyance, air_ticket=:air_ticket, airStay=:airStay, airAss=:airAss, airNarration=:airNarration, airCalculat_bas=:airCalculat_bas, bill_path=:bill_path, is_active=:is_active, updated_by=:updated_by WHERE case_daily_expense_id=:case_daily_expense_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function delete()
    {
        $data = [
            'case_daily_expense_id'    => $this->case_daily_expense_id,
            'is_active'             => 2,
            'updated_by'            => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET is_active=:is_active, updated_by=:updated_by WHERE case_daily_expense_id=:case_daily_expense_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        // $debug_query = $stmt->_debugQuery();
        // echo $debug_query; exit;
        $stmt->closeCursor();
        return true;
    }

    function get($Request)
    {
        $output = [];
        $data = [
            'is_active'  => 2
        ];
        if (!empty($Request)) {
            $query = "SELECT cde.case_daily_expense_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense, cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket,  cde.bill_path FROM " . $this->table_name . " cde
            INNER JOIN file_master f ON (f.file_id=cde.file_id) 
            INNER JOIN client_code cc ON cc.client_code_id = cde.client_code_id and cc.is_active = 1
            LEFT JOIN case_master c ON c.case_id = cde.case_id and c.is_active = 1
            WHERE cde.is_active < :is_active";
            if (isset($Request->search->value)) {
                $data['search_value'] = '%' . $Request->search->value . '%';
                $query .= " AND (client_code LIKE :search_value";
                $query .= " OR case_no LIKE :search_value)";
            }
            if ($this->case_daily_expense_id > 0) {
                $data['case_daily_expense_id'] = $this->case_daily_expense_id;
                $query .= " AND case_daily_expense_id = :case_daily_expense_id";
            }
            if (isset($Request->order) && $Request->order['0']->column > 0) {
                $query .= " ORDER BY " . $Request->order['0']->column . " " . $Request->order['0']->dir;
            } else {
                $query .= ' ORDER BY c.case_no asc ';
            }
            if (isset($Request->length) && $Request->length != -1) {
                $query .= ' LIMIT ' . $Request->start . ', ' . $Request->length;
            }
        } else {
            if ($this->case_daily_expense_id > 0) {
                $data = [
                    'case_daily_expense_id'   => $this->case_daily_expense_id
                ];
                $query = "SELECT cde.case_daily_expense_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense,cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket, cde.bill_path FROM " . $this->table_name . " cde
                INNER JOIN file_master f ON (f.file_id=cde.file_id) 
                INNER JOIN client_code cc ON cc.client_code_id = cde.client_code_id and cc.is_active = 1  
                LEFT JOIN case_master c ON c.case_id = cde.case_id and c.is_active = 1
                WHERE case_daily_expense_id =:case_daily_expense_id";
            } else {
                $query = "SELECT cde.case_daily_expense_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense,cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket, cde.bill_path FROM " . $this->table_name . " cde
                INNER JOIN file_master f ON (f.file_id=cde.file_id) 
                INNER JOIN client_code cc ON cc.client_code_id = cde.client_code_id and cc.is_active = 1  
                LEFT JOIN case_master c ON c.case_id = cde.case_id and c.is_active = 1
                WHERE cde.is_active < :is_active";
            }
        }
        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'case_daily_expense_id'     =>  $row['case_daily_expense_id'],
                    'expense_date'              =>  date('Y-m-d', strtotime($row['expense_date'])),
                    'file_id'                   =>  $row['file_id'],
                    'file_no'                   =>  $row['file_no'],
                    'client_code_id'            =>  $row['client_code_id'],
                    'client_code'               =>  $row['client_code'],
                    'case_id'                   =>  $row['case_id'],
                    'case_no'                   =>  $row['case_no'],
                    'photocopy'                 =>  $row['photocopy'],
                    'courier_domestic'          =>  $row['courier_domestic'],
                    'courier_international'     =>  $row['courier_international'],
                    'hotel_stay'                =>  $row['hotel_stay'],
                    'stay_place'                =>  $row['stay_place'],
                    'stayWithAss'               =>  $row['stayWithAss'],
                    'hotelNarration'            =>  $row['hotelNarration'],
                    'hotelCalculat_bas'         =>  $row['hotelCalculat_bas'],
                    'conveyance'                =>  $row['conveyance'],
                    'air_ticket'                =>  $row['air_ticket'],
                    'airStay'                   =>  $row['airStay'],
                    'airAss'                    =>  $row['airAss'],
                    'airNarration'              =>  $row['airNarration'],
                    'airCalculat_bas'           =>  $row['airCalculat_bas'],
                    'oth_expense'               =>  $row['oth_expense'],
                    'bill_path'                 =>  $row['bill_path']
                ];
            }
        }
        return $output;
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

    function last_insert_id()
    {
        $stmt = $this->conn->prepare("SELECT LAST_INSERT_ID() as last_id FROM " . $this->table_name);
        $stmt->execute();
        $result = $stmt->fetch();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        return $result['last_id'];
    }

    function update_bill_path()
    {
        $data = [
            'case_daily_expense_id'        => $this->case_daily_expense_id,
            'bill_path'                    => $this->bill_path,
            'updated_by'                   => $this->updated_by
        ];
        $sql = "UPDATE " . $this->table_name . " SET bill_path=:bill_path ,updated_by=:updated_by WHERE case_daily_expense_id=:case_daily_expense_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        $stmt->closeCursor();
        return true;
    }

    function getSingle()
    {
        $output = [];

        if ($this->case_daily_expense_id > 0) {
            $data = [
                'case_daily_expense_id'   => $this->case_daily_expense_id
            ];
            $query = "SELECT cde.case_daily_expense_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense,cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket, cde.bill_path FROM " . $this->table_name . " cde
            INNER JOIN file_master f ON (f.file_id=cde.file_id) 
            INNER JOIN client_code cc ON cc.client_code_id = cde.client_code_id and cc.is_active = 1  
            LEFT JOIN case_master c ON c.case_id = cde.case_id and c.is_active = 1
            WHERE case_daily_expense_id =:case_daily_expense_id";
        } else {
            $query = "SELECT cde.case_daily_expense_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense,cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket, cde.bill_path FROM " . $this->table_name . " la
            INNER JOIN file_master f ON (f.file_id=cde.file_id) 
            INNER JOIN client_code cc ON cc.client_code_id = cde.client_code_id and cc.is_active = 1  
            LEFT JOIN case_master c ON c.case_id = cde.case_id and c.is_active = 1
            WHERE cde.is_active < :is_active";
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($data);
        $results = $stmt->fetchAll();
        $count = $stmt->rowCount();
        $stmt->closeCursor();
        if ($count > 0) {
            foreach ($results as $row) {
                $output[] = [
                    'case_daily_expense_id'     =>  $row['case_daily_expense_id'],
                    'expense_date'              =>  date('Y-m-d', strtotime($row['expense_date'])),
                    'file_id'                   =>  $row['file_id'],
                    'file_no'                   =>  $row['file_no'],
                    'client_code_id'            =>  $row['client_code_id'],
                    'client_code'               =>  $row['client_code'],
                    'case_id'                   =>  $row['case_id'],
                    'case_no'                   =>  $row['case_no'],
                    'photocopy'                 =>  $row['photocopy'],
                    'courier_domestic'          =>  $row['courier_domestic'],
                    'courier_international'     =>  $row['courier_international'],
                    'hotel_stay'                =>  $row['hotel_stay'],
                    'stay_place'                =>  $row['stay_place'],
                    'stayWithAss'               =>  $row['stayWithAss'],
                    'hotelNarration'            =>  $row['hotelNarration'],
                    'hotelCalculat_bas'         =>  $row['hotelCalculat_bas'],
                    'conveyance'                =>  $row['conveyance'],
                    'air_ticket'                =>  $row['air_ticket'],
                    'airStay'                   =>  $row['airStay'],
                    'airAss'                    =>  $row['airAss'],
                    'airNarration'              =>  $row['airNarration'],
                    'airCalculat_bas'           =>  $row['airCalculat_bas'],
                    'oth_expense'               =>  $row['oth_expense'],
                    'bill_path'                 =>  $row['bill_path']
                ];
            }
        }
        return $output;
    }

    function get_daily_expense_by_time($Obj)
    {
        $output = [];

        if (!empty($Obj)) {
            if (isset($Obj->invoice_id)) {
                if ($Obj->invoice_id > 0 && $Obj->is_final > 0) {
                    $data = [
                        'is_active'     => 1,
                        'file_id'       => $Obj->file_id,
                        'invoice_id'    => $Obj->invoice_id,
                        'start_date'    => date('Y-m-d', strtotime($Obj->start_date)),
                        'end_date'      => date('Y-m-d', strtotime($Obj->end_date))
                    ];
                    $query = "SELECT cde.case_daily_expense_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, cde.invoice_id, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense,cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket, cde.bill_path 
                    FROM " . $this->table_name . " cde
                    INNER JOIN file_master f ON (f.file_id=cde.file_id)
                    INNER JOIN client_code cc ON (cc.client_code_id = cde.client_code_id and cc.is_active = 1)  
                    LEFT JOIN case_master c ON (c.case_id = cde.case_id and c.is_active = 1)
                    WHERE cde.file_id =:file_id AND (DATE(cde.expense_date) >= :start_date AND DATE(cde.expense_date) <= :end_date) and cde.invoice_id =:invoice_id and cde.is_active=:is_active";
                } else {
                    $data = [
                        'is_active'     => 1,
                        'file_id'       => $Obj->file_id,
                        'start_date'    => date('Y-m-d', strtotime($Obj->start_date)),
                        'end_date'      => date('Y-m-d', strtotime($Obj->end_date))
                    ];
                    $query = "SELECT cde.case_daily_expense_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense,cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket, cde.bill_path 
                    FROM " . $this->table_name . " cde
                    INNER JOIN file_master f ON (f.file_id=cde.file_id)
                    INNER JOIN client_code cc ON (cc.client_code_id = cde.client_code_id and cc.is_active = 1)  
                    LEFT JOIN case_master c ON (c.case_id = cde.case_id and c.is_active = 1)
                    WHERE cde.file_id =:file_id AND (DATE(cde.expense_date) >= :start_date AND DATE(cde.expense_date) <= :end_date) and cde.is_active=:is_active";
                }
            } else {
                $data = [
                    'is_active'     => 1,
                    'file_id'       => $Obj->file_id,
                    'start_date'    => date('Y-m-d', strtotime($Obj->start_date)),
                    'end_date'      => date('Y-m-d', strtotime($Obj->end_date))
                ];
                $query = "SELECT cde.case_daily_expense_id, cde.expense_date, cde.file_id,f.file_no, cde.client_code_id, cc.client_code, c.case_no, cde.case_id, cde.photocopy, cde.courier_domestic, cde.courier_international, cde.stay_place, cde.stayWithAss, cde.hotelNarration, cde.hotelCalculat_bas, cde.hotel_stay, cde.oth_expense,cde.conveyance, cde.airStay, cde.airAss, cde.airNarration, cde.airCalculat_bas, cde.air_ticket, cde.bill_path 
                FROM " . $this->table_name . " cde
                INNER JOIN file_master f ON (f.file_id=cde.file_id)
                INNER JOIN client_code cc ON (cc.client_code_id = cde.client_code_id and cc.is_active = 1)  
                LEFT JOIN case_master c ON (c.case_id = cde.case_id and c.is_active = 1)
                WHERE cde.file_id =:file_id AND (DATE(cde.expense_date) >= :start_date AND DATE(cde.expense_date) <= :end_date) and cde.is_active=:is_active";
            }


            $stmt = $this->conn->prepare($query);
            $stmt->execute($data);
            $results = $stmt->fetchAll();
            $count = $stmt->rowCount();
            // $last_query = $stmt->queryString;
            // $debug_query = $stmt->_debugQuery();
            // echo $debug_query; exit;
            $stmt->closeCursor();
            if ($count > 0) {
                foreach ($results as $row) {
                    $output[] = [
                        'case_daily_expense_id'     =>  $row['case_daily_expense_id'],
                        'expense_date'              =>  date('Y-m-d', strtotime($row['expense_date'])),
                        'file_id'                   =>  $row['file_id'],
                        'file_no'                   =>  $row['file_no'],
                        'client_code_id'            =>  $row['client_code_id'],
                        'client_code'               =>  $row['client_code'],
                        'case_id'                   =>  $row['case_id'],
                        'case_no'                   =>  $row['case_no'],
                        'photocopy'                 =>  $row['photocopy'],
                        'courier_domestic'          =>  $row['courier_domestic'],
                        'courier_international'     =>  $row['courier_international'],
                        'hotel_stay'                =>  $row['hotel_stay'],
                        'stay_place'                =>  $row['stay_place'],
                        'stayWithAss'               =>  $row['stayWithAss'],
                        'hotelNarration'            =>  $row['hotelNarration'],
                        'hotelCalculat_bas'         =>  $row['hotelCalculat_bas'],
                        'conveyance'                =>  $row['conveyance'],
                        'air_ticket'                =>  $row['air_ticket'],
                        'airStay'                   =>  $row['airStay'],
                        'airAss'                    =>  $row['airAss'],
                        'airNarration'              =>  $row['airNarration'],
                        'airCalculat_bas'           =>  $row['airCalculat_bas'],
                        'oth_expense'               =>  $row['oth_expense'],
                        'bill_path'                 =>  $row['bill_path']
                    ];
                }
            }
        }
        return $output;
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
        $sql = "UPDATE " . $this->table_name . " SET invoice_id=:invoice_id, updated_by=:updated_by WHERE file_id=:file_id AND DATE(expense_date) >= :start_time AND DATE(expense_date) <= :end_time AND is_active =1;";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($data);
        // $last_query = $stmt->queryString;
        // $debug_query = $stmt->_debugQuery();
        $stmt->closeCursor();
        return true;
    }
}
