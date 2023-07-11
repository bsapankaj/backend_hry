<?php
require_once '../model/case_daily_expense.php';
require_once '../helper/common.php';

$case_daily_expenses = new Case_daily_expenses();

$case_daily_expenses->conn->beginTransaction();
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
                        $case_daily_expenses->expense_date = date('Y-m-d', strtotime($request->expense_date));
                        $case_daily_expenses->file_id = $request->file_id;
                        $case_daily_expenses->client_code_id = $request->client_code_id;
                        $case_daily_expenses->case_id = $request->case_id;
                        $case_daily_expenses->photocopy = $request->photocopy;
                        $case_daily_expenses->courier_domestic = $request->courier_domestic;
                        $case_daily_expenses->courier_international = $request->courier_international;
                        $case_daily_expenses->hotel_stay = $request->hotel_stay;
                        $case_daily_expenses->stay_place = $request->stay_place;
                        $case_daily_expenses->stayWithAss = $request->stayWithAss;
                        $case_daily_expenses->hotelNarration = $request->hotelNarration;
                        $case_daily_expenses->hotelCalculat_bas = $request->hotelCalculat_bas;
                        $case_daily_expenses->conveyance = $request->conveyance;
                        $case_daily_expenses->air_ticket = $request->air_ticket;
                        $case_daily_expenses->airStay = $request->airStay;
                        $case_daily_expenses->airAss = $request->airAss;
                        $case_daily_expenses->airNarration = $request->airNarration;
                        $case_daily_expenses->airCalculat_bas = $request->airCalculat_bas;
                        $case_daily_expenses->oth_expense = $request->oth_expense;
                        $case_daily_expenses->created_by = $user_id;
                        if ($case_daily_expenses->insert()) {
                            $case_daily_expenses_id = $case_daily_expenses->last_insert_id();
                            // getting file extension in base 64
                            if (isset($request->bill_file_base64) && $request->bill_file_base64 != '') {
                                $file_name = 'ExpenseBill'.'_'.$case_daily_expenses_id . '_' . date('YmdHis');
                                if (createFileFromBase64AllExtn($request->bill_file_base64, "../../rsp_files/", $file_name)) {
                                    $case_daily_expenses->case_daily_expense_id = $case_daily_expenses_id;
                                    $case_daily_expenses->bill_path = $file_name . '.' . get_string_between($request->bill_file_base64, '/', ';base64');
                                    $case_daily_expenses->updated_by = $user_id;
                                    if ($case_daily_expenses->update_bill_path()) {
                                        $response = [
                                            'success' => 1,
                                            'code' => 200,
                                            'msg' => 'Case Daily Expense Add successfully!'
                                        ];
                                        $case_daily_expenses->conn->commit();
                                        http_response_code(200);
                                        echo json_encode($response);
                                    } else {
                                        throw new Exception("Error while saving bill file", 400);
                                    }
                                } else {
                                    throw new Exception("Error while uploading bill file", 400);
                                }
                            } else {
                                $response = [
                                    'success' => 1,
                                    'code' => 200,
                                    'msg' => 'Senior Case Daily Expense details successfully added!'
                                ];
                                $case_daily_expenses->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            }
                        } else {
                            $case_daily_expenses->conn->rollBack();
                            throw new Exception('Error while adding Senior Case Daily Expense details', 400);
                        }
                    } else if ($request->action == 'update') {
                        $case_daily_expenses->case_daily_expense_id = $request->case_daily_expense_id;
                        $case_daily_expenses->expense_date = date('Y-m-d', strtotime($request->expense_date));
                        $case_daily_expenses->file_id = $request->file_id;
                        $case_daily_expenses->client_code_id = $request->client_code_id;
                        $case_daily_expenses->case_id = $request->case_id;
                        $case_daily_expenses->photocopy = $request->photocopy;
                        $case_daily_expenses->courier_domestic = $request->courier_domestic;
                        $case_daily_expenses->courier_international = $request->courier_international;
                        $case_daily_expenses->hotel_stay = $request->hotel_stay;
                        $case_daily_expenses->stay_place = $request->stay_place;
                        $case_daily_expenses->hotelNarration = $request->hotelNarration;
                        $case_daily_expenses->stayWithAss = $request->stayWithAss;
                        $case_daily_expenses->hotelCalculat_bas = $request->hotelCalculat_bas;
                        $case_daily_expenses->conveyance = $request->conveyance;
                        $case_daily_expenses->oth_expense = $request->oth_expense;
                        $case_daily_expenses->air_ticket = $request->air_ticket;
                        $case_daily_expenses->airStay = $request->airStay;
                        $case_daily_expenses->airAss = $request->airAss;
                        $case_daily_expenses->airNarration = $request->airNarration;
                        $case_daily_expenses->airCalculat_bas = $request->airCalculat_bas;
                        $case_daily_expenses->updated_by = $user_id;
                        if ($case_daily_expenses->update()) {
                            if (isset($request->bill_file_base64) && $request->bill_file_base64 != '') {
                                $file_name = 'ExpenseBill'.'_'.$request->case_daily_expense_id . '_' . date('YmdHis');
                                if (createFileFromBase64AllExtn($request->bill_file_base64, "../../rsp_files/", $file_name)) {
                                    $case_daily_expenses->case_daily_expense_id = $request->case_daily_expense_id;
                                    $case_daily_expenses->bill_path = $file_name . '.' . get_string_between($request->bill_file_base64, '/', ';base64');
                                    $case_daily_expenses->updated_by = $user_id;
                                    if ($case_daily_expenses->update_bill_path()) {
                                        $response = [
                                            'success' => 1,
                                            'code' => 200,
                                            'msg' => 'Case Daily Expense updated successfully!'
                                        ];
                                        $case_daily_expenses->conn->commit();
                                        http_response_code(200);
                                        echo json_encode($response);
                                    } else {
                                        $case_daily_expenses->conn->rollBack();
                                        throw new Exception("Error while saving bill file", 400);
                                    }
                                } else {
                                    $case_daily_expenses->conn->rollBack();
                                    throw new Exception("Error while uploading bill file", 400);
                                }
                            } else {
                                $case_daily_expenses->bill_path = $request->bill_path;
                                if ($case_daily_expenses->update_bill_path()) {
                                    $response = [
                                        'success' => 1,
                                        'code' => 200,
                                        'msg' => 'Case Daily Expense updated successfully!'
                                    ];
                                    $case_daily_expenses->conn->commit();
                                    http_response_code(200);
                                    echo json_encode($response);
                                } else {
                                    $case_daily_expenses->conn->rollBack();
                                    throw new Exception("Error while saving bill file", 400);
                                }
                            }
                        } else {
                            $case_daily_expenses->conn->rollBack();
                            throw new Exception("Error while Updating Case Daily Expense", 400);
                        }
                    } else if ($request->action == 'delete') {
                        $case_daily_expenses->case_daily_expense_id = $request->case_daily_expense_id;
                        $case_daily_expenses->updated_by = $user_id;
                        if ($case_daily_expenses->delete()) {
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Case Daily Expense successfully deleted!'
                            ];
                            $case_daily_expenses->conn->commit();
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception("Error while deleting Case Daily Expense", 1);
                        }
                    } else if ($request->action == 'get') {
                        $result = [];
                        if (isset($request->case_daily_expense_id) && $request->case_daily_expense_id > 0) {
                            $case_daily_expenses->case_daily_expense_id = $request->case_daily_expense_id;
                        }
                        $results = $case_daily_expenses->get($request);
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        foreach ($results as $res) {
                            ++$i;
                            // echo '../../rsp_files/'.$res['bill_path'];exit;
                            if(file_exists('../../rsp_files/'.$res['bill_path'])){
                                $a = "<a class='edit cursor-pointer' data-id='" . $res['case_daily_expense_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['case_daily_expense_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a style='cursor:pointer;' href='../rsp_files/".$res['bill_path']."' target='_blank' class='cursor-pointer text-warning'><i class='fa fa-download' aria-hidden='true'></i></a>";
                            } else {
                                $a = "<a class='edit cursor-pointer' data-id='" . $res['case_daily_expense_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['case_daily_expense_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>";
                            }
                            $couriers = (float)$res['courier_domestic']+(float)$res['courier_international'];
                            $total_amount = $couriers + (float)$res['photocopy']+(float)$res['hotel_stay']+(float)$res['conveyance']+(float)$res['air_ticket'];
                            $result[] = [
                                's_no'                      =>  $i,
                                'case_daily_expense_id'     =>  $res['case_daily_expense_id'],
                                'expense_date'              =>  date('d-m-Y', strtotime($res['expense_date'])),
                                'file_id'                   =>  $res['file_id'],
                                'file_no'                   =>  $res['file_no'],
                                'client_code_id'            =>  $res['client_code_id'],
                                'client_code'               =>  $res['client_code'],
                                'case_id'                   =>  $res['case_id'],
                                'case_no'                   =>  $res['case_no'],
                                'photocopy'                 =>  $res['photocopy'],
                                'courier_domestic'          =>  $res['courier_domestic'],
                                'courier_international'     =>  $res['courier_international'],
                                'hotel_stay'                =>  $res['hotel_stay'],
                                'stay_place'                =>  $res['stay_place'],
                                'hotelNarration'            =>  $res['hotelNarration'],
                                'hotelCalculat_bas'         =>  $res['hotelCalculat_bas'],
                                'stayWithAss'               =>  $res['stayWithAss'],
                                'conveyance'                =>  $res['conveyance'],
                                'total_amount'              =>  number_format($total_amount,2),
                                'courier'                   =>  number_format($couriers,2),
                                'air_ticket'                =>  $res['air_ticket'],
                                'airStay'                   =>  $res['airStay'],
                                'airAss'                    =>  $res['airAss'],
                                'airNarration'              =>  $res['airNarration'],
                                'airCalculat_bas'           =>  $res['airCalculat_bas'],
                                'oth_expense'               =>  $res['oth_expense'],
                                'bill_path'                 =>  $res['bill_path'],
                                'action'                    =>  $a
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $case_daily_expenses->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Case Daily Expense Data Fetch Successfully!',
                            'data'              => $result
                        ];
                        $case_daily_expenses->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'getSingle') {
                        $result = [];
                        $case_daily_expenses->case_daily_expense_id = $request->case_daily_expense_id;

                        $results = $case_daily_expenses->getSingle();
                        $i = 0;
                        $request->draw = 0;

                        foreach ($results as $res) {
                            ++$i;
                            $result[] = [
                                's_no'                      =>  $i,
                                'case_daily_expense_id'     =>  $res['case_daily_expense_id'],
                                'expense_date'              =>  date('Y-m-d', strtotime($res['expense_date'])),
                                'file_id'                   =>  $res['file_id'],
                                'file_no'                   =>  $res['file_no'],
                                'client_code_id'            =>  $res['client_code_id'],
                                'client_code'               =>  $res['client_code'],
                                'case_id'                   =>  $res['case_id'],
                                'case_no'                   =>  $res['case_no'],
                                'photocopy'                 =>  $res['photocopy'],
                                'courier_domestic'          =>  $res['courier_domestic'],
                                'courier_international'     =>  $res['courier_international'],
                                'hotel_stay'                =>  $res['hotel_stay'],
                                'stay_place'                =>  $res['stay_place'],
                                'hotelNarration'            =>  $res['hotelNarration'],
                                'hotelCalculat_bas'         =>  $res['hotelCalculat_bas'],
                                'stayWithAss'               =>  $res['stayWithAss'],
                                'conveyance'                =>  $res['conveyance'],
                                'air_ticket'                =>  $res['air_ticket'],
                                'airStay'                   =>  $res['airStay'],
                                'airAss'                    =>  $res['airAss'],
                                'airNarration'              =>  $res['airNarration'],
                                'airCalculat_bas'           =>  $res['airCalculat_bas'],
                                'oth_expense'               =>  $res['oth_expense'],
                                'bill_path'                 =>  $res['bill_path'],
                                'action'                    => "<a class='edit cursor-pointer' data-id='" . $res['case_daily_expense_id'] . "'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='" . $res['case_daily_expense_id'] . "'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $case_daily_expenses->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Case Daily Expense Data Fetch Successfully!',
                            'data'              => $result
                        ];
                        $case_daily_expenses->conn->commit();
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
    $case_daily_expenses->conn->rollBack();
    $response = [

        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code(500);
    echo json_encode($response);
} catch (Exception $e) {
    $case_daily_expenses->conn->rollBack();
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage(),
    ];
    http_response_code($e->getCode());
    echo json_encode($response);
}
