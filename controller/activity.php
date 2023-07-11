<?php 
require_once '../model/invoice.php';
require_once '../model/time_sheet.php';
require_once '../model/user.php';
require_once '../helper/date.php';
@session_start();
$login_user_id = isset($_SESSION["hryS_user_id"])?$_SESSION["hryS_user_id"]:0;
try {
    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST') {
        $json = file_get_contents('php://input');
        if(isset($json) && !empty($json)) {
            $request = json_decode($json);
            if(isset($request) && !empty($request)) {
                // ADD UPDATE DELETE FETCH GET_BY_ID PRINT_BILL FINAL_BILL
                if(isset($request->action)) {
                    $user = new user();
                    $time_sheet = new Time_sheet();
                    if($request->action=='fetch_user') {
                        $result=[];
                        $results = $user->get([]);
                        // print_r($results);exit;
                        $i=0;
                        foreach($results as $res) {
                            ++$i;
                            $result[] = [
                                'user_id'      => $res['user_id'],
                                'username'     => $res['username'],
                                'name'         => $res['name'],
                            ];
                        }
                        $response = [
                            'recordsTotal'      => count($results),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Case Lawyer Fetch Successfully!',
                            'data'              => $result
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else if($request->action=='fetch_activity') {
                        $result=[];
                        $time_sheet->start_time = $request->from_date;
                        $time_sheet->end_time = $request->to_date;
                        $time_sheet->user_id = $request->user_id;                     
                        $results = $time_sheet->fetch_activity($request->user_id);
                        // print_r($results);exit;
                        $i=0;
                        foreach($results as $res) {
                            ++$i;
                            if ($res['billable'] == 'Y') {
                                $billable = 'YES';
                            } else {
                                $billable = 'NO';
                            }
                            $start_time = date('Hi', strtotime($res['start_time']));
                            if ($start_time > 0) {
                                $start_time = date('H:i', strtotime($res['start_time']));
                            } else {
                                $start_time = "";
                            }
                            $end_time = date('Hi', strtotime($res['end_time']));
                            if ($end_time > 0) {
                                $end_time = date('H:i', strtotime($res['end_time']));
                            } else {
                                $end_time = "";
                            }
                            
                            $result[] = [
                                's_no'              => $i,
                                'time_sheet_id'     => $res['time_sheet_id'],
                                'date'              => date('d-m-Y', strtotime($res['start_time'])),
                                'start_time'        => $start_time,
                                'end_time'          => $end_time,
                                'case_no'           => $res['case_no'],
                                'task_type'         => $res['task_type'],
                                'fee_type'          => $res['fee_type'],
                                'name'              => $res['name'],
                                'client'              => $res['client_code'],
                                'case_id'           => $res['case_id'],
                                'task_id'           => $res['task_id'],
                                'amount'            => $res['amount'],
                                'total_time'        => $res['total_time'].' mins',
                                'description'       => $res['description'],
                                'billable'          => $billable
                            ];
                        }
                        $response = [
                            'recordsTotal'      => count($results),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Time Sheet Fetch Successfully!',
                            'data'              => $result
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else {
                        throw new Exception('Invalid action type',400);
                    }
                } else {
                    throw new Exception('action key missing in request body',400);
                }
                
            } else {
                throw new Exception('Invalid JSON',400);
            }
        } else {
            throw new Exception('Request body missing',400);
        }
    } else {
        throw new Exception('Invalid Request METHOD - METHOD must be POST',400);
    }
}catch(PDOException $e){
    $response =[
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code(500);
    echo json_encode($response);

}catch(Exception $e) {
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code($e->getCode());
    echo json_encode($response);
}
?>
