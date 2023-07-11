<?php
require_once '../model/problom.php';

$contactUs = new Problom();                        

$contactUs->conn->beginTransaction();

try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        print_r(!isset($_SERVER['REQUEST_METHOD']));exit;
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                // ADD UPDATE DELETE FETCH
                if (isset($request->action)) {
                    if ($request->action == 'add') {
                        @session_start();
                        $contactUs->contact_name = $request->contact_name;
                        $contactUs->email_id = $request->email_id;
                        $contactUs->contactMessage = $request->contactMessage;
                        $contactUs->mobile_no = $request->mobile_no;
                        
                        $contactUs->created_by = $_SESSION['hryS_user_id'];

                        // print_r($_SESSION);exit;

                        if ($contactUs->checkEmail() === true) {
                            // $contactUs->checkEmail() == $_SESSION['hryS_user_id'] && $contactUs->checkEmail() == $_SESSION['hryS_name']
                            if($contactUs->checkEmail() != $_SESSION['hryS_name']){

                                print_r('abc');exit;
                                $contactUs->insert();
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'Your User Request Has Been Accepted!'
                                ];
                                $contactUs->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            }else {
                                print_r(('ref'));exit;
                                  throw new Exception('Please Use Your UserID!', 400);                            
                            }
                        } else {
                            print_r(('def'));exit;
                              throw new Exception('Please Use Your UserID!', 400);                            
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
                        $contactUs->contactUs_id = $request->contactUs_id;
                        $contactUs->delete();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'User detail successfully deleted!'
                        ];
                        $contactUs->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } 
                    else if ($request->action == 'get') {
                        $result = [];
                        if (isset($request->contactUs_id) && $request->contactUs_id > 0) {
                            $contactUs->contactUs_id = $request->contactUs_id;
                        }
                        $results = $contactUs->get($request);
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
                                'contactUs_id'          => $res['contactUs_id'],
                                'contact_name'          => ucfirst($res['contact_name']),
                                'email_id'              => $res['email_id'],
                                'contactMessage'        => $res['contactMessage'],
                                'mobile_no'             => $res['mobile_no'],
                                'rqst_by'               => ucfirst($res['rqst_by']),
                                'action'                => "<a class='delete cursor-pointer text-danger' data-id='" . $res['contactUs_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $contactUs->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'User Detail Fetch Successfully!',
                            'data'              => $result
                        ];
                        $contactUs->conn->commit();
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
    http_response_code($e->getCode());
    echo json_encode($response);
}
