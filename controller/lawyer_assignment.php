<?php require_once '../model/lawyer_fee.php';
try {
    
    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST') {
        $json = file_get_contents('php://input');
        if(isset($json) && !empty($json)) {
            $request = json_decode($json);
            if(isset($request) && !empty($request)) {
                // ADD UPDATE DELETE FETCH
                if(isset($request->action)) {
                    $lawyer_fee = new lawyer_fee();
                    if($request->action=='add') {
                        $lawyer_fee->case_id = $request->case_id;
                        $lawyer_fee->user_id = $request->user_id;
                        $data = $request->data;
                        $output=[];
                        $i = 0;
                        foreach($data as $ele){
                            $lawyer_fee->task_id = $ele->task_id;
                            $check = $lawyer_fee->check();
                            if($check['status'] == true){
                                $lawyer_fee->type = $ele->type;
                                $lawyer_fee->fee = $ele->fee;
                                $lawyer_fee->insert();
                                $task_name = $lawyer_fee->check();
                                $output[$i] = [
                                    'msg'       => $task_name['msg'].' fee inserted successfully',
                                    'status'    => true 
                                ];
                            }else{
                                $output[$i] = [
                                    'msg' => 'Sorry '.$check['msg'].' fee is already exists',
                                    'status'    => false 
                                ];
                            }
                            $i++;
                        }
                        $response = [
                            'success'   => 1,
                            'code'      => 200,
                            'msg'       => 'Case Lawyer successfully added!',
                            'data'      => $output
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                        
                    } else if($request->action=='update') {
                        $lawyer_fee->lawyer_fee_id = $request->lawyer_fee_id;
                        $lawyer_fee->case_id = $request->case_id;
                        $lawyer_fee->user_id = $request->user_id;
                        $data = $request->data;
                        foreach($data as $ele){
                            $lawyer_fee->task_id = $ele->task_id;
                            $lawyer_fee->type = $ele->type;
                            $lawyer_fee->fee = $ele->fee;
                            $lawyer_fee->update();
                        }   $output[0] = [
                            'msg'       => 'Task fee succesfully updated',
                            'status'    => true 
                        ];
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Case Lawyer Update successfully!',
                                'data'      => $output
                            ];

                            http_response_code(200);
                            echo json_encode($response);
                    } else if($request->action=='delete') {
                        $lawyer_fee->lawyer_fee_id = $request->lawyer_fee_id;
                        $lawyer_fee->delete();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Case Lawyer successfully deleted!'
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    } else if($request->action=='get') {
                        $result = [];
                        if(isset($request->lawyer_fee_id) && $request->lawyer_fee_id>0) {
                            $lawyer_fee->lawyer_fee_id = $request->lawyer_fee_id;
                        }
                        if (isset($request->case_id) && $request->case_id>0 && isset($request->lawyer_id) && $request->lawyer_id>0) {
                            $lawyer_fee->case_id = $request->case_id;
                            $lawyer_fee->user_id = $request->lawyer_id;
                        }
                        if (isset($request->case_id) && $request->case_id>0 && isset($request->from) && $request->from=='time_sheet') {
                            @session_start();
                            $login_user_id = $_SESSION["hryS_user_id"];
                            $lawyer_fee->case_id = $request->case_id;
                            $lawyer_fee->user_id = $login_user_id;
                        }
                        // if (isset($request->case_id) && $request->case_id>0 && isset($request->from) && $request->from=='time_sheet') {
                        //     @session_start();
                        //     $login_user_id = $_SESSION["hryS_user_id"];
                        //     $lawyer_fee->case_id = $request->case_id;
                        //     $lawyer_fee->task_id = $request->task_id;
                        //     $lawyer_fee->user_id = $login_user_id;
                        // }
                        $results = $lawyer_fee->get($request);
                        if(isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i=0;
                            $request->draw=0;
                        }
                        foreach($results as $res) {
                            ++$i;
                            $result[] = [
                                's_no'               =>$i,
                                'lawyer_fee_id'    => $res['lawyer_fee_id'],
                                'user_id'          => $res['user_id'],
                                'case_id'          => $res['case_id'],
                                'case_no'          => $res['case_no'],
                                'name'             => $res['name'],
                                'fee'              => $res['fee'],
                                'type'             => $res['type'],
                                'task_id'          => $res['task_id'],
                                'task_type'        => $res['task_type'],
                                'task_description' => $res['task_description'],
                                'action'            => "<a class='edit cursor-pointer' data-id='".$res['lawyer_fee_id']."'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='".$res['lawyer_fee_id']."'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Case Lawyer Fetch Successfully!',
                            'data'              => $result
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else if($request->action=='fetch_by_case_id') {
                        $result = [];
                        if(isset($request->case_id) && $request->case_id>0) {
                            $lawyer_fee->case_id = $request->case_id;
                            $results = $lawyer_fee->fetch_by_case_id();
                            if(isset($request->start)) {
                                $i = $request->start;
                            } else {
                                $i=0;
                                $request->draw=0;
                            }
                            foreach($results as $res) {
                                ++$i;
                                $result[] = [
                                    'user_id'          => $res['user_id'],
                                    'name'             => $res['name'],
                                    'task_id'          => $res['task_id'],
                                    'task_type'        => $res['task_type']
                                ];
                            }

                            $response = [
                                'draw'              => intval($request->draw),
                                'recordsTotal'      => count($results),
                                'success'           => 1,
                                'code'              => 200,
                                'msg'               => 'Case Lawyer Fetch Successfully!',
                                'data'              => $result
                            ];
                            http_response_code(200);
                            echo json_encode($response);
                        }
                        
                    } else if($request->action=='fetch_by_lawyer_id') {
                        $result = [];
                        @session_start();
                        $login_user_id = $_SESSION["hryS_user_id"];
                        $lawyer_fee->user_id = $login_user_id;
                        $results = $lawyer_fee->fetch_by_lawyer_id();
                        if(isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i=0;
                            $request->draw=0;
                        }
                        foreach($results as $res) {
                            ++$i;
                            $result[] = [
                                'task_id'        => $res['task_id'],
                                'case_no'        => $res['case_no'],
                                'case_id'        => $res['case_id'],
                            ];
                        }

                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Case Lawyer Fetch Successfully!',
                            'data'              => $result
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else if($request->action=='validate_lawyer_fee') {
                        $lawyer_fee->case_id  = $request->case_id;
                        $lawyer_fee->user_id  = $request->lawyer_id;
                        $lawyer_fee->task_id  = $request->activity_type;
                        $validation = $lawyer_fee->validation_lawyer_fee();
                        $result = [
                            'status' => $validation['status']
                        ];
                        $response = [
                            'recordsTotal'      => count($validation),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Validaton request done',
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
