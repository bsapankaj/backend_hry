<?php
require_once '../model/lawyer_advisory.php';
require_once '../helper/common.php';

$lawyer_advisory = new Lawyer_advisory();

$lawyer_advisory->conn->beginTransaction();

try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                // ADD UPDATE DELETE GET
                if (isset($request->action)) {
                    @session_start();
                    $user_id = $_SESSION["hryS_user_id"];
                   
                    if ($request->action == 'add') {
                        $lawyer_advisory->date = date('Y-m-d', strtotime($request->date));
                        $lawyer_advisory->client_code_id = $request->client_code_id;
                        $lawyer_advisory->file_id = $request->file_id;
                        $lawyer_advisory->case_id = $request->case_id;
                        $lawyer_advisory->lawyer_name = $request->lawyer_name;
                        $lawyer_advisory->fee = $request->fee;
                        $lawyer_advisory->task_id = $request->task_id;
                        $lawyer_advisory->created_by = $user_id;
                        if ($lawyer_advisory->insert()) {
                            $lawyer_advisory_id = $lawyer_advisory->last_insert_id();
                            // getting file extension in base 64
                            if (isset($request->bill_file_base64) && $request->bill_file_base64 != '') {
                                $file_name = $lawyer_advisory_id . '_' . date('YmdHis');
                                if (createFileFromBase64AllExtn($request->bill_file_base64, "../../rsp_files/", $file_name)) {
                                    $lawyer_advisory->lawyer_advisory_id = $lawyer_advisory_id;
                                    $lawyer_advisory->sacn_bill_copy_path = $file_name . '.' . get_string_between($request->bill_file_base64, '/', ';base64');
                                    $lawyer_advisory->updated_by = $user_id;
                                    if ($lawyer_advisory->update_bill_path()) {
                                        $response = [
                                            'success' => 1,
                                            'code' => 200,
                                            'msg' => 'Lawyer Advisory updated successfully!'
                                        ];
                                        $lawyer_advisory->conn->commit();
                                        http_response_code(200);
                                        echo json_encode($response);
                                    } else {
                                        $lawyer_advisory->conn->rollBack();
                                        throw new Exception("Error while saving bill file", 400);
                                    }
                                } else {
                                    $lawyer_advisory->conn->rollBack();
                                    throw new Exception("Error while uploading bill file", 400);
                                }
                            } else {
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'Senior Lawyer Advisory details successfully added!'
                                ];
                                $lawyer_advisory->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            }
                        } else {
                            $lawyer_advisory->conn->rollBack();
                            throw new Exception('Error while adding Senior Lawyer Advisory details', 400);
                        }
                    } else if ($request->action == 'update') {
                        $lawyer_advisory->lawyer_advisory_id = $request->lawyer_advisory_id;
                        $lawyer_advisory->date = date('Y-m-d', strtotime($request->date));
                        $lawyer_advisory->client_code_id = $request->client_code_id;
                        $lawyer_advisory->file_id = $request->file_id;
                        $lawyer_advisory->case_id = $request->case_id;
                        $lawyer_advisory->lawyer_name = $request->lawyer_name;
                        $lawyer_advisory->fee = $request->fee;
                        $lawyer_advisory->task_id = $request->task_id;
                        $lawyer_advisory->updated_by = $user_id;
                        if ($lawyer_advisory->update()) {
                            if (isset($request->bill_file_base64) && $request->bill_file_base64 != '') {
                                $file_name = $request->lawyer_advisory_id . '_' . date('YmdHis');
                                if (createFileFromBase64AllExtn($request->bill_file_base64, "../../rsp_files/", $file_name)) {
                                    $lawyer_advisory->lawyer_advisory_id = $request->lawyer_advisory_id;
                                    $lawyer_advisory->sacn_bill_copy_path = $file_name . '.' . get_string_between($request->bill_file_base64, '/', ';base64');
                                    $lawyer_advisory->updated_by = $user_id;
                                    if ($lawyer_advisory->update_bill_path()) {
                                        $response = [
                                            'success' => 1,
                                            'code' => 200,
                                            'msg' => 'Lawyer Advisory updated successfully!'
                                        ];
                                        $lawyer_advisory->conn->commit();
                                        http_response_code(200);
                                        echo json_encode($response);
                                    } else {
                                        $lawyer_advisory->conn->rollBack();
                                        throw new Exception("Error while saving bill file", 400);
                                    }
                                } else {
                                    $lawyer_advisory->conn->rollBack();
                                    throw new Exception("Error while uploading bill file", 400);
                                }
                            } else {
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'Lawyer Advisory updated successfully!'
                                ];
                                $lawyer_advisory->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            }
                        } else {
                            $lawyer_advisory->conn->rollBack();
                            throw new Exception("Error while Updating Lawyer Advisory", 400);
                        }
                    } else if ($request->action == 'delete') {
                        $lawyer_advisory->lawyer_advisory_id = $request->lawyer_advisory_id;
                        $lawyer_advisory->updated_by = $user_id;
                        if ($lawyer_advisory->delete()) {
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Lawyer Advisory successfully deleted!'
                            ];
                            $lawyer_advisory->conn->commit();
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception("Error while deleting Lawyer Advisory", 1);
                        }
                    } else if ($request->action == 'get') {
                        $result = [];
                        if (isset($request->lawyer_advisory_id) && $request->lawyer_advisory_id > 0) {
                            $lawyer_advisory->lawyer_advisory_id = $request->lawyer_advisory_id;
                        }
                        $results = $lawyer_advisory->get($request);
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        foreach ($results as $res) {
                            ++$i;
                            if(file_exists('../../rsp_files/'.$res['sacn_bill_copy_path']) && !empty($res['sacn_bill_copy_path'])){
                                $a = "<a class='edit cursor-pointer' data-id='" . $res['lawyer_advisory_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['lawyer_advisory_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a style='cursor:pointer;' href='../rsp_files/".$res['sacn_bill_copy_path']."' target='_blank' class='text-warning'><i class='fa fa-download' aria-hidden='true'></i></a>";
                            } else{
                                $a = "<a class='edit cursor-pointer' data-id='" . $res['lawyer_advisory_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['lawyer_advisory_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>";
                            }
                            $result[] = [
                                's_no'                      =>  $i,
                                'lawyer_advisory_id'        =>  $res['lawyer_advisory_id'],
                                'date'                      =>  $res['date'],
                                'client_code_id'            =>  $res['client_code_id'],
                                'client_code'               =>  $res['client_code'],
                                'file_id'                   =>  $res['file_id'],
                                'file_no'                   =>  $res['file_no'],
                                'case_id'                   =>  $res['case_id'],
                                'case_no'                   =>  $res['case_no'],
                                'lawyer_name'               =>  $res['lawyer_name'],
                                'fee'                       =>  $res['fee'],
                                'task_id'                   =>  $res['task_id'],
                                'task_type'                 =>  $res['task_type'],
                                'sacn_bill_copy_path'       =>  $res['sacn_bill_copy_path'],
                                'action'                    => $a
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $lawyer_advisory->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Lawyer Advisory Data Fetch Successfully!',
                            'data'              => $result
                        ];
                        $lawyer_advisory->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'getSingle') {
                        $result = [];
                        $lawyer_advisory->lawyer_advisory_id = $request->lawyer_advisory_id;

                        $results = $lawyer_advisory->getSingle();
                        $i = 0;
                        $request->draw = 0;

                        foreach ($results as $res) {
                            ++$i;
                            $result[] = [
                                's_no'                      => $i,
                                'lawyer_advisory_id'        =>  $res['lawyer_advisory_id'],
                                'date'                      =>  $res['date'],
                                'client_code_id'            =>  $res['client_code_id'],
                                'client_code'               =>  $res['client_code'],
                                'file_id'                   =>  $res['file_id'],
                                'file_no'                   =>  $res['file_no'],
                                'case_id'                   =>  $res['case_id'],
                                'case_no'                   =>  $res['case_no'],
                                'lawyer_name'               =>  $res['lawyer_name'],
                                'fee'                       =>  $res['fee'],
                                'task_id'                   =>  $res['task_id'],
                                'task_type'                 =>  $res['task_type'],
                                'sacn_bill_copy_path'       =>  $res['sacn_bill_copy_path'],
                                'action'                    => "<a class='edit cursor-pointer' data-id='" . $res['lawyer_advisory_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['lawyer_advisory_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $lawyer_advisory->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Lawyer Advisory Data Fetch Successfully!',
                            'data'              => $result
                        ];
                        $lawyer_advisory->conn->commit();
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
    $lawyer_advisory->conn->rollBack();
    $response = [

        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code(500);
    echo json_encode($response);
} catch (Exception $e) {
    $lawyer_advisory->conn->rollBack();
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage(),
    ];
    http_response_code($e->getCode());
    echo json_encode($response);
}
