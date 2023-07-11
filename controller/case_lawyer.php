<?php require_once '../model/case_lawyer.php';
try {
    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST') {
        $json = file_get_contents('php://input');
        if(isset($json) && !empty($json)) {
            $request = json_decode($json);
            if(isset($request) && !empty($request)) {
                // ADD UPDATE DELETE FETCH
                if(isset($request->action)) {
                    $case_lawyer = new Case_lawyer();
                    if($request->action=='add') {
                        $case_lawyer->case_id = $request->case_id;
                        $case_lawyer->user_id = $request->user_id;
                        $case_lawyer->per_hour_fee = $request->per_hour_fee;
                        if($case_lawyer->check() === false) {
                            $case_lawyer->insert();
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Case Lawyer successfully added!'
                            ];

                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('Case Lawyer Already Exists',400);
                        }
                    } else if($request->action=='update') {
                        $case_lawyer->case_lawyer_id = $request->case_lawyer_id;
                        $case_lawyer->case_id = $request->case_id;
                        $case_lawyer->user_id = $request->user_id;
                        $case_lawyer->per_hour_fee = $request->per_hour_fee;
                        $case_lawyer->update();
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Case Lawyer Update successfully!'
                            ];

                            http_response_code(200);
                            echo json_encode($response);
                    } else if($request->action=='delete') {
                        $case_lawyer->case_lawyer_id = $request->case_lawyer_id;
                        $case_lawyer->delete();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Case Lawyer successfully deleted!'
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    } else if($request->action=='get') {
                        $result = [];
                        if(isset($request->case_lawyer_id) && $request->case_lawyer_id>0) {
                            $case_lawyer->case_lawyer_id = $request->case_lawyer_id;
                        }
                        $results = $case_lawyer->get($request);
                        if(isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i=0;
                            $request->draw=0;
                        }
                        foreach($results as $res) {
                            ++$i;
                            $result[] = [
                                's_no'              => $i,
                                'case_lawyer_id'    => $res['case_lawyer_id'],
                                'case_id'           => $res['case_id'],
                                'case_no'           => $res['case_no'],
                                'name'              => $res['name'],
                                'user_id'           => $res['user_id'],
                                'per_hour_fee'      => $res['per_hour_fee'],
                                'action'            => "<a class='edit cursor-pointer' data-id='".$res['case_lawyer_id']."'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='".$res['case_lawyer_id']."'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $case_lawyer->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Case Lawyer Fetch Successfully!',
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
