<?php require_once '../model/fee_master.php';
try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                if (isset($request->action)) {
                    $fee_master = new fee_master();
                    if ($request->action == 'add') {
                        $fee_master->client_code_id   = $request->client_code_id;
                        $fee_master->task_id   = $request->task_id;
                        $fee_master->particulars   = $request->particulars;
                        // $fee_master->user_designation_id   = $request->user_designation_id;
                        // $fee_master->court_id   = $request->court_id;
                        $fee_master->unit   = $request->unit;
                        $fee_master->fee   = $request->fee;
                        @session_start();
                        $fee_master->created_by = $_SESSION['hryS_user_id'];
                        // if ($fee_master->check() === false) {
                        $fee_master->insert();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Client Rate successfully added!'
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                        // } else {
                        //     throw new Exception('Client Rate Already Exists', 400);
                        // }
                    } else if ($request->action == 'update') {
                        // print_r($request);exit;
                        $fee_master->fee_master_id  = $request->client_rate_id;
                        $fee_master->client_code_id   = $request->client_code_id;
                        $fee_master->task_id   = $request->task_id;
                        $fee_master->particulars   = $request->particulars;
                        // $fee_master->user_designation_id   = $request->user_designation_id;
                        // $fee_master->court_id   = $request->court_id;
                        $fee_master->unit   = $request->unit;
                        $fee_master->fee   = $request->fee;
                        @session_start();
                        $fee_master->updated_by = $_SESSION['hryS_user_id'];
                        $fee_master->update();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Client Rate  Update Successfully!'
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'delete') {
                        $fee_master->fee_master_id = $request->client_rate_id;
                        $fee_master->delete();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Client Rate Successfully deleted!',
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get') {
                        $result = [];
                        if (isset($request->fee_master_id) && $request->fee_master_id > 0) {
                            $fee_master->fee_master_id = $request->fee_master_id;
                        } else if (isset($request->client_code_id) && $request->client_code_id > 0) {
                            $fee_master->client_code_id = $request->client_code_id;
                        }
                        $results = $fee_master->get($request);
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                            $unit = "";
                        }
                        // echo "<pre>";
                        // print_r($results);exit;
                        foreach ($results as $res) {
                            if ($res["unit"] == 1) {
                                $unit = 'Per Appearance';
                            } elseif ($res["unit"] == 2) {
                                $unit = 'Per Hours';
                            } elseif ($res["unit"] == 3) {
                                $unit = 'Per Days';
                            } elseif ($res["unit"] == 4) {
                                $unit = 'Lump Sum';
                            } else {
                                $unit = 'Actual';
                            }

                            ++$i;
                            $result[] = [
                                "s_no"       =>  $i,
                                'fee_master_id'  => $res['fee_master_id'],
                                "client" =>  $res["client_name"],
                                "particulars" =>  $res["particulars"],
                                "task" =>  $res["task_type"],
                                "unit" => $unit,
                                "fee"  =>  $res["fee"],
                                "action"     => "<a class='edit cursor-pointer' data-id='" . $res['fee_master_id'] . "' data-client='" . $res['client_code_id'] . "' data-task='" . $res['task_id'] . "' data-unit='" . $res["unit"] . "' data-fee='" . $res['fee'] . "' data-particulars='" . $res['particulars'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;
                                <a class='delete cursor-pointer text-danger' data-id='" . $res['fee_master_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        //  "<pre>";
                        // print_r($results);die(); #"unit" =>  ($res["unit"] == 1) ? 'Per Visit' : 'Per Hours',


                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $fee_master->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Client Rate Fetch Successfully!',
                            'data'              => $result,
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get_by_client_code_id') {
                        if (isset($request->client_code_id) && $request->client_code_id > 0) {
                            $fee_master->client_code_id = $request->client_code_id;
                        }
                        $result = [];
                        $i = 0;

                        $results = $fee_master->get();
                        foreach ($results as $res) {

                            ++$i;
                            $result[] = [
                                "s_no"       =>  $i,
                                'fee_master_id' => $res['fee_master_id'],
                                'client_code_id' => $res['client_code_id'],
                                'particulars' => $res['particulars'],
                                'task_id' => $res['task_id'],
                                'client_name' => $res['client_name'],
                                'task_type' => $res['task_type'],
                                'unit' => $res['unit'],
                                'fee' => $res['fee']
                            ];
                        }
                        $response = [
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $fee_master->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Client Rate Fetch Successfully!',
                            'data'              => $result,
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get_by_client_code_id_and_task_id') {

                        $fee_master->client_code_id = $request->client_code_id;
                        $fee_master->task_id = $request->task_id;

                        $result = [];
                        $i = 0;

                        $results = $fee_master->get_by_client_id_and_task_id();
                        foreach ($results as $res) {

                            ++$i;
                            $result[] = [
                                "s_no"       =>  $i,
                                'fee_master_id' => $res['fee_master_id'],
                                'client_code_id' => $res['client_code_id'],
                                'particulars' => $res['particulars'],
                                'task_id' => $res['task_id'],
                                'client_name' => $res['client_name'],
                                'task_type' => $res['task_type'],
                                'unit' => $res['unit'],
                                'fee' => $res['fee']
                            ];
                        }
                        $response = [
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $fee_master->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Client Rate Fetch Successfully!',
                            'data'              => $result,
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
    // http_response_code($e->getCode());
    echo json_encode($response);
}
