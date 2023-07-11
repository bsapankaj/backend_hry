<?php require_once '../model/case_master.php';
try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                // ADD UPDATE DELETE FETCH
                if (isset($request->action)) {
                    $case_master = new Case_master();
                    if ($request->action == 'add') {
                        $case_master->case_no = $request->case_no;
                        $case_master->client_code_id = $request->client_code_id;
                        $case_master->file_id = $request->file_id;
                        $case_master->case_detail = $request->case_detail;
                        $case_master->court_case_title = $request->court_case_title;
                        $case_master->case_description = $request->case_description;
                        $case_master->case_vs_from = $request->case_vs_from;
                        $case_master->case_vs_to = $request->case_vs_to;
                        $case_master->case_start_date = date('Y-m-d', strtotime($request->case_start_date));
                        if ($case_master->check_case_with_file_id() === false) {
                            if ($case_master->check() === false) {
                                $case_master->insert();
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'Case detail successfully added!'
                                ];

                                http_response_code(200);
                                echo json_encode($response);
                            } else {
                                throw new Exception('Case No Already Exists', 400);
                            }
                        } else {
                            throw new Exception("File no. already exist on another case no", 400);
                        }
                    } else if ($request->action == 'update') {
                        $case_master->case_id = $request->case_id;
                        $case_master->client_code_id = $request->client_code_id;
                        $case_master->file_id = $request->file_id;
                        $case_master->case_no = $request->case_no;
                        $case_master->case_detail = $request->case_detail;
                        $case_master->court_case_title = $request->court_case_title;
                        $case_master->case_description = $request->case_description;
                        $case_master->case_vs_from = $request->case_vs_from;
                        $case_master->case_vs_to = $request->case_vs_to;
                        $case_master->case_start_date = date('Y-m-d', strtotime($request->case_start_date));
                        if ($case_master->check_case_with_file_id() === false) {
                            if ($case_master->check() === false) {
                                $case_master->update();
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'Case detail Update successfully!'
                                ];

                                http_response_code(200);
                                echo json_encode($response);
                            } else {
                                throw new Exception('Case No Already Exists', 400);
                            }
                        } else {
                            throw new Exception("File no. already exist on another case no", 400);
                        }
                    } else if ($request->action == 'delete') {
                        $case_master->case_id = $request->case_id;
                        $case_master->delete();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Case detail successfully deleted!'
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get') {
                        $result = [];
                        if (isset($request->case_id) && $request->case_id > 0) {
                            $case_master->case_id = $request->case_id;
                        }
                        $results = $case_master->get($request);
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        foreach ($results as $res) {
                            ++$i;
                            $result[] = [
                                's_no'              => $i,
                                'case_id'           => $res['case_id'],
                                'file_id'           => $res['file_id'],
                                'file_no'           => $res['file_no'],
                                'client_code'       => $res['client_code'],
                                'client_code_id'    => $res['client_code_id'],
                                'court_case_title'  => $res['court_case_title'],
                                'case_description'  => $res['case_description'],
                                'case_no'           => $res['case_no'],
                                'case_from'         => $res['case_vs_from'],
                                'case_to'           => $res['case_vs_to'],
                                'case_start'        => date('d-m-Y', strtotime($res['case_start_date'])),
                                'case_detail'       => $res['case_detail'],
                                'action'            => "<a class='edit cursor-pointer' data-id='" . $res['case_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['case_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $case_master->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Case Detail Fetch Successfully!',
                            'data'              => $result
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'fatch_by_lawyer_id') {
                        $result = [];
                        if (isset($request->case_id) && $request->case_id > 0) {
                            $case_master->case_id = $request->case_id;
                        }
                        $results = $case_master->fatch_by_lawyer_id($request);
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        foreach ($results as $res) {
                            ++$i;
                            $result[] = [
                                's_no'              => $i,
                                'case_id'           => $res['case_id'],
                                'client_code'       => $res['client_code'],
                                'client_code_id'    => $res['client_code_id'],
                                'case_no'           => $res['case_no'],
                                'court_case_title'  => $res['court_case_title'],
                                'case_description'  => $res['case_description'],
                                'case_from'         => $res['case_vs_from'],
                                'case_to'           => $res['case_vs_to'],
                                'case_start'        => date('d-m-Y', strtotime($res['case_start_date'])),
                                'case_detail'       => $res['case_detail'],
                                'action'            => "<a class='edit cursor-pointer' data-id='" . $res['case_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['case_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $case_master->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Case Detail Fetch Successfully!',
                            'data'              => $result
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'fatch_by_client_code') {
                        $result = [];
                        $case_master->client_code_id = $request->client_id;
                        $results = $case_master->fatch_by_client_code();
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        foreach ($results as $res) {
                            ++$i;
                            $result[] = [
                                'case_detail'       => $res['case_detail'],
                                'case_from'         => $res['case_vs_from'],
                                'case_to'           => $res['case_vs_to'],
                                'case_id'           => $res['case_id'],
                                'case_no'           => $res['case_no']
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $case_master->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Case Detail Fetch Successfully!',
                            'data'              => $result
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'fatch_by_file_id') {
                        $result = [];
                        $case_master->file_id = $request->file_id;
                        $results = $case_master->fatch_by_file_id();
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        foreach ($results as $res) {
                            ++$i;
                            $result[] = [
                                'case_detail'       => $res['case_detail'],
                                'case_from'         => $res['case_vs_from'],
                                'case_to'           => $res['case_vs_to'],
                                'case_id'           => $res['case_id'],
                                'case_no'           => $res['case_no']
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $case_master->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Case Detail Fetch Successfully!',
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
        'msg' => $e->getMessage()
    ];
    http_response_code($e->getCode());
    echo json_encode($response);
}
