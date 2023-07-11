<?php
require_once '../model/justice.php';

try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                @session_start();
                $user_id = $_SESSION['hryS_user_id'];
                if (isset($request->action)) {
                    $justice = new Justice();
                    if ($request->action == 'add') {
                        $justice->justice_name = $request->justice_name;
                        $justice->created_by = $user_id;
                        if ($justice->check() === false) {
                            if ($justice->insert()) {
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'Justice details successfully added!'
                                ];
                                http_response_code(200);
                                echo json_encode($response);
                            } else {
                                throw new Exception('Somthing went wrong while adding Justice details!', 400);
                            }
                        } else {
                            throw new Exception('Justice name already exists', 400);
                        }
                    } else if ($request->action == 'update') {
                        $justice->justice_id = $request->justice_id;
                        $justice->justice_name = $request->justice_name;
                        $justice->updated_by = $user_id;
                        if ($justice->update()) {
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Justice successfully updated!'
                            ];
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('Somthing went wrong while updating Justice details', 400);
                        }
                    } else if ($request->action == 'delete') {
                        $justice->justice_id = $request->justice_id;
                        $justice->updated_by = $user_id;
                        if ($justice->delete()) {
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Justice successfully deleted!'
                            ];
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('Somthing went wrong while deleting Justice details', 400);
                        }
                    } else if ($request->action == 'get') {
                        if (isset($request->justice_id) && $request->justice_id > 0) {
                            $justice->justice_id = $request->justice_id;
                            $result = $justice->getSingleRecord();
                            // foreach ($results as $res) {
                            //     $result[] = [
                            //         's_no'            => $res['justice_id'],
                            //         'justice_name'      => $res['justice_name']
                            //     ];
                            // }
                            $response = [
                                'recordsTotal'      => count($result),
                                'success'           => 1,
                                'code'              => 200,
                                'msg'               => 'Justices Fetch Successfully!',
                                'data'              => $result
                            ];
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            $result = [];
                            $results = $justice->get($request);
                            if (isset($request->start)) {
                                $i = $request->start;
                            } else {
                                $i = 0;
                                $request->draw = 0;
                            }
                            foreach ($results as $res) {
                                ++$i;
                                $result[] = [
                                    's_no'            => $i,
                                    'justice_name'      => $res['justice_name'],
                                    'action'          => "<a class='edit cursor-pointer' data-id='" . $res['justice_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['justice_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                                ];
                            }
                            $response = [
                                'draw'              => intval($request->draw),
                                'recordsTotal'      => count($results),
                                'recordsFiltered'   => $justice->get_total_count(),
                                'success'           => 1,
                                'code'              => 200,
                                'msg'               => 'Justices Fetch Successfully!',
                                'data'              => $result
                            ];
                            http_response_code(200);
                            echo json_encode($response);
                        }
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
        'msg' => $e->getMessage()
    ];
    http_response_code($e->getCode());
    echo json_encode($response);
}
