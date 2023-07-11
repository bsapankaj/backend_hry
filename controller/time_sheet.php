<?php
require_once '../model/time_sheet.php';
require_once '../helper/date.php';
require_once '../model/lawyer_fee.php';
try {
    @session_start();
    $login_user_id = $_SESSION["hryS_user_id"];
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                // ADD UPDATE DELETE FETCH
                if (isset($request->action)) {
                    $time_sheet = new Time_sheet();
                    if ($request->action == 'add') {
                        $time_sheet->file_id = $request->file_id;
                        $time_sheet->task_id = $request->task_id;
                        $time_sheet->cause_list_id = $request->cause_list_id;
                        $time_sheet->case_id = $request->case_id;
                        $time_sheet->description = $request->description;
                        $time_sheet->fee_id = $request->fee_id;
                        $time_sheet->billable = 'Y';
                        $time_sheet->user_id = $login_user_id;
                        $time_sheet->created_by = $login_user_id;
                        if ($request->start_time != "" && date('Hi', strtotime($request->start_time))) {
                            $time_sheet->start_time = date('Y-m-d', strtotime($request->date)) . ' ' . date('H:i', strtotime($request->start_time));
                        } else {
                            $time_sheet->start_time = date('Y-m-d', strtotime($request->date)) . ' ' . '00:00:00';
                        }
                        if ($request->end_time != "" && date('Hi', strtotime($request->end_time)) > 0) {
                            $time_sheet->end_time = date('Y-m-d', strtotime($request->date)) . ' ' . date('H:i', strtotime($request->end_time));
                        } else {
                            $time_sheet->end_time = date('Y-m-d', strtotime($request->date)) . ' ' . '00:00:00';
                        }
                        $total_time = time_difference($time_sheet->start_time, $time_sheet->end_time);
                        $time_sheet->total_time = $total_time;
                        $time_sheet->amount = 0;
                        if ($total_time >= 0) {
                            $time_sheet->insert();
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Time Sheet successfully added!'
                            ];
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('End Time must be greater then Start time', 400);
                        }
                    } else if ($request->action == 'update') {
                        $time_sheet->time_sheet_id = $request->time_sheet_id;
                        $time_sheet->file_id = $request->file_id;
                        $time_sheet->task_id = $request->task_id;
                        $time_sheet->cause_list_id = $request->cause_list_id;
                        $time_sheet->case_id = $request->case_id;
                        $time_sheet->description = $request->description;
                        $time_sheet->fee_id = $request->fee_id;
                        $time_sheet->billable = 'Y';
                        $time_sheet->user_id = $login_user_id;
                        $time_sheet->created_by = $login_user_id;
                        if ($request->start_time != "" && date('Hi', strtotime($request->start_time))) {
                            $time_sheet->start_time = date('Y-m-d', strtotime($request->date)) . ' ' . date('H:i', strtotime($request->start_time));
                        } else {
                            $time_sheet->start_time = date('Y-m-d', strtotime($request->date)) . ' ' . '00:00:00';
                        }
                        if ($request->end_time != "" && date('Hi', strtotime($request->end_time)) > 0) {
                            $time_sheet->end_time = date('Y-m-d', strtotime($request->date)) . ' ' . date('H:i', strtotime($request->end_time));
                        } else {
                            $time_sheet->end_time = date('Y-m-d', strtotime($request->date)) . ' ' . '00:00:00';
                        }
                        $total_time = time_difference($time_sheet->start_time, $time_sheet->end_time);
                        $time_sheet->total_time = $total_time;
                        $time_sheet->amount = 0;
                        $time_sheet->amount = 0;
                        if ($total_time >= 0) {
                            $time_sheet->update();
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Time Sheet successfully added!'
                            ];
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('End Time must be greater then Start time', 400);
                        }
                    } else if ($request->action == 'delete') {
                        $time_sheet->time_sheet_id = $request->time_sheet_id;
                        $time_sheet->updated_by = $login_user_id;
                        $time_sheet->delete();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Time Sheet successfully deleted!'
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'time_sheet_mis') {
                        $result = [];
                        $results = $time_sheet->time_sheet_mis($request);
                        $i = 0;
                        foreach ($results as $res) {
                            ++$i;
                            if ($res['billable'] == 'Y') {
                                $billable = 'YES';
                            } else {
                                $billable = 'NO';
                            }
                            if($res['cause_list_id']>0){
                                $res['cause'] = $res['cause'];
                            } else {
                                $res['cause'] = '';
                            }
                            $start_time = date('Hi', strtotime($res['start_time']));
                            if ($start_time > 0) {
                                $start_time = date('H:i', strtotime($res['start_time']));
                            } else {
                                $start_time = "";
                            }
                            $end_time = date('Hi', strtotime($res['end_time']));
                            if ($end_time > 0) {
                                $end_time = date('H:i', strtotime($res['end_time']));
                            } else {
                                $end_time = "";
                            }
                            $result[] = [
                                's_no'              => $i,
                                'time_sheet_id'     => $res['time_sheet_id'],
                                'date'              => date('d-m-Y', strtotime($res['start_time'])),
                                'start_time'        => $start_time,
                                'end_time'          => $end_time,
                                'name'              => $res['name'],
                                'case_no'           => $res['case_no'],
                                'file_no'           => $res['file_no'],
                                'cause'             => $res['cause'],
                                'client'            => $res['client'],
                                'task_type'         => $res['task_type'],
                                'case_id'           => $res['case_id'],
                                'task_id'           => $res['task_id'],
                                'amount'            => $res['amount'],
                                'total_time'        => $res['total_time'] . ' mins',
                                'description'       => $res['description'],
                                'billable'          => $billable
                            ];
                        }
                        $response = [
                            'recordsTotal'      => count($results),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Time Sheet Fetch Successfully!',
                            'data'              => $result
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get') {
                        $result = [];
                        if (isset($request->time_sheet_id)) {
                            $time_sheet->time_sheet_id  = $request->time_sheet_id;
                            $results = $time_sheet->get($request);
                            if (isset($request->start)) {
                                $i = $request->start;
                            } else {
                                $i = 0;
                                $request->draw = 0;
                            }

                            $i = 0;
                            foreach ($results as $res) {
                                ++$i;
                                $start_time = date('Hi', strtotime($res['start_time']));
                                if ($start_time > 0) {
                                    $start_time = date('H:i', strtotime($res['start_time']));
                                } else {
                                    $start_time = "";
                                }
                                $end_time = date('Hi', strtotime($res['end_time']));
                                if ($end_time > 0) {
                                    $end_time = date('H:i', strtotime($res['end_time']));
                                } else {
                                    $end_time = "";
                                }
                                if($res['unit'] == 2){
                                    $time =  $res['total_time'] . ' mins';
                                } else {
                                    $time =  '-';
                                }

                                $result[] = [
                                    's_no'              => $i,
                                    'time_sheet_id'     => $res['time_sheet_id'],
                                    'date'              => date('d-m-Y', strtotime($res['start_time'])),
                                    'start_time'        => $start_time,
                                    'end_time'          => $end_time,
                                    'unit'              => $res['unit'],
                                    'file_id'           => $res['file_id'],
                                    'file_no'           => $res['file_no'],
                                    'cause_list_id'     => $res['cause_list_id'],
                                    'case_no'           => $res['case_no'],
                                    'task_type'         => $res['task_type'],
                                    'case_id'           => $res['case_id'],
                                    'task_id'           => $res['task_id'],
                                    'amount'            => $res['amount'],
                                    'particulars'       => $res['particulars'],
                                    'client_name'       => $res['client_name'],
                                    'total_time'        => $time,
                                    'description'       => $res['description'],
                                    'fee_id'            => $res['fee_id'],
                                    'billable'          => $res['billable'],
                                    'action'            => "<a class='edit cursor-pointer' data-id='" . $res['time_sheet_id'] . "'><i class='fa     fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger'   data-id='" . $res['time_sheet_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                                ];
                            }
                        } else {
                            $results = $time_sheet->get($request);
                            if (isset($request->start)) {
                                $i = $request->start;
                            } else {
                                $i = 0;
                                $request->draw = 0;
                            }

                            $i = 0;
                            foreach ($results as $res) {
                                if($res['unit'] == 2){
                                    $time =  $res['total_time'] . ' mins';
                                } else {
                                    $time =  '-';
                                }
                                ++$i;
                                $result[] = [
                                    's_no'              => $i,
                                    'time_sheet_id'     => $res['time_sheet_id'],
                                    'file_id'           => $res['file_id'],
                                    'unit'              => $res['unit'],
                                    'file_no'           => $res['file_no'],
                                    'cause_list_id'     => $res['cause_list_id'],
                                    'date'              => date('d-m-Y', strtotime($res['start_time'])),
                                    'start_time'        => date('H:i', strtotime($res['start_time'])),
                                    'end_time'          => date('H:i', strtotime($res['end_time'])),
                                    'case_no'           => $res['case_no'],
                                    'task_type'         => $res['task_type'],
                                    'case_id'           => $res['case_id'],
                                    'task_id'           => $res['task_id'],
                                    'amount'            => $res['amount'],
                                    'total_time'        => $time,
                                    'particulars'       => $res['particulars'],
                                    'client_name'       => $res['client_name'],
                                    'description'       => $res['description'],
                                    'fee_id'            => $res['fee_id'],
                                    'billable'          => $res['billable'],
                                    'action'            => "<a class='edit cursor-pointer' data-id='" . $res['time_sheet_id'] . "'><i class='fa     fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger'   data-id='" . $res['time_sheet_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                                ];
                            }
                        }

                        $response = [
                            'draw'              => intval($request->draw),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Time Sheet Fetch Successfully!',
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
