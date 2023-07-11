<?php

session_start();
require '../modal/db.php';
require '../../PHPMailer/PHPMailerAutoload.php';
require '../modal/suportFunctions.php';
// $conn = new db();
$connection = $conn->connect();
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$userId = $_SESSION['bsa_group_user_id'];
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = array();
    $data = array();
    if (isset($_POST['type']) && $_POST['type'] == "get_cases_for_audit") {
        $date = $_POST['date'];
        if (empty($date)) {
            $activity_date = date('Y-m-d');
        } else {
            $activity_date = $date;
        }
        try {
            $query = "select a.activity_id,ci.customer_id,cc.client_code,customer_name,
            boxno,ci.address,am.area,ci.contact_person from activity_dbfc a
            inner join customer_information ci on ci.customer_id=a.customer_id
            inner join client_code_dbfc cc on cc.client_code_id=ci.client_code_id
            inner join area_master am on am.area_id=ci.area_id
            inner join feedback_dbfc f on f.activity_id=a.activity_id
            where ci.application_id='" . $_SESSION['bsa_group_default_application_id'] . "' and activity_date='$activity_date' and f.audit_status='N'
            and is_cheque_collected='N'";
            $statement = $connection->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $statement->closeCursor();
            $dataArray = [];
            $i = 0;
            foreach ($result as $row) {
                ++$i;
                $innerArray = [];
                $innerArray[] = $i;
                $innerArray[] = $row['customer_id'];
                $innerArray[] = $row['client_code'];
                $innerArray[] = $row['customer_name'];
                $innerArray[] = $row['boxno'];
                $innerArray[] = $row['address'];
                $innerArray[] = $row['area'];
                $innerArray[] = $row['contact_person'];
                $innerArray[] = '<a class="badge green audit" data-acid="' . $row['activity_id'] . '">Audit</a>';
                $dataArray[] = $innerArray;
            }
            $data['success'] = true;
            $data['data'] = $dataArray;
        } catch (Exception $ex) {
            $data['success'] = false;
            $errors['error'] = $ex->getMessage();
            $data['errors'] = $errors;
        }
        echo json_encode($data);
    } else if (isset($_POST['type']) && $_POST['type'] == "get_single") {
        $activity_id = $_POST['activity_id'];
        try {
            $query = "select a.activity_id,DATE_FORMAT(a.activity_date, '%Y-%m-%d') as dir_name,cc.client_code,customer_name,ci.customer_id as card_no,boxno,
            ci.address,fm.franchisee_name,concat(fe.fe_name,'-',fe.login_id,' (',ifnull(df.username,'*'),')-',fe.mobile_no) as fe_name,
            ci.contact_person,ci.phone,ci.mobile_no,ci.visit_mode,ci.pickup_time_to as pickup_to, ci.pickup_time_from as pickup_from,
            f.transaction_date,is_cheque_collected,is_cheque_counted,cheque_count,
            envelope_count,open_doc_count,other_count,cheque_count+
            envelope_count+open_doc_count+other_count as grand_total,receving_type,Manual_Mode, 
            type_of_dropbox,name_of_person_when_dropbox_is_person,audit_done,
            audit_dropbox_found,audit_dropbox_damage,audit_dropbox_lock_working,
            audit_dropbox_key_available_with_fe,audit_dropbox_sticker,audit_dropbox_painted,
            physical_received,mismatch_count,latitude,longitude,google_address, contract_nos, no_of_contract, nach_form_nos,no_of_nach_form, no_of_other_document, weight_in_kg,no_of_bag, cm.city as destination_location, a.pod_no,f.reason
            from activity_dbfc a
            inner join customer_information ci on ci.customer_id=a.customer_id
            inner join client_code_dbfc cc on cc.client_code_id=ci.client_code_id
            inner join franchisee_master fm on fm.franchisee_id=a.franchisee_id
            -- inner join area_master FA on FA.area_id=fm.area_id
            -- inner Join city_master FC on FC.city_id = FA.city_id
            inner Join city_master FC on FC.city_id = fm.city_id
            inner join fe_master fe on fe.fe_id=a.fe_id
            left outer join doxcol.mobile_login df on df.bsagroup_fe_id=fe.fe_id
            left outer join feedback_dbfc f on f.activity_id=a.activity_id
            left outer join city_master cm on a.destination_location=cm.city_id
            where a.activity_id=$activity_id";
            $statement = $connection->prepare($query);
            $statement->execute();
            $statement->setFetchMode(PDO::FETCH_ASSOC);
            $row = $statement->fetch();
            // print_r($row);exit;
            $statement->closeCursor();
            $dir_name = $row['dir_name'];
            $fileActiviy = $row['activity_id'];
            if ($row['is_cheque_collected'] == 'Y' || $row['is_cheque_collected'] == 'N') {
                $standard_activity_start_time = $row['dir_name'] . ' ' . $row['pickup_from'];
                $standard_activity_end_time = $row['dir_name'] . ' ' . $row['pickup_to'];
                $activity_time = $row['transaction_date'];
                if (strtotime($standard_activity_start_time) <= strtotime($activity_time) && strtotime($activity_time) <= strtotime($standard_activity_end_time)) {
                    $color = '<b style="color:green;">With in Tat</b>';
                } else {
                    $color = '<b style="color:red;">Out of Tat</b>';
                }
                print_r($color);
            }
            $photoArray = [
                "DbfcDropBox",
                "DbfcDropBox2",
                "DropBoxPhoto",
                "Map",
                "DbfcLocationPhoto",
                "DbfcMap"
            ];
            foreach ($photoArray as $key => $value) {
                if (file_exists("../../../dbfc/$dir_name/$fileActiviy" . $value . ".jpg")) {
                    $images[$value] = '<a data-magnify="gallery" data-src="" data-caption="' . $value . '" data-group="a" href="https://www.bsaapps.co.in/dbfc/' . $dir_name . '/' . $fileActiviy . $value . '.jpg"><img src="../../dbfc/' . $dir_name . '/' . $fileActiviy . $value . '.jpg" width="100%" style="height:200px; cursor:pointer;"></a>';
                    //$images[$value] = "<a class='imgView' data-imgsrc='https://www.bsaapps.co.in/dbfc/$dir_name/$fileActiviy" . $value . ".jpg'><img src='../../dbfc/$dir_name/$fileActiviy" . $value . ".jpg' width='100%' style='height:200px; cursor:pointer;'/></a>";
                } else {
                    $images[$value] = false;
                }
//                 $images[$value."2"] = "../../dbfc/$dir_name/$fileActiviy" . $value . ".jpg";
            }
            $data['success'] = true;
            $data['data'] = $row;
            $data['images'] = $images;
        } catch (Exception $ex) {
            $data['success'] = false;
            $errors['error'] = $ex->getMessage();
            $data['errors'] = $errors;
        }
        echo json_encode($data);
    } else if (isset($_POST['type']) && $_POST['type'] == "update_audit_status") {
        $activity_id = $_POST['activity_id'];
        $audit_status = $_POST['audit_status'];
        $audit_remarks = $_POST['audit_remarks'];
        try {
            $query = "UPDATE feedback_dbfc SET audit_status='$audit_status',
            audit_remarks='$audit_remarks',audit_by=$userId,audit_on=NOW() 
            WHERE activity_id=$activity_id";
            $statement = $connection->prepare($query);
            $statement->execute();
            $statement->closeCursor();
            $data['success'] = true;
        } catch (Exception $ex) {
            $data['success'] = false;
            $errors['error'] = $ex->getMessage();
            $data['errors'] = $errors;
        }
        echo json_encode($data);
    } else if (isset($_POST['type']) && $_POST['type'] == "get_cases_for_franchisee_fail_cases") {
        $date = $_POST['date'];
        if (empty($date)) {
            $activity_date = date('Y-m-d');
        } else {
            $activity_date = $date;
        }
        try {
            if ($_SESSION['bsa_group_role'] == 'BSA FRANCHISEE') {
                $franchisee_id = $_SESSION['bsa_group_franchisee_id'];
                $query = "select a.activity_date as dirname,f.reason, a.activity_id,ci.customer_id,cc.client_code,customer_name, boxno,ci.address,am.area,"
                        . "ci.contact_person,fr.franchisee_name, fm.fe_name,f.audit_remarks from activity_dbfc a "
                        . "inner join customer_information ci on ci.customer_id=a.customer_id "
                        . "inner join client_code_dbfc cc on cc.client_code_id=ci.client_code_id "
                        . "inner join area_master am on am.area_id=ci.area_id "
                        . "inner join feedback_dbfc f on f.activity_id=a.activity_id "
                        . "inner join fe_master fm on a.fe_id=fm.fe_id "
                        . "inner join franchisee_master fr on fm.franchisee_id=fr.franchisee_id "
                        . "where ci.application_id=" . $_SESSION['bsa_group_default_application_id'] . " and activity_date='$activity_date' and is_cheque_collected='N' AND a.franchisee_id=$franchisee_id AND (f.audit_status='F' or audit_remarks like '%wrong%' or audit_remarks like '%photo%'  or audit_remarks like '%pic%' or audit_remarks like '%difference%' or audit_remarks like '%miss%' or audit_remarks like '%clear%') GROUP BY a.activity_id";
            } else {
                $query = "select a.activity_date as dirname,f.reason, a.activity_id,ci.customer_id,cc.client_code,customer_name, boxno,ci.address,am.area,ci.contact_person,fr.franchisee_name, fm.fe_name,f.audit_remarks from activity_dbfc a inner join customer_information ci on ci.customer_id=a.customer_id inner join client_code_dbfc cc on cc.client_code_id=ci.client_code_id inner join area_master am on am.area_id=ci.area_id inner join feedback_dbfc f on f.activity_id=a.activity_id inner join fe_master fm on a.fe_id=fm.fe_id inner join franchisee_master fr on fm.franchisee_id=fr.franchisee_id where ci.application_id=" . $_SESSION['bsa_group_default_application_id'] . " and activity_date='$activity_date' and is_cheque_collected='N' AND (f.audit_status='F' or audit_remarks like '%wrong%' or audit_remarks like '%photo%'  or audit_remarks like '%pic%' or audit_remarks like '%difference%' or audit_remarks like '%miss%' or audit_remarks like '%clear%') GROUP BY a.activity_id";
            }
            $statement = $connection->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $statement->closeCursor();
            $dataArray = [];
            $i = 0;
            foreach ($result as $row) {
                ++$i;

                $photoArray = ["DbfcLocationPhoto"];
                foreach ($photoArray as $key => $value) {
                    $dir_name = $row['dirname'];
                    $fileActiviy = $row['activity_id'];
                    if (file_exists("../../../dbfc/$dir_name/$fileActiviy" . $value . ".jpg")) {
                        $imgUrl = "dbfc/$dir_name/$fileActiviy" . $value . ".jpg";
                    } else {
                        $imgUrl = false;
                    }
                }
                $innerArray = [];
                $innerArray[] = $i;
                $innerArray[] = $row['customer_id'];
                $innerArray[] = $row['client_code'];
                $innerArray[] = $row['customer_name'];
                $innerArray[] = $row['boxno'];
                $innerArray[] = $row['address'];
                $innerArray[] = $row['area'];
                $innerArray[] = $row['contact_person'];
                $innerArray[] = $row['franchisee_name'];
                $innerArray[] = $row['fe_name'];
                $innerArray[] = $row['reason'];
                $innerArray[] = $row['audit_remarks'] . "<br/><a class='badge green viewPhoto' data-acid='" . $row['activity_id'] . "' data-imgUrl='$imgUrl'><i class='fa fa-eye'></i></a>";
                $dataArray[] = $innerArray;
            }
            $data['success'] = true;
            $data['data'] = $dataArray;
        } catch (Exception $ex) {
            $data['success'] = false;
            $errors['error'] = $ex->getMessage();
            $data['errors'] = $errors;
        }
        echo json_encode($data);
    } else if (isset($_POST['type']) && $_POST['type'] == "get_data_for_submission") {
        try {
            $client_code_id = $_POST['client_id'];
            $date = $_POST['date'];
            $query = "SELECT distinct cc.client_code, c.customer_id, c.customer_name, c.boxno, c.address, am.area, f.cheque_count, f.open_doc_count,f.other_count, f.weight_in_kg, f.pod_no  FROM `feedback_dbfc` f INNER JOIN activity_dbfc a ON (f.activity_id=a.activity_id) INNER JOIN customer_information c ON (a.customer_id=c.customer_id) INNER JOIN client_code_dbfc cc ON (c.client_code_id=cc.client_code_id) INNER JOIN area_master am ON (c.area_id=am.area_id) WHERE f.receiving_date = '$date' AND c.application_id=15 AND c.client_code_id=$client_code_id";
            $statement = $connection->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $statement->closeCursor();
            $dataArray = [];
            $i = 0;
            foreach ($result as $row) {
                ++$i;
                $innerArray = [];
                $innerArray[] = $i;
                $innerArray[] = $row['customer_id'];
                $innerArray[] = $row['client_code'];
                $innerArray[] = $row['boxno'];
                $innerArray[] = $row['customer_name'];
                $innerArray[] = $row['address'];
                $innerArray[] = $row['area'];
                $innerArray[] = $row['cheque_count'];
                $innerArray[] = $row['open_doc_count'];
                $innerArray[] = $row['other_count'];
                $innerArray[] = $row['weight_in_kg'];
                $innerArray[] = $row['pod_no'];
                $dataArray[] = $innerArray;
            }
            $data['success'] = true;
            $data['data'] = $dataArray;
        } catch (Exception $ex) {
            $data['success'] = false;
            $errors['error'] = $ex->getMessage();
            $data['errors'] = $errors;
        }
        echo json_encode($data);
    } else if (isset($_POST['type']) && $_POST['type'] == "download_submission") {
        try {
            $client_code_id = $_POST['client_id'];
            $client_name = '';
            if (isset($_POST['client_name'])) {
                $client_name = "-" . str_replace('amp;', '', $_POST['client_name']);
            }
            $date = $_POST['date'];
            $query = "SELECT distinct cc.client_code, c.customer_id, c.customer_name, c.boxno, c.address, am.area, f.cheque_count, f.open_doc_count,f.other_count, f.weight_in_kg, f.pod_no  FROM `feedback_dbfc` f INNER JOIN activity_dbfc a ON (f.activity_id=a.activity_id) INNER JOIN customer_information c ON (a.customer_id=c.customer_id) INNER JOIN client_code_dbfc cc ON (c.client_code_id=cc.client_code_id) INNER JOIN area_master am ON (c.area_id=am.area_id) WHERE f.receiving_date = '$date' AND c.application_id=15 AND c.client_code_id=$client_code_id";
            $statement = $connection->prepare($query);
            $statement->execute();
            $result = $statement->fetchAll();
            $statement->closeCursor();
            $dataArray = [];
            $i = 0;
            $TotalFiles = $TotalPackets = $TotalOtherDoc = $TotalWeight = $TotalCollections = 0;
            $dataArray[] = ["S. No.", "Card No.", "Client Code", "Client Uniuqe No.", "Location Name", "Address", "Area", "No. Of Files", "No. Of Packets", "No. Of Other Documents", "Weight", "POD No"];
            foreach ($result as $row) {
                ++$i;
                $TotalFiles += (int) $row['cheque_count'];
                $TotalPackets += (int) $row['open_doc_count'];
                $TotalOtherDoc += (int) $row['other_count'];
                $TotalWeight += (int) $row['weight_in_kg'];
                $TotalCollections += ((int) $row['cheque_count'] + (int) $row['open_doc_count'] + (int) $row['other_count']);
                $innerArray = [];
                $innerArray[] = $i;
                $innerArray[] = $row['customer_id'];
                $innerArray[] = $row['client_code'];
                $innerArray[] = $row['boxno'];
                $innerArray[] = $row['customer_name'];
                $innerArray[] = $row['address'];
                $innerArray[] = $row['area'];
                if (empty($row['cheque_count'])) {
                    $innerArray[] = 0;
                } else {
                    $innerArray[] = $row['cheque_count'];
                }
                if (empty($row['open_doc_count'])) {
                    $innerArray[] = 0;
                } else {
                    $innerArray[] = $row['open_doc_count'];
                }
                if (empty($row['other_count'])) {
                    $innerArray[] = 0;
                } else {
                    $innerArray[] = $row['other_count'];
                }
                if (empty($row['weight_in_kg'])) {
                    $innerArray[] = 0;
                } else {
                    $innerArray[] = $row['weight_in_kg'];
                }
                $innerArray[] = $row['pod_no'];
                $dataArray[] = $innerArray;
            }
            include("../../classes/PHPExcel/IOFactory.php");
            $file_name = 'submission_to_clinet-' . date('d-m-Y') . $client_name . '.xlsx';
            $dir_name = '../../../download/';
            $full_name = $dir_name . $file_name;
            if (!is_dir($dir_name)) {
                mkdir($dir_name, 0755);
            }
            if (file_exists($full_name)) {
                unlink($full_name);
            }
            // $objPHPExcel = new PHPExcel();
            // $color = new PHPExcel_Style_Color();
            $color->setRGB('800080');
            $objPHPExcel->getProperties()->setCreator("Me")->setLastModifiedBy("Me")->setTitle("My Excel Sheet")->setSubject("My Excel Sheet")->setDescription("Excel Sheet")->setKeywords("Excel Sheet")->setCategory("Me");
            $objPHPExcel->setActiveSheetIndex(0);
            $objPHPExcel->getActiveSheet()->setTitle('Submission to Client');

            $select = "SELECT client_name FROM `client_code_dbfc` cc INNER JOIN client_master c ON (cc.client_id=c.client_id) WHERE cc.client_code_id=$client_code_id";
            $statement = $connection->prepare($select);
            $statement->execute();
            $clients = $statement->fetch();
            $rowCount = $statement->rowCount();
            $statement->closeCursor();
            $client_name = '';
            if ($rowCount > 0) {
                $client_name = $clients['client_name'];
            }

            $objPHPExcel->getActiveSheet()->setCellValue('A1', 'Name of the Client : ');
            $objPHPExcel->getActiveSheet()->mergeCells('A1:C1');
            $objPHPExcel->getActiveSheet()->getStyle("A1:C1")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->setCellValue('E1', $client_name);
            $objPHPExcel->getActiveSheet()->mergeCells('E1:L1');
            $objPHPExcel->getActiveSheet()->setCellValue('A2', 'Date of Submission : ');
            $objPHPExcel->getActiveSheet()->mergeCells('A2:C2');
            $objPHPExcel->getActiveSheet()->getStyle("A2:C2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->setCellValue('E2', date('d-m-Y'));
            $objPHPExcel->getActiveSheet()->setCellValue('H2', 'Date of Submission : ');
            $objPHPExcel->getActiveSheet()->mergeCells('H2:J2');
            $objPHPExcel->getActiveSheet()->getStyle("H2:J2")->getFont()->setBold(true);
            $objPHPExcel->getActiveSheet()->setCellValue('L2', date('d-m-Y', strtotime($date)));
            $i = 3;
            foreach ($dataArray as $d) {
                ++$i;
                $col = "A";
                foreach ($d as $dd) {
                    $objPHPExcel->getActiveSheet()->setCellValue($col . $i, $dd);
                    ++$col;
                }
            }
            ++$i;
            ++$i;
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, "Total Files : " . $TotalFiles);
            ++$i;
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, "Total Packets : " . $TotalPackets);
            ++$i;
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, "Total Other Documents : " . $TotalOtherDoc);
            ++$i;
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, "Total Weights : " . $TotalWeight);
            ++$i;
            $objPHPExcel->getActiveSheet()->setCellValue('H' . $i, "Total Collection : " . $TotalCollections);

            $objPHPExcel->getActiveSheet()->getStyle("A4:L4")->getFont()->setBold(true);
            // $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
            try {
                $objWriter->save($full_name);
            } catch (Exception $ex) {
                // $errors['error'] = $ex . Message;
                $data['success'] = false;
                $data['errors'] = $errors;
            }
            if ($i > 0) {
                if ($_SERVER['HTTP_HOST'] == 'localhost') {
                    $file_name = 'http://localhost/download/' . $file_name;
                } else {
                    $file_name = 'https://www.bsaapps.co.in/download/' . $file_name;
                }
                $data['success'] = true;
                $data['data'] = $file_name;
            } else {
                $errors['error'] = "Unable To Export File!";
                $data['success'] = false;
                $data['errors'] = $errors;
            }
        } catch (Exception $ex) {
            $data['success'] = false;
            $errors['error'] = $ex->getMessage();
            $data['errors'] = $errors;
        }
        echo json_encode($data);
    }
}

