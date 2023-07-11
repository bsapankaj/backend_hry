<?php
require_once '../model/cause_list.php';
require_once '../model/cause_list_detail.php';
require_once '../helper/common.php';

$cause_list = new Cause_list();
$cause_list_detail = new Cause_list_detail();

$cause_list->conn->beginTransaction();
try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                @session_start();
                $user_id = $_SESSION['hryS_user_id'];
                // $user_id = 1;

                if (isset($request->action)) {
                    if ($request->action == 'add') {
                        $cause_list->court_no = $request->court_no;
                        $cause_list->court_id = $request->court_id;
                        $cause_list->item_no = $request->item_no;
                        $cause_list->justice = $request->justice;
                        $cause_list->client_code_id = $request->client_code_id;
                        $cause_list->file_id = $request->file_id;
                        $cause_list->cause_date = date('Y-m-d H:i', strtotime($request->cause_date));
                        $cause_list->case_id = $request->case_id;
                        $cause_list->cause_desc = $request->cause_desc;
                        $cause_list->activity_type = $request->activity_type;
                        $cause_list->short_title = $request->short_title;
                        $cause_list->created_by = $user_id;
                        if ($cause_list->insert()) {
                            $cause_list_id = $cause_list->last_insert_id();
                            if ($cause_list_id > 0) {
                                $cause_list_detail->cause_list_id = $cause_list_id;
                                $cause_list_detail->created_by = $user_id;
                                foreach ($request->lawyer_ids as $lawyer_id) {
                                    $cause_list_detail->user_id = $lawyer_id;
                                    $cause_list_detail->insert_lawyers();
                                }
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'Cause List added successfully added!'
                                ];
                                $cause_list->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            } else {
                                throw new Exception("Cause id not found", 400);
                            }
                        } else {
                            throw new Exception("Someting went worng while add casue", 400);
                        }
                    } else if ($request->action == 'update') {
                        $cause_list->cause_list_id = $request->cause_list_id;
                        $cause_list->court_no = $request->court_no;
                        $cause_list->court_id = $request->court_id;
                        $cause_list->item_no = $request->item_no;
                        $cause_list->justice = $request->justice;
                        $cause_list->file_id = $request->file_id;
                        $cause_list->client_code_id = $request->client_code_id;
                        $cause_list->cause_date = date('Y-m-d H:i', strtotime($request->cause_date));
                        $cause_list->case_id = $request->case_id;
                        $cause_list->cause_desc = $request->cause_desc;
                        $cause_list->activity_type = $request->activity_type;
                        $cause_list->short_title = $request->short_title;
                        $cause_list->updated_by = $user_id;
                        if ($cause_list->update()) {
                            $cause_list_detail->cause_list_id = $request->cause_list_id;
                            if ($cause_list_detail->detective_lawyers_by_cause_list_id()) {
                                foreach ($request->lawyer_ids as $lawyer_id) {
                                    $cause_list_detail->user_id = $lawyer_id;
                                    $cause_list_detail_id = $cause_list_detail->check_exist_lawyer_on_casue();
                                    if ($cause_list_detail_id == 0) {
                                        $cause_list_detail->created_by = $user_id;
                                        $cause_list_detail->insert_lawyers();
                                    } else {
                                        $cause_list_detail->updated_by = $user_id;
                                        $cause_list_detail->cause_list_detail_id = $cause_list_detail_id;
                                        $cause_list_detail->active_lawyers_by_cause_list_detail_id();
                                    }
                                }
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'Cause list successfully updated!'
                                ];
                                $cause_list->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            } else {
                                throw new Exception('Somthing went wrong while updating Cause Lawyers', 400);
                            }
                        } else {
                            throw new Exception('Somthing went wrong while updating Cause details', 400);
                        }
                    } else if ($request->action == 'delete') {
                        $cause_list->cause_list_id = $request->cause_list_id;
                        $cause_list->updated_by = $user_id;
                        if ($cause_list->delete()) {
                            $cause_list_detail->cause_list_id = $request->cause_list_id;
                            $cause_list_detail->updated_by = $user_id;
                            if ($cause_list_detail->detective_lawyers_by_cause_list_id()) {
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'Casue successfully deleted!'
                                ];
                                $cause_list->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            } else {
                                throw new Exception('Somthing went wrong while deleting Cause lawyers', 400);
                            }
                        } else {
                            throw new Exception('Somthing went wrong while deleting Cause details', 400);
                        }
                    } else if ($request->action == 'get') {
                        $result = [];
                        $results = $cause_list->get($request);
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        foreach ($results as $res) {
                            ++$i;
                            $result[] = [
                                's_no'                  =>  $i,
                                'court_id'              =>  $res['court_id'],
                                'court_no'              =>  $res['court_no'],
                                'court_name'            =>  $res['court_name'],
                                'client_code'           =>  $res['client_code'],
                                'justice'               =>  $res['justice'],
                                'case_no'               =>  $res['case_no'],
                                'client_code_id'        =>  $res['client_code_id'],
                                'file_id'               =>  $res['file_id'],
                                'file_no'               =>  $res['file_no'],
                                'case_id'               =>  $res['case_id'],
                                'case_no'               =>  $res['case_no'],
                                'cause_desc'           =>  $res['cause_desc'],
                                'short_title'           =>  $res['short_title'],
                                'case_detail'           =>  $res['case_detail'],
                                'activity_type'         =>  $res['activity_type'],
                                'cause_date'            =>  date('d-m-Y', strtotime($res['cause_date'])),
                                'item_no'               =>  $res['item_no'],
                                'remarks'               =>  $res['remarks'],
                                'action'                =>  "<a class='edit cursor-pointer' data-id='" . $res['cause_list_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['cause_list_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $cause_list->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Court Detail Fetch Successfully!',
                            'data'              => $result
                        ];
                        $cause_list->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get_single') {
                        $result = [];
                        $i = 0;
                        $cause_list->cause_list_id = $request->cause_list_id;
                        $results = $cause_list->getSingleRecord();
                        foreach ($results as $res) {
                            ++$i;
                            $cause_list_detail->cause_list_id = $request->cause_list_id;
                            $lawyer_results = $cause_list_detail->get_lawyers_by_cause_list_id();
                            $result[] = [
                                's_no'                  =>  $i,
                                'cause_list_id'         =>  $res['cause_list_id'],
                                'court_id'              =>  $res['court_id'],
                                'court_no'              =>  $res['court_no'],
                                'court_name'            =>  $res['court_name'],
                                'client_code'           =>  $res['client_code'],
                                'justice'               =>  $res['justice'],
                                'case_no'               =>  $res['case_no'],
                                'client_code_id'        =>  $res['client_code_id'],
                                'file_id'               =>  $res['file_id'],
                                'file_no'               =>  $res['file_no'],
                                'case_id'               =>  $res['case_id'],
                                'case_no'               =>  $res['case_no'],
                                'cause_desc'            =>  $res['cause_desc'],
                                'short_title'           =>  $res['short_title'],
                                'case_detail'           =>  $res['case_detail'],
                                'activity_type'         =>  $res['activity_type'],
                                'remarks'               =>  $res['remarks'],
                                'lawyers'               =>  $lawyer_results,
                                'cause_date'            =>  date('d-m-Y', strtotime($res['cause_date'])),
                                'item_no'               =>  $res['item_no']
                            ];
                        }
                        $response = [
                            'recordsTotal'      => count($results),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Cause Detail Fetch Successfully!',
                            'data'              => $result
                        ];
                        $cause_list->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'fetch_cause_list_by_file_id') {
                        $result = [];
                        $i = 0;
                        $cause_list->file_id = $request->file_id;
                        $results = $cause_list->get();
                        foreach ($results as $res) {
                            ++$i;
                            $result[] = [
                                's_no'                  =>  $i,
                                'cause_list_id'         =>  $res['cause_list_id'],
                                'court_id'              =>  $res['court_id'],
                                'court_no'              =>  $res['court_no'],
                                'court_name'            =>  $res['court_name'],
                                'client_code'           =>  $res['client_code'],
                                'justice'               =>  $res['justice'],
                                'case_no'               =>  $res['case_no'],
                                'client_code_id'        =>  $res['client_code_id'],
                                'file_id'               =>  $res['file_id'],
                                'file_no'               =>  $res['file_no'],
                                'case_id'               =>  $res['case_id'],
                                'case_no'               =>  $res['case_no'],
                                'short_title'           =>  $res['short_title'],
                                'case_detail'           =>  $res['case_detail'],
                                'activity_type'         =>  $res['activity_type'],
                                'remarks'               =>  $res['remarks'],
                                'cause_date'            =>  date('d-m-Y', strtotime($res['cause_date'])),
                                'item_no'               =>  $res['item_no']
                            ];
                        }
                        $response = [
                            'recordsTotal'      => count($results),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Cause Detail Fetch Successfully!',
                            'data'              => $result
                        ];
                        $cause_list->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'fetch_cause_for_time_sheet') {
                        $result = [];
                        $i = 0;
                        $cause_list_detail->file_id = $request->file_id;
                        $cause_list_detail->time_sheet_id = $request->time_sheet_id;
                        $cause_list_detail->user_id = $user_id;
                        $results = $cause_list_detail->fetch_cause_for_time_sheet();
                        foreach ($results as $res) {
                            ++$i;
                            $result[] = [
                                's_no'                  =>  $i,
                                'cause_list_id'         => $res['cause_list_id'],
                                'court_no'              => $res['court_no'],
                                'item_no'               => $res['item_no'],
                                'cause_date'            => date('d-m-Y', strtotime($res['cause_date']))
                            ];
                        }
                        $response = [
                            'recordsTotal'      => count($results),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Cause Detail Fetch Successfully!',
                            'data'              => $result
                        ];
                        $cause_list->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get_justice') {
                        $result = [];
                        $results = $cause_list->get_justice($request);
                        $response = [
                            'recordsTotal'      => count($results),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Justice Fetch Successfully!',
                            'data'              => $results
                        ];
                        $cause_list->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get_pending') {
                        $result = [];
                        $results = $cause_list_detail->get_pending($request);
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
                                'cause_list_id'         => $res['cause_list_id'],
                                'court_no'              => $res['court_no'],
                                'item_no'               => $res['item_no'],
                                'court_name'            => $res['court_name'],
                                'justice'               => $res['justice'],
                                'name'                  => $res['name'],
                                'file_no'               => $res['file_no'],
                                'short_title'           => $res['short_title'],
                                'task_type'             => $res['task_type'],
                                'cause_desc'            => $res['cause_desc'],
                                'activity_type'         => $res['activity_type'],
                                'cause_date'            => date('d-m-Y', strtotime($res['cause_date']))
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $cause_list->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Court Detail Fetch Successfully!',
                            'data'              => $result
                        ];
                        $cause_list->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'print_cause') {
                        $result = [];
                        $results = $cause_list_detail->get_pending($request);
                        if(count($results)==0){
                            throw new Exception('Cause Data Not Found',401);
                        }
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        if ($request->date_format == "print") {
                            require_once 'print_cause_list.php';
                            $raw_data = group_by($results, 'court_name');
                            $content = [
                                "data" => $raw_data,
                                "request_date" => strtoupper(date('l jS F Y', strtotime($request->date)))
                            ];
                            // print_r($content);exit;
                            generate_cause_list($content);
                            $url = '../download/cause_list.pdf';
                            $response = [
                                'success'           => 1,
                                'code'              => 200,
                                'msg'               => 'PDF Generated!',
                                'data'              => $url
                            ];
                        } else {
                            foreach ($results as $res) {
                                ++$i;
                                $result[] = [
                                    's_no'                  => $i,
                                    'cause_list_id'         => $res['cause_list_id'],
                                    'court_no'              => $res['court_no'],
                                    'item_no'               => $res['item_no'],
                                    'court_name'            => $res['court_name'],
                                    'justice'               => $res['justice'],
                                    'name'                  => $res['name'],
                                    'case_no'               => $res['case_no'],
                                    'file_no'               => $res['file_no'],
                                    'timesheet_description' => $res['timesheet_description'],
                                    'short_title'           => $res['short_title'],
                                    'task_type'             => $res['task_type'],
                                    'cause_desc'            => $res['cause_desc'],
                                    'activity_type'         => $res['activity_type'],
                                    'cause_date'            => date('d-m-Y', strtotime($res['cause_date']))
                                ];
                            }
                            $response = [
                                'draw'              => intval($request->draw),
                                'recordsTotal'      => count($results),
                                'recordsFiltered'   => $cause_list->get_total_count(),
                                'success'           => 1,
                                'code'              => 200,
                                'msg'               => 'Court Detail Fetch Successfully!',
                                'data'              => $result
                            ];
                        }

                        $cause_list->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'update_period') {
                        $cause_list->cause_list_id = $request->cause_list_id;
                        $cause_list->remarks = $request->remarks;
                        $cause_list->updated_by = $user_id;
                        if ($cause_list->upadte_remarks()) {
                            $lawyer_data = $request->lawyer_data;
                            foreach ($lawyer_data as $d) {
                                $cause_list_detail->cause_list_detail_id = $d->cld_id;
                                $cause_list_detail->to_time = date('H:i', strtotime($d->to_time));
                                $cause_list_detail->from_time = date('H:i', strtotime($d->from_time));
                                $cause_list_detail->updated_by = $user_id;
                                $cause_list_detail->update_period();
                            }
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Cause list details successfully updated!'
                            ];
                            $cause_list->conn->commit();
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('Somthing went wrong while updating Cause remarks details', 400);
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
    $cause_list->conn->rollBack();
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code(500);
    echo json_encode($response);
} catch (Exception $e) {
    $cause_list->conn->rollBack();
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code($e->getCode());
    echo json_encode($response);
}
