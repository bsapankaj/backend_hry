<?php require_once '../model/designation.php';

try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                // ADD UPDATE DELETE GET
                @session_start();
                $user_id = $_SESSION['hryS_user_id'];
                if (isset($request->action)) {
                    $user_designation = new User_designation();
                    if ($request->action == 'add') {
                        $user_designation->user_designation = $request->user_designation;
                        $user_designation->user_type_id = $request->user_type_id;
                        $user_designation->sort_code = $request->sort_code;
                        $user_designation->created_by = $user_id;
                        if ($user_designation->check() === false) {
                            $user_designation->insert();
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Designation successfully added!'
                            ];

                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('Designation Already Exists', 400);
                        }
                    } else if ($request->action == 'update') {
                        $user_designation->user_designation_id = $request->user_designation_id;
                        $user_designation->user_designation = $request->user_designation;
                        $user_designation->user_type_id = $request->user_type_id;
                        $user_designation->sort_code = $request->sort_code;
                        $user_designation->updated_by = $user_id;
                        $user_designation->update();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Designation Update successfully!'
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'delete') {
                        $user_designation->user_designation_id = $request->user_designation_id;
                        $user_designation->delete();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Designation successfully deleted!'
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get') {
                        $result = [];
                        if (isset($request->user_designation_id) && $request->user_designation_id > 0) {
                            $user_designation->user_designation_id = $request->user_designation_id;
                        }
                        $results = $user_designation->get($request);
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
                                'user_designation_id'   => $res['user_designation_id'],
                                'user_designation'      => $res['user_designation'],
                                'user_type_id'          => $res['user_type_id'],
                                'user_type'             => $res['user_type'],
                                'sort_code'             => $res['sort_code'],
                                'action'                => "<a class='edit cursor-pointer' data-id='" . $res['user_designation_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['user_designation_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $user_designation->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Designation Fetch Successfully!',
                            'data'              => $result
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get_by_user_type_id') {
                        $user_designation->user_type_id = $request->user_type_id;
                        $result = [];
                        $results = $user_designation->get_by_user_type_id($request);
                        $i = 0;
                        foreach ($results as $res) {
                            ++$i;
                            $result[] = [
                                's_no'                  => $i,
                                'user_designation_id'   => $res['user_designation_id'],
                                'user_designation'      => $res['user_designation'],
                                'user_type_id'          => $res['user_type_id'],
                                'user_type'             => $res['user_type'],
                                'sort_code'             => $res['sort_code'],
                                'action'                => "<a class='edit cursor-pointer' data-id='" . $res['user_designation_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['user_designation_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $user_designation->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Designation Fetch Successfully!',
                            'data'              => $result
                        ];
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
