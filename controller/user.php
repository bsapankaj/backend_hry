<?php
require_once '../model/user.php';
require_once '../model/user_login.php';

$user = new User();

$user->conn->beginTransaction();

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
                        $user->name = $request->name;
                        $user->email_id = $request->email_id;
                        $user->company_name = $request->company_name;
                        $user->mobile_no = $request->mobile_no;
                        $user->user_type_id = $request->user_type_id;
                        $user->father_name = $request->father_name;
                        $user->address = $request->address;
                        // print_r($user);exit;
                        if (isset($request->pincode) && !empty($request->pincode)) {
                            $user->pincode = $request->pincode;
                        }
                        $user->login_access = $request->login_access;
                        $user->created_by = $_SESSION['hryS_user_id'];

                        if ($user->check() === false) {
                            $user->insert();
                            if ($request->login_access == 1) {
                                $user_login = new User_login();
                                $user_login->user_id = $user->user_id;
                                $user_login->username = $request->username;
                                $user_login->password = $request->password;
                                $user_login->created_by = $_SESSION['hryS_user_id'];
                                $user_login->updated_by = $_SESSION['hryS_user_id'];
                                if ($user_login->check() === false) {
                                    if ($user_login->check_username() === false) {
                                        $user_login->insert();
                                        $response = [
                                            'success' => 1,
                                            'code' => 200,
                                            'msg' => 'User detail successfully added!'
                                        ];
                                        $user->conn->commit();
                                        http_response_code(200);
                                        echo json_encode($response);
                                    } else {
                                        throw new Exception("Username already exists, please choose another name", 400);
                                    }
                                }
                            } else {
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'User detail successfully added!'
                                ];
                                $user->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            }
                        } else {
                            throw new Exception('User Name Already Exists', 400);
                        }
                    } else if ($request->action == 'update') {
                        $user->user_id  = $request->user_id;
                        $user->name = $request->name;
                        $user->email_id = $request->email_id;
                        $user->company_name = $request->company_name;
                        $user->mobile_no = $request->mobile_no;
                        $user->user_type_id = $request->user_type_id;
                        $user->father_name = $request->father_name;
                        $user->address = $request->address;
                        if (isset($request->pincode) && !empty($request->pincode)) {
                            $user->pincode = $request->pincode;
                        }
                        $user->login_access = $request->login_access;
                        $user->update();
                        if ($request->login_access > 0) {
                            $user_login = new User_login();
                            $user_login->user_id = $request->user_id;
                            $user_login->username = $request->username;
                            $user_login->password = $request->password;
                            @session_start();
                            $user_login->created_by = $_SESSION['hryS_user_id'];
                            $user_login->updated_by = $_SESSION['hryS_user_id'];
                            $user_login->update();
                            if ($user_login->check() === false) {
                                if ($user_login->check_username() === false) {
                                    $user_login->insert();
                                    $response = [
                                        'success' => 1,
                                        'code' => 200,
                                        'msg' => 'User detail update successfully !'
                                    ];
                                    $user->conn->commit();
                                    http_response_code(200);
                                    echo json_encode($response);
                                } else {
                                    throw new Exception("Username already exists, please choose another name", 400);
                                }
                            } else {
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'User detail successfully added!'
                                ];
                                $user->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            }
                        } else {
                            $user_login = new User_login();
                            $user_login->user_id = $request->user_id;
                            if ($user_login->remove()) {
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'User detail Update successfully!'
                                ];
                                $user->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            } else {
                                throw new Exception('Unable to update user', 400);
                            }
                        }
                    } else if ($request->action == 'delete') {
                        $user->user_id = $request->user_id;
                        $user->delete();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'User detail successfully deleted!'
                        ];
                        $user->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get') {
                        $result = [];
                        if (isset($request->user_id) && $request->user_id > 0) {
                            $user->user_id = $request->user_id;
                        }
                        $results = $user->get($request);
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
                                'user_id'               => $res['user_id'],
                                'username'              => $res['username'],
                                'name'                  => $res['name'],
                                'email_id'              => $res['email_id'],
                                'company_name'          => $res['company_name'],
                                'mobile_no'             => $res['mobile_no'],
                                'user_type_id'          => $res['user_type_id'],
                                'login_access'          => $res['login_access'],
                                'user_type'             => $res['user_type'],
                                'father_name'           => $res['father_name'],
                                'address'               => $res['address'],
                                'pincode'               => $res['pincode'],
                                'action'                => "<a class='edit cursor-pointer' data-id='" . $res['user_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['user_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $user->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'User Detail Fetch Successfully!',
                            'data'              => $result
                        ];
                        $user->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get_lawyer') {
                        $user->user_type_id = 2;
                        $response = [
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'User details!',
                            'data'              => $user->get()
                        ];
                        $user->conn->commit();
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
    $user->conn->rollBack();
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code(500);
    echo json_encode($response);
} catch (Exception $e) {
    $user->conn->rollBack();
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code($e->getCode());
    echo json_encode($response);
}
