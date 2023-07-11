<?php require_once '../model/task_type.php';

try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                // ADD UPDATE DELETE GET
                if (isset($request->action)) {
                    $task_master = new Task_type();
                    if ($request->action == 'add') {
                        $task_master->task_type = $request->task_type;
                        $task_master->sort_code = $request->sort_code;
                        $task_master->task_description = $request->task_description;
                        if ($task_master->check() === false) {
                            $task_master->insert();
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Task Type successfully added!'
                            ];

                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('Task Type Already Exists', 400);
                        }
                    } else if ($request->action == 'update') {
                        $task_master->task_id = $request->task_id;
                        $task_master->task_type = $request->task_type;
                        $task_master->sort_code = $request->sort_code;
                        $task_master->task_description = $request->task_description;
                        // $task_master->sort_order = $request->sort_order;
                        $task_master->update();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Task Type Update successfully!'
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'delete') {
                        $task_master->task_id = $request->task_id;
                        $task_master->delete();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Task Type successfully deleted!'
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get') {
                        $result = [];
                        if (isset($request->task_id) && $request->task_id > 0) {
                            $task_master->task_id = $request->task_id;
                        }
                        $results = $task_master->get($request);
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        foreach ($results as $res) {
                            if (isset($request->is_cause) && $request->is_cause == 'Y') {
                                if ($res['cause_task'] == 'Y') {
                                    ++$i;
                                    $result[] = [
                                        's_no'              => $i,
                                        'task_id'           => $res['task_id'],
                                        'task_type'         => $res['task_type'],
                                        'sort_code'         => $res['sort_code'],
                                        'task_description'  => $res['task_description'],
                                        'sort_order'        => $res['sort_order'],
                                        'action'            => "<a class='edit cursor-pointer' data-id='" . $res['task_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['task_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                                    ];
                                }
                            } else {
                                ++$i;
                                $result[] = [
                                    's_no'              => $i,
                                    'task_id'           => $res['task_id'],
                                    'task_type'         => $res['task_type'],
                                    'sort_code'         => $res['sort_code'],
                                    'task_description'  => $res['task_description'],
                                    'sort_order'        => $res['sort_order'],
                                    'action'            => "<a class='edit cursor-pointer' data-id='" . $res['task_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['task_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                                ];
                            }
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $task_master->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Task Type Fetch Successfully!',
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
