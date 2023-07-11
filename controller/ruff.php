<?php

session_start();
require '../modal/db.php';
require '../../PHPMailer/PHPMailerAutoload.php';
require '../modal/suportFunctions.php';
$conn = new db();
$connection = $conn->connect();
$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// $userId = $_SESSION['bsa_group_user_id'];
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errors = array();
    $data = array();
         if (isset($_POST['type']) && $_POST['type'] == "client_vs_franchisee_expense_new") {
        $year_month = $_POST['expense_month'];
        $report_type = "all";
        $c_f = 0;
        try {
            $qry="SELECT BM.billcode,CM.client_shortname,CM.client_name,CC.client_code,
            MC.visit_particular as c_visit_particular,
            MC.no_of_branches as c_no_of_branches,
            MC.no_of_visits as c_no_of_visits,
            MC.rate as c_rate,
            (MC.lunch+MC.cake+MC.bouquet+MC.ot*MC.ot_rate) as c_misc_amount,
            MC.parking_amount as c_parking_amount,
            MC.mobile_amount as c_mobile_amount,
            MC.rate_per_km as c_rate_per_km,MC.working_days as working_days,
            MC.km_reading,MC.rate_per_km,(MC.km_reading*MC.rate_per_km) as petrol_amount,
            ((MC.km_reading*MC.rate_per_km) +MC.ot*MC.ot_rate+ MC.parking_amount +  MC.mobile_amount +(MC.lunch+MC.cake+MC.bouquet)+MC.rate) as c_ttl,
            (MF.km_reading * MF.rate_per_km) as f_pertol_amount,MF.parking_amount as f_parking_amount,
            MF.mobile_amount as f_mobile_amount,MF.conveyance_amount as f_conveyance_amount,
            (MF.lunch+MF.cake+MF.bouquet+MF.ot*MF.ot_rate) as f_misc_amount,
            ((MF.km_reading*MF.rate_per_km) +MF.ot*MF.ot_rate+ MF.parking_amount +  MF.mobile_amount +(MF.lunch+MF.cake+MF.bouquet)+MF.rate) as f_ttl,
            case MC.visit_type when 'P' then 'Visit' when 'M' then 'Month' end as c_visit_type,
            case MC.visit_type WHEN 'P' THEN ((MC.no_of_branches * MC.no_of_visits * MC.working_days) * MC.rate) WHEN 'M' THEN ((MC.no_of_branches * MC.no_of_visits) * MC.rate) END as c_testing_amt,
            case MF.visit_type when 'P' then 'Visit' when 'M' then 'Month' end as f_visit_type,
            case MF.visit_type WHEN 'P' THEN ((MF.no_of_branches * MF.no_of_visits *MF.working_days)*MF.rate) WHEN 'M' THEN ((MF.no_of_branches * MF.no_of_visits)*MF.rate) END as f_testing_amt,
            FM.franchisee_name,MF.visit_particular as f_visit_particular,
            MF.km_reading as f_km_reading,MF.rate_per_km as f_rate_per_km,(MF.km_reading*MF.rate_per_km) as f_petrol_amount,
            MF.no_of_branches as f_no_of_branches,MF.no_of_visits as f_no_of_visits,MF.rate as f_rate,
            MF.rate as f_amount,MF.rate_per_km as f_rate_per_km,FE.fe_name -- ,MC.*,MF.*,CC.*,CM.*,BM.*,V.*,FE.* 
            FROM monthly_compile_data_client_fv MC
            LEFT OUTER JOIN monthly_compile_data_franchisee_fv MF ON MF.visit_detail_id=MC.visit_detail_id AND MF.expense_month=MC.expense_month
            INNER JOIN franchisee_master FM ON FM.franchisee_id=MF.franchisee_id
            INNER JOIN client_code_fv CC ON CC.client_code_id=MC.client_code_id and (CC.end_date is null or  date_format(CC.end_date,'%Y-%m') >= date_format(:year_month,'%Y-%m') and date_format(CC.start_date,'%Y-%m') <= date_format(:year_month,'%Y-%m'))
            INNER JOIN client_master CM ON CM.client_id=CC.client_id
            INNER JOIN billcode_master BM ON BM.billcode_id=CC.bill_code_id
            INNER JOIN visit_detail V ON V.visit_detail_id=MC.visit_detail_id
            INNER JOIN fe_master FE ON FE.fe_id=MF.fe_id
            WHERE MC.expense_month=:year_month -- AND MC.final_closer='Y';
            order by BM.billcode,CC.client_code,FM.franchisee_name,MF.rate";
            $statement = $connection->prepare( $qry);
            $statement->bindValue(":year_month", $year_month);
            $statement->execute();
            print_r($statement); exit;
            $result = $statement->fetchAll();
            // print_r($result); exit;
        //    echo json_encode($result);exit;
            $rowCount = $statement->rowCount();
            // print_r($rowCount); exit;
            $statement->closeCursor();
            if ($rowCount > 0) {
                include("../../classes/PHPExcel/IOFactory.php");
                $file_name = 'Comparison_Chart_Final.xlsx';
                $dir_name = '../../../download/';
                $full_name = $dir_name . $file_name;
                if (!is_dir($dir_name)) {
                    // Return canonicalized absolute pathname Make Directory
                    mkdir($dir_name, 0777);
                }
                if (file_exists($full_name)) {
                    // Delete the Existing File
                    unlink($full_name);
                }
                $objPHPExcel = new PHPExcel();
// Set document properties
                $color = new PHPExcel_Style_Color();
                $color->setRGB('800080');
                $objPHPExcel->getProperties()->setCreator("Me")->setLastModifiedBy("Me")->setTitle("My Excel Sheet")->setSubject("My Excel Sheet")->setDescription("Excel Sheet")->setKeywords("Excel Sheet")->setCategory("Me");
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
                $objPHPExcel->setActiveSheetIndex(0);
// Add column headers for First Sheet
                $ctr = 9;
                $objPHPExcel->getActiveSheet()->setTitle('Comparison Chart')
                        ->setCellValue('A1', 'BSA LOGISTICS PVT. LTD.')
                        ->setCellValue('A2', '1301, VIKRANT TOWER , 13TH FLOOR, 4,RAJENDRA PLACE NEW DELHI 110008')
                        ->setCellValue('A3', 'Phone : 011-66098316,011-45416367')
                        ->setCellValue('A4', '')
                        ->setCellValue('A5', 'Monthly Active Visit Status Chart for the Month ' . date('F-Y', strtotime($year_month)) . '  (Comparison - Fixed Visit)')
                        ->setCellValue('A6', 'Report Processed on ' . date('d-m-Y'))
                        // Row 8 two parts 
                        ->setCellValue('A8', 'Client Bill(s)')
                        ->setCellValue('N8', 'Franchisee Bill(s)')
                        //Row 9 table headings
                        ->setCellValue('A9', 'S No')
                        ->setCellValue('B9', 'Bill Code')
                        ->setCellValue('C9', 'Client Name')
                        ->setCellValue('D9', 'Client Code')
                        ->setCellValue('E9', 'Visit Particular')
                        ->setCellValue('F9', 'Amount')
                        ->setCellValue('G9', 'Petrol KM')
                        ->setCellValue('H9', 'Rate / KM')
                        ->setCellValue('I9', 'Petrol Amount')
                        ->setCellValue('J9', 'Parking Amount')
                        ->setCellValue('K9', 'Mobile Amount')
                        ->setCellValue('L9', 'Misc Amount')
                        ->setCellValue('M9', 'Total Amount')
                        ->setCellValue('N9', 'Franchisee Name')
                        ->setCellValue('O9', 'Visit Particular')
                        ->setCellValue('P9', 'Amount')
                        ->setCellValue('Q9', 'Petrol KM')
                        ->setCellValue('R9', 'Rate / KM')
                        ->setCellValue('S9', 'Petrol Amount')
                        ->setCellValue('T9', 'Parking Amount')
                        ->setCellValue('U9', 'Mobile Amount')
                        ->setCellValue('V9', 'Misc Amount')
                        ->setCellValue('W9', 'Total Amount')
                        ->setCellValue('X9', 'Diff.Amt');    
                        $objPHPExcel->getActiveSheet()->getStyle('A9:X9')->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(0)->setWidth('4.42');
                        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(4)->setWidth('25');
                        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(13)->setWidth('10');
                        $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn(14)->setWidth('25');
                 $i = 0;
                 $client_grand_total = 0;
                 $franchisee_grand_total = 0;
                 $bill_code_prev = "";
                 $c_sub_total = 0;
                 $f_sub_total = 0;
                 $d_sub_total =0;
                 foreach ($result as $row) {
                     ++$i;
                     $client_grand_total += $row['c_testing_amt'];
                     $franchisee_grand_total += $row['f_testing_amt'];
                     if ($i == 1) {
                         // client 
                         $objPHPExcel->getActiveSheet()->setCellValue('A' . ($ctr + 1), $i);
                         $objPHPExcel->getActiveSheet()->setCellValue('B' . ($ctr + 1), $row['billcode']);
                        $objPHPExcel->getActiveSheet()->setCellValue('C' . ($ctr + 1), $row['client_name']);
                        $objPHPExcel->getActiveSheet()->setCellValue('D' . ($ctr + 1), $row['client_code']);
                        if ($row['c_visit_type'] == "Visit") {
                            $objPHPExcel->getActiveSheet()->setCellValue('E' . ($ctr + 1), $row['c_visit_particular'] . "\n (@" . $row['c_rate'] . "*" . $row['c_no_of_branches'] . "*" . $row['c_no_of_visits'] . "*" . $row['working_days'] . ")/" . $row['c_visit_type']);
                        } else if ($row['c_visit_type'] == "Month") {
                            $objPHPExcel->getActiveSheet()->setCellValue('E' . ($ctr + 1), $row['c_visit_particular'] . "\n (@" . $row['c_rate'] . "*" . $row['c_no_of_branches'] . "*" . $row['c_no_of_visits'] . ")/" . $row['c_visit_type']);
                        }
                        $objPHPExcel->getActiveSheet()->getStyle('E' . ($ctr + 1))->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->setCellValue('F' . ($ctr + 1), $row['c_testing_amt']);
                        $objPHPExcel->getActiveSheet()->setCellValue('G' . ($ctr + 1), $row['km_reading']);
                        $objPHPExcel->getActiveSheet()->setCellValue('H' . ($ctr + 1), $row['c_rate_per_km']);
                        $objPHPExcel->getActiveSheet()->setCellValue('I' . ($ctr + 1), $row['petrol_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('J' . ($ctr + 1), $row['c_parking_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('K' . ($ctr + 1), $row['c_mobile_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('L' . ($ctr + 1), $row['c_misc_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . ($ctr + 1), $row['c_ttl']);
                        
                        // franchisee
                        $objPHPExcel->getActiveSheet()->setCellValue('N' . ($ctr + 1), $row['franchisee_name'] . '(' . $row['fe_name'] . ')');
                        $objPHPExcel->getActiveSheet()->getStyle('N' . ($ctr + 1))->getAlignment()->setWrapText(true);
                        if ($row['f_visit_type'] == "Visit") {
                            $objPHPExcel->getActiveSheet()->setCellValue('O' . ($ctr + 1), $row['f_visit_particular'] . "\n(@" . $row['f_rate'] . "*" . $row['f_no_of_branches'] . "*" . $row['f_no_of_visits'] . "*" . $row['working_days'] . ")/" . $row['f_visit_type']);
                        } else if ($row['f_visit_type'] == "Month") {
                           $objPHPExcel->getActiveSheet()->setCellValue('O' . ($ctr + 1), $row['f_visit_particular'] . "\n(@" . $row['f_rate'] . "*" . $row['f_no_of_branches'] . "*" . $row['f_no_of_visits'] . ")/" . $row['f_visit_type']);
                        }
                        $objPHPExcel->getActiveSheet()->getStyle('O' . ($ctr + 1))->getAlignment()->setWrapText(true);

                        $objPHPExcel->getActiveSheet()->setCellValue('P' . ($ctr + 1), $row['f_testing_amt']);
                        $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($ctr + 1), $row['f_km_reading']);
                        $objPHPExcel->getActiveSheet()->setCellValue('R' . ($ctr + 1), $row['f_rate_per_km']);
                        $objPHPExcel->getActiveSheet()->setCellValue('S' . ($ctr + 1), $row['f_petrol_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('T' . ($ctr + 1), $row['f_parking_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('U' . ($ctr + 1), $row['f_mobile_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('V' . ($ctr + 1), $row['f_misc_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('W' . ($ctr + 1), $row['f_ttl']);
                        $objPHPExcel->getActiveSheet()->setCellValue('X' . ($ctr + 1), ($row['c_ttl']-$row['f_ttl']));


                      
                        $c_sub_total += $row['c_ttl'];
                        $f_sub_total += $row['f_ttl'];
                        $d_sub_total += $row['c_ttl']-$row['f_ttl'];
                    } else if ($bill_code_prev == $row['billcode']) {
                        if ($client_code == $row['client_code']) {
                            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($ctr + 1), $i);
                            $objPHPExcel->getActiveSheet()->setCellValue('B' . ($ctr + 1), '-');
                            $objPHPExcel->getActiveSheet()->setCellValue('C' . ($ctr + 1), '-');
                            $objPHPExcel->getActiveSheet()->setCellValue('D' . ($ctr + 1), '-');
                            $objPHPExcel->getActiveSheet()->setCellValue('E' . ($ctr + 1), '-');
                            $objPHPExcel->getActiveSheet()->setCellValue('F' . ($ctr + 1), '-');
                            $objPHPExcel->getActiveSheet()->setCellValue('G' . ($ctr + 1), '-');
                            $objPHPExcel->getActiveSheet()->setCellValue('H' . ($ctr + 1), '-');
                            $objPHPExcel->getActiveSheet()->setCellValue('I' . ($ctr + 1), '-');
                            $objPHPExcel->getActiveSheet()->setCellValue('J' . ($ctr + 1), '-');
                            $objPHPExcel->getActiveSheet()->setCellValue('K' . ($ctr + 1), '-');
                            $objPHPExcel->getActiveSheet()->setCellValue('L' . ($ctr + 1), '-');
                            $objPHPExcel->getActiveSheet()->setCellValue('M' . ($ctr + 1), '-');
                            $client_grand_total += (-$row['c_testing_amt']);
                            $row['c_ttl']=0;
                            // $c_sub_total += $row['c_ttl'];
                             $f_sub_total += $row['f_ttl'];
                             $d_sub_total += $row['c_ttl']-$row['f_ttl'];
                        
                            
                        } else {
                            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($ctr + 1), $i);
                            $objPHPExcel->getActiveSheet()->setCellValue('B' . ($ctr + 1), $row['billcode']);
                            $objPHPExcel->getActiveSheet()->setCellValue('C' . ($ctr + 1), $row['client_name']);
                            $objPHPExcel->getActiveSheet()->setCellValue('D' . ($ctr + 1), $row['client_code']);
                            if ($row['c_visit_type'] == "Visit") {
                                $objPHPExcel->getActiveSheet()->setCellValue('E' . ($ctr + 1), $row['c_visit_particular'] . "\n (@" . $row['c_rate'] . "*" . $row['c_no_of_branches'] . "*" . $row['c_no_of_visits'] . "*" . $row['working_days'] . ")/" . $row['c_visit_type']);
                            } else if ($row['c_visit_type'] == "Month") {
                                $objPHPExcel->getActiveSheet()->setCellValue('E' . ($ctr + 1), $row['c_visit_particular'] . "\n (@" . $row['c_rate'] . "*" . $row['c_no_of_branches'] . "*" . $row['c_no_of_visits'] . ")/" . $row['c_visit_type']);
                            }
                            $objPHPExcel->getActiveSheet()->getStyle('E' . ($ctr + 1))->getAlignment()->setWrapText(true);
                            
                            $c_sub_total += $row['c_ttl'];
                            $f_sub_total += $row['f_ttl'];
                            $d_sub_total += $row['c_ttl']-$row['f_ttl'];
                           $objPHPExcel->getActiveSheet()->setCellValue('F' . ($ctr + 1), $row['c_testing_amt']);
                           $objPHPExcel->getActiveSheet()->setCellValue('G' . ($ctr + 1), $row['km_reading']);
                           $objPHPExcel->getActiveSheet()->setCellValue('H' . ($ctr + 1), $row['c_rate_per_km']);
                           $objPHPExcel->getActiveSheet()->setCellValue('I' . ($ctr + 1), $row['petrol_amount']);
                           $objPHPExcel->getActiveSheet()->setCellValue('J' . ($ctr + 1), $row['c_parking_amount']);
                      if ($row['f_visit_type'] == "Visit") {
                       $objPHPExcel->getActiveSheet()->setCellValue('O' . ($ctr + 1), $row['f_visit_particular'] . "\n(@" . $row['f_rate'] . "*" . $row['f_no_of_branches'] . "*" . $row['f_no_of_visits'] . "*" . $row['working_days'] . ")/" . $row['f_visit_type']);
                   } else if ($row['f_visit_type'] == "Month") {
                      $objPHPExcel->getActiveSheet()->setCellValue('O' . ($ctr + 1), $row['f_visit_particular'] . "\n(@" . $row['f_rate'] . "*" . $row['f_no_of_branches'] . "*" . $row['f_no_of_visits'] . ")/" . $row['f_visit_type']);
                   }
                   $objPHPExcel->getActiveSheet()->getStyle('O' . ($ctr + 1))->getAlignment()->setWrapText(true);
                       $objPHPExcel->getActiveSheet()->setCellValue('K' . ($ctr + 1), $row['c_mobile_amount']);
                       $objPHPExcel->getActiveSheet()->setCellValue('L' . ($ctr + 1), $row['c_misc_amount']);
                       $objPHPExcel->getActiveSheet()->setCellValue('M' . ($ctr + 1), $row['c_ttl']);
                        }
                      
                          
                        $objPHPExcel->getActiveSheet()->setCellValue('N' . ($ctr + 1), $row['franchisee_name'] . '(' . $row['fe_name'] . ')');
                        $objPHPExcel->getActiveSheet()->getStyle('N' . ($ctr + 1))->getAlignment()->setWrapText(true);
                        

                        $objPHPExcel->getActiveSheet()->setCellValue('P' . ($ctr + 1), $row['f_testing_amt']);
                        $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($ctr + 1), $row['f_km_reading']);
                        $objPHPExcel->getActiveSheet()->setCellValue('R' . ($ctr + 1), $row['f_rate_per_km']);
                        $objPHPExcel->getActiveSheet()->setCellValue('S' . ($ctr + 1), $row['f_petrol_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('T' . ($ctr + 1), $row['f_parking_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('U' . ($ctr + 1), $row['f_mobile_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('V' . ($ctr + 1), $row['f_misc_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('W' . ($ctr + 1), $row['f_ttl']);
                        $objPHPExcel->getActiveSheet()->setCellValue('X' . ($ctr + 1), ($row['c_ttl']-$row['f_ttl']));
                       // $c_sub_total += $row['c_ttl'];
                        //$f_sub_total += $row['f_ttl'];
                       // $d_sub_total += $row['c_ttl']-$row['f_ttl'];
                    } else {
                       
                        $ctr++;
                        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($ctr), "Sub Total");
                        //$objPHPExcel->getActiveSheet()->setCellValue('G' . ($ctr), '=SUM(G10:G'.($ctr-1).')');
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . ($ctr), $c_sub_total);
                        //$objPHPExcel->getActiveSheet()->setCellValue('E10','=SUM(A10:E9)');
                        $objPHPExcel->getActiveSheet()->setCellValue('W' . ($ctr), $f_sub_total);
                        $objPHPExcel->getActiveSheet()->setCellValue('X' . ($ctr), $d_sub_total);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . ($ctr) . ':X' . ($ctr))->getFont()->setBold(true);
                       
                        $ctr++;
                        // Expense vs sale percent 
                        if($c_sub_total!=0){
                           $per= ($f_sub_total/$c_sub_total)*100;
                           $per=round( $per,2);
                        }
                        else
                        {
                            $per="NA";

                        }
                        $objPHPExcel->getActiveSheet()->setCellValue('W' . ($ctr), $per);
                        $objPHPExcel->getActiveSheet()->setCellValue('A' . ($ctr), "Expenses Vs Sales %");
                        $objPHPExcel->getActiveSheet()->mergeCells('A' . ($ctr) . ':V' . ($ctr));
                        $objPHPExcel->getActiveSheet()->mergeCells('W' . ($ctr) . ':X' . ($ctr));
                        $objPHPExcel->getActiveSheet()->getStyle('A' . ($ctr) . ':X' . ($ctr))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . ($ctr) . ':X' . ($ctr))->getFont()->setBold(true);
                        $c_sub_total = 0;
                        $f_sub_total = 0;
                        $d_sub_total = 0;

                         // client 
                         $objPHPExcel->getActiveSheet()->setCellValue('A' . ($ctr + 1), $i);
                         $objPHPExcel->getActiveSheet()->setCellValue('B' . ($ctr + 1), $row['billcode']);
                        $objPHPExcel->getActiveSheet()->setCellValue('C' . ($ctr + 1), $row['client_name']);
                        $objPHPExcel->getActiveSheet()->setCellValue('D' . ($ctr + 1), $row['client_code']);
                        if ($row['c_visit_type'] == "Visit") {
                            $objPHPExcel->getActiveSheet()->setCellValue('E' . ($ctr + 1), $row['c_visit_particular'] . "\n (@" . $row['c_rate'] . "*" . $row['c_no_of_branches'] . "*" . $row['c_no_of_visits'] . "*" . $row['working_days'] . ")/" . $row['c_visit_type']);
                        } else if ($row['c_visit_type'] == "Month") {
                            $objPHPExcel->getActiveSheet()->setCellValue('E' . ($ctr + 1), $row['c_visit_particular'] . "\n (@" . $row['c_rate'] . "*" . $row['c_no_of_branches'] . "*" . $row['c_no_of_visits'] . ")/" . $row['c_visit_type']);
                        }
                        $objPHPExcel->getActiveSheet()->getStyle('E' . ($ctr + 1))->getAlignment()->setWrapText(true);
                        $objPHPExcel->getActiveSheet()->setCellValue('F' . ($ctr + 1), $row['c_testing_amt']);
                        $objPHPExcel->getActiveSheet()->setCellValue('G' . ($ctr + 1), $row['km_reading']);
                        $objPHPExcel->getActiveSheet()->setCellValue('H' . ($ctr + 1), $row['c_rate_per_km']);
                        $objPHPExcel->getActiveSheet()->setCellValue('I' . ($ctr + 1), $row['petrol_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('J' . ($ctr + 1), $row['c_parking_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('K' . ($ctr + 1), $row['c_mobile_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('L' . ($ctr + 1), $row['c_misc_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . ($ctr + 1), $row['c_ttl']);
                        
                        // franchisee
                        $objPHPExcel->getActiveSheet()->setCellValue('N' . ($ctr + 1), $row['franchisee_name'] . '(' . $row['fe_name'] . ')');
                        $objPHPExcel->getActiveSheet()->getStyle('N' . ($ctr + 1))->getAlignment()->setWrapText(true);
                        if ($row['f_visit_type'] == "Visit") {
                            $objPHPExcel->getActiveSheet()->setCellValue('O' . ($ctr + 1), $row['f_visit_particular'] . "\n(@" . $row['f_rate'] . "*" . $row['f_no_of_branches'] . "*" . $row['f_no_of_visits'] . "*" . $row['working_days'] . ")/" . $row['f_visit_type']);
                        } else if ($row['f_visit_type'] == "Month") {
                           $objPHPExcel->getActiveSheet()->setCellValue('O' . ($ctr + 1), $row['f_visit_particular'] . "\n(@" . $row['f_rate'] . "*" . $row['f_no_of_branches'] . "*" . $row['f_no_of_visits'] . ")/" . $row['f_visit_type']);
                        }
                        $objPHPExcel->getActiveSheet()->getStyle('O' . ($ctr + 1))->getAlignment()->setWrapText(true);

                        $objPHPExcel->getActiveSheet()->setCellValue('P' . ($ctr + 1), $row['f_testing_amt']);
                        $objPHPExcel->getActiveSheet()->setCellValue('Q' . ($ctr + 1), $row['f_km_reading']);
                        $objPHPExcel->getActiveSheet()->setCellValue('R' . ($ctr + 1), $row['f_rate_per_km']);
                        $objPHPExcel->getActiveSheet()->setCellValue('S' . ($ctr + 1), $row['f_petrol_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('T' . ($ctr + 1), $row['f_parking_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('U' . ($ctr + 1), $row['f_mobile_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('V' . ($ctr + 1), $row['f_misc_amount']);
                        $objPHPExcel->getActiveSheet()->setCellValue('W' . ($ctr + 1), $row['f_ttl']);
                        $objPHPExcel->getActiveSheet()->setCellValue('X' . ($ctr + 1), ($row['c_ttl']-$row['f_ttl']));


                        $c_sub_total += $row['c_ttl'];
                        $f_sub_total += $row['f_ttl'];
                        $d_sub_total += $row['c_ttl']-$row['f_ttl'];
                        
                       
                    }
                    $bill_code_prev = $row['billcode'];
                    $client_code = $row['client_code'];
                    $ctr++;
                }
                
                if ($ctr > 9) {
                    $ctr = $ctr + 1;
                    $objPHPExcel->getActiveSheet()->setCellValue('E' . ($ctr), "Sub Total");             
                    $objPHPExcel->getActiveSheet()->setCellValue('M' . ($ctr), $c_sub_total);
                    $objPHPExcel->getActiveSheet()->setCellValue('W' . ($ctr), $f_sub_total);
                    $objPHPExcel->getActiveSheet()->setCellValue('X' . ($ctr), $d_sub_total);      
                    $objPHPExcel->getActiveSheet()->getStyle('A' . ($ctr) . ':X' . ($ctr))->getFont()->setBold(true);
                    $ctr++;
                        // Expense vs sale percent 
                        if($c_sub_total!=0){
                           $per= ($f_sub_total/$c_sub_total)*100;
                           $per=round( $per,2);
                        }
                        else
                        {
                            $per="NA";

                        }
                        $objPHPExcel->getActiveSheet()->setCellValue('W' . ($ctr), $per);
                        $objPHPExcel->getActiveSheet()->setCellValue('A' . ($ctr), "Expenses Vs Sales %");
                        $objPHPExcel->getActiveSheet()->mergeCells('A' . ($ctr) . ':V' . ($ctr));
                        $objPHPExcel->getActiveSheet()->mergeCells('W' . ($ctr) . ':X' . ($ctr));
                        $objPHPExcel->getActiveSheet()->getStyle('A' . ($ctr) . ':X' . ($ctr))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                        $objPHPExcel->getActiveSheet()->getStyle('A' . ($ctr) . ':X' . ($ctr))->getFont()->setBold(true);
                        $c_sub_total = 0;
                        $f_sub_total = 0;
                        $d_sub_total = 0;

                        // Grand Total 
                        $ctr++;
                        $objPHPExcel->getActiveSheet()->mergeCells('A' . ($ctr) . ':X' . ($ctr));
                        $ctr = $ctr + 1;
                        $objPHPExcel->getActiveSheet()->setCellValue('E' . ($ctr), "Grand Total");             
                        $objPHPExcel->getActiveSheet()->setCellValue('M' . ($ctr), $client_grand_total);
                        $objPHPExcel->getActiveSheet()->setCellValue('W' . ($ctr),  $franchisee_grand_total);
                        $objPHPExcel->getActiveSheet()->setCellValue('X' . ($ctr), $client_grand_total - $franchisee_grand_total);      
                        $objPHPExcel->getActiveSheet()->getStyle('A' . ($ctr) . ':X' . ($ctr))->getFont()->setBold(true);
                      
                        $ctr++;
                            // Expense vs sale percent 
                            if( $client_grand_total!=0){
                               $per= ( $franchisee_grand_total/ $client_grand_total)*100;
                               $per=round( $per,2);
                            }
                            else
                            {
                                $per="NA";
    
                            }
                            $objPHPExcel->getActiveSheet()->setCellValue('W' . ($ctr), $per);
                            $objPHPExcel->getActiveSheet()->setCellValue('A' . ($ctr), "Over all Expenses Vs Sales %");
                            $objPHPExcel->getActiveSheet()->mergeCells('A' . ($ctr) . ':V' . ($ctr));
                            $objPHPExcel->getActiveSheet()->mergeCells('W' . ($ctr) . ':X' . ($ctr));
                            $objPHPExcel->getActiveSheet()->getStyle('A' . ($ctr) . ':X' . ($ctr))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                            $objPHPExcel->getActiveSheet()->getStyle('A' . ($ctr) . ':X' . ($ctr))->getFont()->setBold(true);
                    
                    // $objPHPExcel->getActiveSheet()->setCellValue('D' . ($ctr + 1), "Expenses Vs Sales %");
                    // $objPHPExcel->getActiveSheet()->setCellValue('E' . ($ctr + 1), ($franchisee_grand_total / $client_grand_total) * 100);
                    // $objPHPExcel->getActiveSheet()->setCellValue('F' . ($ctr + 1), "Grand Total");
                    // $objPHPExcel->getActiveSheet()->setCellValue('G' . ($ctr + 1), $client_grand_total);
                    // $objPHPExcel->getActiveSheet()->setCellValue('K' . ($ctr + 1), $franchisee_grand_total);
                    // $objPHPExcel->getActiveSheet()->setCellValue('J' . ($ctr + 3), "Difference");
                    // $objPHPExcel->getActiveSheet()->setCellValue('K' . ($ctr + 3), $client_grand_total - $franchisee_grand_total);
                    // $objPHPExcel->getActiveSheet()->getStyle('A' . ($ctr + 1) . ':L' . ($ctr + 1))->getFont()->setBold(true);
                    // $objPHPExcel->getActiveSheet()->getStyle('A' . ($ctr + 3) . ':L' . ($ctr + 3))->getFont()->setBold(true);
                  //  $ctr = $ctr + 2;
                }
               
             
                $objPHPExcel->getActiveSheet()->getStyle("A1:X9")->getFont()->setBold(true);
                $objPHPExcel->getActiveSheet()->getStyle("A9:X9")->getFont()->setColor($color);
                $objPHPExcel->getActiveSheet()->mergeCells('A1:X1');
                $objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->mergeCells('A2:X2');
                $objPHPExcel->getActiveSheet()->getStyle('A2:X2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->mergeCells('A3:X3');
                $objPHPExcel->getActiveSheet()->getStyle('A3:X3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->mergeCells('A4:X4');
                $objPHPExcel->getActiveSheet()->getStyle('A4:X4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->mergeCells('A5:X5');
                $objPHPExcel->getActiveSheet()->getStyle('A5:X5')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->mergeCells('A6:X6');
                $objPHPExcel->getActiveSheet()->getStyle('A6:X6')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->mergeCells('A7:X7');
                $objPHPExcel->getActiveSheet()->getStyle('A7:X7')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->mergeCells('A8:M8');
                $objPHPExcel->getActiveSheet()->getStyle('A8:M8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                $objPHPExcel->getActiveSheet()->mergeCells('N8:X8');
                $objPHPExcel->getActiveSheet()->getStyle('N8:X8')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                //$border_style= array('borders' => array('right' => array('style' => PHPExcel_Style_Border::BORDER_THICK,'color' => array('argb' => '000000'),)));
                $objPHPExcel->getActiveSheet()->getStyle("A1:X" . $ctr)->applyFromArray(array(
                    'borders' => array(
                        'allborders' => array(
                            'style' => PHPExcel_Style_Border::BORDER_THIN,
                            'color' => array('rgb' => '000000')
                            
                        )
                        
                        ),
                        'font' => [
                            'size' => 8
                        ]
                ));
                // PROTECTION 
                // $objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
                // $objPHPExcel->getActiveSheet()->getProtection()->setSort(true);
                // $objPHPExcel->getActiveSheet()->getProtection()->setInsertRows(true);
                // $objPHPExcel->getActiveSheet()->getProtection()->setFormatCells(true);
                // $objPHPExcel->getActiveSheet()->getProtection()->setPassword('bsa@123');
                $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                try {
                    $objWriter->save($full_name);
                } catch (Exception $ex) {
                    $errors['error'] = $ex . Message;
                    $data['success'] = false;
                    $data['errors'] = $errors;
                    echo json_encode($data);
                }
                if ($ctr > 0) {
                    if ($_SERVER['HTTP_HOST'] == 'localhost:81') {
                        $file_name = 'http://localhost:81/download/' . $file_name;
                    } else if ($_SERVER['HTTP_HOST'] == 'localhost') {
                        $file_name = 'http://localhost/download/' . $file_name;
                    } else if ($_SERVER['HTTP_HOST'] == "192.168.1.76") {
                        $file_name = 'http://192.168.1.76/download/' . $file_name;
                    } else {
                        $file_name = 'https://www.bsaapps.co.in/download/' . $file_name;
                    }
                    $data['success'] = true;
                    $data['data'] = $file_name;
                    sleep(5);
                }
            } else {
                $errors['error'] = "No Record Found!";
                $data['success'] = false;
                $data['errors'] = $errors;
            }
        } catch (PDOException $ex) {
            $errors['error'] = $ex->getMessage() . " Line: " . $ex->getLine();
            $data['success'] = false;
            $data['errors'] = $errors;
        } catch (Exception $ex) {
            $errors['error'] = $ex->getMessage() . " Line: " . $ex->getLine();
            $data['success'] = false;
            $data['errors'] = $errors;
        }
        echo json_encode($data);
    }
}

