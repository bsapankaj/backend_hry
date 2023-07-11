<?php
require_once '../model/court.php';

try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                @session_start();
                $user_id = $_SESSION['hryS_user_id'];
                if (isset($request->action)) {
                    $court = new Court();
                    if ($request->action == 'add') {
                        $court->court_name = $request->court_name;
                        $court->created_by = $user_id;
                        if ($court->check() === false) {
                            if ($court->insert()) {
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'Court details successfully added!'
                                ];
                                http_response_code(200);
                                echo json_encode($response);
                            } else {
                                throw new Exception('Somthing went wrong while adding Court details!', 400);
                            }
                        } else {
                            throw new Exception('Court name already exists', 400);
                        }
                    } else if ($request->action == 'update') {
                        $court->court_id = $request->court_id;
                        $court->court_name = $request->court_name;
                        $court->updated_by = $user_id;
                        if ($court->update()) {
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Court successfully updated!'
                            ];
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('Somthing went wrong while updating Court details', 400);
                        }
                    } else if ($request->action == 'delete') {
                        $court->court_id = $request->court_id;
                        $court->updated_by = $user_id;
                        if ($court->delete()) {
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Court successfully deleted!'
                            ];
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('Somthing went wrong while deleting Court details', 400);
                        }
                    } else if ($request->action == 'get') {
                        if (isset($request->court_id) && $request->court_id > 0) {
                            $court->court_id = $request->court_id;
                            $result = $court->getSingleRecord();
                            // foreach ($results as $res) {
                            //     $result[] = [
                            //         's_no'            => $res['court_id'],
                            //         'court_name'      => $res['court_name']
                            //     ];
                            // }
                            $response = [
                                'recordsTotal'      => count($result),
                                'success'           => 1,
                                'code'              => 200,
                                'msg'               => 'Courts Fetch Successfully!',
                                'data'              => $result
                            ];
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            $result = [];
                            $results = $court->get($request);
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
                                    'court_name'      => $res['court_name'],
                                    'court_id'        => $res['court_id'],
                                    'action'          => "<a class='edit cursor-pointer' data-id='" . $res['court_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['court_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                                ];
                            }
                            $response = [
                                'draw'              => intval($request->draw),
                                'recordsTotal'      => count($results),
                                'recordsFiltered'   => $court->get_total_count(),
                                'success'           => 1,
                                'code'              => 200,
                                'msg'               => 'Courts Fetch Successfully!',
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
