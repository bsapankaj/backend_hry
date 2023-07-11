<?php
require_once '../model/user_request.php';

$user_qst = new User_Request();                        

$user_qst->conn->beginTransaction();

try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                // ADD UPDATE DELETE FETCH
                if (isset($request->action)) {
                    if ($request->action == 'add') {
                        @session_start();
                        $user_qst->user_request_name = $request->user_request_name;
                        $user_qst->email_id = $request->email_id;
                        $user_qst->company_name = $request->company_name;
                        $user_qst->mobile_no = $request->mobile_no;
                        $user_qst->father_name = $request->father_name;
                        $user_qst->address = $request->address;
                        // print_r($user);exit;
                        if (isset($request->pincode) && !empty($request->pincode)) {
                            $user_qst->pincode = $request->pincode;
                        }
                        $user_qst->created_by = $_SESSION['hryS_user_id'];

                        if ($user_qst->check() === false && $user_qst->checkEmail() === false) {
                            // print_r('abc');exit;
                            $user_qst->insert();
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Your User Request Has Been Accepted!'
                            ];
                            $user_qst->conn->commit();
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            if($user_qst->check() === true){
                                //  print_r('xyz');exit;
                               throw new Exception('User Request Name Already Exists', 400);
                            }else{
                                // print_r('def');exit;
                                throw new Exception('User Request EmailID Already Exists', 400);
                            }
                        }
                    } 
                    // else if ($request->action == 'update') {
                    //     $user->user_id  = $request->user_id;
                    //     $user->name = $request->name;
                    //     $user->email_id = $request->email_id;
                    //     $user->company_name = $request->company_name;
                    //     $user->mobile_no = $request->mobile_no;
                    //     $user->user_type_id = $request->user_type_id;
                    //     $user->father_name = $request->father_name;
                    //     $user->address = $request->address;
                    //     if (isset($request->pincode) && !empty($request->pincode)) {
                    //         $user->pincode = $request->pincode;
                    //     }
                    //     $user->login_access = $request->login_access;
                    //     $user->update();
                    //     if ($request->login_access > 0) {
                    //         $user_login = new User_login();
                    //         $user_login->user_id = $request->user_id;
                    //         $user_login->username = $request->username;
                    //         $user_login->password = $request->password;
                    //         @session_start();
                    //         $user_login->created_by = $_SESSION['hryS_user_id'];
                    //         $user_login->updated_by = $_SESSION['hryS_user_id'];
                    //         $user_login->update();
                    //         $response = [
                    //             'success' => 1,
                    //             'code' => 200,
                    //             'msg' => 'User detail successfully added!'
                    //         ];
                    //         $user->conn->commit();
                    //         http_response_code(200);
                    //         echo json_encode($response);
                    //     }
                    // } 
                    else if ($request->action == 'delete') {
                        $user_qst->user_request_id = $request->user_request_id;
                        $user_qst->delete();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'User detail successfully deleted!'
                        ];
                        $user_qst->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } 
                    else if ($request->action == 'get') {
                        $result = [];
                        if (isset($request->user_request_id) && $request->user_request_id > 0) {
                            $user_qst->user_request_id = $request->user_request_id;
                        }
                        $results = $user_qst->get($request);
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        foreach ($results as $res) {
                            ++$i;
                            $result[] = [
                                's_no'                  => $i,
                                'user_request_id'       => $res['user_request_id'],
                                'user_request_name'     => ucfirst($res['user_request_name']),
                                'email_id'              => $res['email_id'],
                                'company_name'          => $res['company_name'],
                                'mobile_no'             => $res['mobile_no'],
                                'father_name'           => $res['father_name'],
                                'address'               => $res['address'],
                                'rqst_by'               => ucfirst($res['rqst_by']),
                                'pincode'               => $res['pincode'],
                                'action'                => "<a class='delete cursor-pointer text-danger' data-id='" . $res['user_request_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $user_qst->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'User Detail Fetch Successfully!',
                            'data'              => $result
                        ];
                        $user_qst->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else {
                        throw new Exception('Invalid action type', 400);
                    }
                } else {
                    throw new Exception('action key missing in request body', 400);
                }
            } else {
                throw new Exception('Invalid JSON', 400);
            }
        } else {
            throw new Exception('Request body missing', 400);
        }
    } else {
        throw new Exception('Invalid Request METHOD - METHOD must be POST', 400);
    }
} catch (PDOException $e) {
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code(500);
    echo json_encode($response);
} catch (Exception $e) {
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage(),
    ];
    // http_response_code($e->getCode());
    echo json_encode($response);
}
