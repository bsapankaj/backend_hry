<?php
require_once '../model/invoice.php';
require_once '../model/time_sheet.php';
require_once '../model/cause_list_detail.php';
require_once '../model/lawyer_advisory.php';
require_once '../model/case_daily_expense.php';
require_once '../helper/date.php';
@session_start();
$login_user_id = isset($_SESSION["hryS_user_id"]) ? $_SESSION["hryS_user_id"] : 0;

$invoice = new Invoice();
$time_sheet = new Time_sheet();
$cause_list_detail = new Cause_list_detail();
$lawyer_advisory = new Lawyer_advisory();
$case_daily_expense = new Case_daily_expenses();

$invoice->conn->beginTransaction();
try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                // ADD UPDATE DELETE FETCH GET_BY_ID PRINT_BILL FINAL_BILL
                if (isset($request->action)) {
                    if ($request->action == 'add') {
                        if (isset($request->file_id) && $request->file_id > 0) {
                            $time_sheet->file_id = $request->file_id;
                            $time_sheet->start_time = $request->start_date;
                            $time_sheet->end_time = $request->end_date;
                        }
                        $time_results = $time_sheet->fetch('0');
                        foreach ($time_results as $res) {
                            if ($res['fee'] <= 0) {
                                throw new Exception('Client rate is missing', 400);
                            }
                        }
                        $invoice->client_id = $request->client_id;
                        $invoice->file_id = $request->file_id;
                        $invoice->start_date = $request->start_date;
                        $invoice->end_date = $request->end_date;
                        $invoice->total = $request->total;
                        $max_digit = $invoice->max_id() + 1;
                        $prt_date = preg_replace('/[^\p{L}\p{N}\s]/u', '', date("d-m-Y", strtotime($request->bill_date)));
                        $invoice->clerkage = $request->clerkage;
                        $invoice->bill_no = 'RSPL' . '/00' . $max_digit . '/' . $prt_date;
                        $invoice->bill_date = date('Y-m-d', strtotime($request->bill_date));
                        $invoice->photocopy_charge = $request->photocopy_charges;
                        $invoice->int_courier_charges = $request->int_courier_charges;
                        $invoice->other_charges = $request->other_charges;
                        $invoice->grand_total = $request->grand_total;
                        $invoice->created_by = $login_user_id;
                        if (!$invoice->check()) {
                            $max_digit;
                            $invoice->insert();
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Bill successfully added!'
                            ];
                            $invoice->conn->commit();
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('Bill Already generated for the given period', 400);
                        }
                    } else if ($request->action == 'update') {
                        $invoice->total = $request->total;
                        $invoice->clerkage = $request->clerkage;
                        $prt_date = preg_replace('/[^\p{L}\p{N}\s]/u', '', date("d-m-Y", strtotime($request->bill_date)));
                        $invoice->bill_no = 'RSPL' . '/00' . $request->invoice_id . '/' . $prt_date;
                        $invoice->bill_date = date('Y-m-d', strtotime($request->bill_date));
                        $invoice->photocopy_charge = $request->photocopy_charges;
                        $invoice->int_courier_charges = $request->int_courier_charges;
                        $invoice->other_charges = $request->other_charges;
                        $invoice->grand_total = $request->grand_total;
                        $invoice->invoice_id = $request->invoice_id;
                        $invoice->updated_by = $login_user_id;
                        $invoice->update();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Billing information successfully Updated!'
                        ];
                        $invoice->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'delete') {
                        $invoice->invoice_id = $request->invoice_id;
                        $invoice->updated_by = $login_user_id;
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'Bill successfully deleted!'
                        ];
                        $invoice->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'get_by_id') {
                        if (isset($request->invoice_id) && $request->invoice_id > 0) {
                            $invoice->invoice_id = $request->invoice_id;
                            $response = [
                                'success'           => 1,
                                'code'              => 200,
                                'msg'               => 'Billing details!',
                                'data'              => $invoice->get()
                            ];
                            $invoice->conn->commit();
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('Invalid request', 400);
                        }
                    } else if ($request->action == 'fetch') {
                        $result = [];
                        if (isset($request->file_id) && $request->file_id > 0) {
                            $time_sheet->file_id = $request->file_id;
                            $time_sheet->start_time = $request->start_date;
                            $time_sheet->end_time = $request->end_date;
                        }
                        $results = $time_sheet->fetch('0');
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        $grand_total = 0;
                        $clerkage_per = 0;
                        $time_sheet_id = [];
                        // print_r($results);exit;
                        if (isset($results)  && count($results) > 0) {
                            foreach ($results as $res) {
                             
                                // print_r($res);exit;
                                ++$i;
                                $start_date = date('d-m-Y', strtotime($res['start_time']));
                                $end_date = date('d-m-Y', strtotime($res['end_time']));
                                $start_time = date('h A', strtotime($res['start_time']));
                                $end_time = date('h A', strtotime($res['end_time']));
                                if ($start_date == $end_date) {
                                    $date = $start_date;
                                    $time = $start_time . ' to ' . $end_time;
                                } else {
                                    $date = $start_date . ' to ' . $end_date;
                                    $time = $start_date . ' ' . $start_time . ' to ' . $end_date . ' ' . $end_time;
                                }
                                $duration = getDuration($res['total_time']);
                                $amount = $res['amount'];
                                if ($res['fee_type'] == "PerVisit") {
                                    $duration_text = 'Appearance<br/>(' . $res['per_hour_fee'] . ')';
                                    $time = '-';
                                } else {
                                    $duration_text = $duration . ' hour(s)<br/>(' . (float)$res['per_hour_fee'] . ' x ' . $duration . ')';
                                }

                                $grand_total += $amount;
                                $result[] = [
                                    's_no'              => $i,
                                    'date'              => $date,
                                    'description'       => $res['description'],
                                    'duration'          => $duration_text,
                                    'time'              => $time,
                                    'amount'            => $res['amount']
                                ];
                            }
                            $result[] = [
                                's_no'              => '',
                                'date'              => '',
                                'description'       => '',
                                'duration'          => '',
                                'time'              => '<b>Total</b>',
                                'amount'            => '<b id="total">' . $grand_total . '</b>'
                            ];
                            $clerkage_per = $res['clerkage'];
                            $clerkage_type = $res['clerkage_type'];
                            if ($clerkage_type == 'percentage') {
                                $clerkage = (float)($grand_total * $clerkage_per / 100);
                                $result[] = [
                                    's_no'              => '',
                                    'date'              => '',
                                    'description'       => '',
                                    'duration'          => '',
                                    'time'              => '<b>Clerkage(' . $clerkage_per . '%)</b>',
                                    'amount'            => '<input type="number" value="' . $clerkage . '" name="bill_clerkage" id="bill_clerkage" class="form-control" readonly="readonly">'
                                ];
                            } else {
                                $clerkage = 0;
                                $result[] = [
                                    's_no'              => '',
                                    'date'              => '',
                                    'description'       => '',
                                    'duration'          => '',
                                    'time'              => '<b>Clerkage</b>',
                                    'amount'            => '<input type="number" value="0" name="bill_clerkage" id="bill_clerkage" class="form-control">'
                                ];
                            }
                            $grand_total += $clerkage;
                            $result[] = [
                                's_no'              => '',
                                'date'              => '',
                                'description'       => '',
                                'duration'          => '',
                                'time'              => '<b>Bill Date :</b>',
                                'amount'            => '<div class="input-group date" id="bill_date_box" data-target-input="nearest"><input type="text" name="bill_date" id="bill_date" class="form-control datetimepicker-input" data-target="#bill_date" autocomplete="off"><div class="input-group-append" data-target="#bill_date" data-toggle="datetimepicker"><div class="input-group-text"><i class="fa fa-calendar"></i></div></div></div>'
                            ];
                            $result[] = [
                                's_no'              => '',
                                'date'              => '',
                                'description'       => '',
                                'duration'          => '',
                                'time'              => '<b>Photocopy Charges</b>',
                                'amount'            => '<input type="number" value="0" name="copy_chrg" id="copy_chrg" class="form-control">'
                            ];
                            $result[] = [
                                's_no'              => '',
                                'date'              => '',
                                'description'       => '',
                                'duration'          => '',
                                'time'              => '<b>International Courier Charges</b>',
                                'amount'            => '<input type="number" value="0" name="courier_chrg" id="courier_chrg" class="form-control">'
                            ];
                            $result[] = [
                                's_no'              => '',
                                'date'              => '',
                                'description'       => '',
                                'duration'          => '',
                                'time'              => '<b>Other Charges</b>',
                                'amount'            => '<input type="number" value="0" name="other_chrg" id="other_chrg" class="form-control">'
                            ];
                            $result[] = [
                                's_no'              => '',
                                'date'              => '',
                                'description'       => '',
                                'duration'          => '',
                                'time'              => '<b>Grand Total</b>',
                                'amount'            => '<b id="grand_total">' . ($grand_total) . '</b>'
                            ];
                            $response = [
                                'success'           => 1,
                                'code'              => 200,
                                'msg'               => 'Billing details!',
                                'time_sheet_id'      => $time_sheet_id,
                                'data'              => $result
                            ];
                            $invoice->conn->commit();
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('No Data found for bill generation', 400);
                        }
                    } else if ($request->action == 'fetch_new') {
                        $result = [];
                        if (isset($request->file_id) && $request->file_id > 0) {
                            $time_sheet->file_id = $request->file_id;
                            $time_sheet->start_time = $request->start_date;
                            $time_sheet->end_time = $request->end_date;
                        }
                        $time_results = $time_sheet->fetch('0');
                        $lawyer_advisory_results = $lawyer_advisory->get_lawyer_advisory_by_time($request);
                        $case_daily_expense_results = $case_daily_expense->get_daily_expense_by_time($request);
                        // $cause_results = $cause_list_detail->get_cause_list_by_time($request);
                        if (isset($request->file_id) && $request->file_id > 0) {
                            $time_sheet->file_id = $request->file_id;
                            $time_sheet->start_time = $request->start_date;
                            $time_sheet->end_time = $request->end_date;
                        }
                        if (isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i = 0;
                            $request->draw = 0;
                        }
                        $grand_total = 0;
                        $clerkage_per = 0;
                        $clerkage_type = '';
                        $time_sheet_id = [];
                        $cause_list_id = [];
                        $cause_list_detail_id = [];
                        $lawyer_advisory_id = [];
                        $case_daily_expense_id = [];
                        if ((isset($time_results)  && count($time_results) > 0) || (isset($case_daily_expense_results)  && count($case_daily_expense_results) > 0) || (isset($cause_results)  && count($cause_results) > 0)) {

                            if (isset($time_results)  && count($time_results) > 0) {
                                foreach ($time_results as $res) {
                                    ++$i;
                                    $start_date = date('d-m-Y', strtotime($res['start_time']));
                                    $end_date = date('d-m-Y', strtotime($res['end_time']));
                                    $start_time = date('h A', strtotime($res['start_time']));
                                    $end_time = date('h A', strtotime($res['end_time']));
                                    if ($start_date == $end_date) {
                                        $date = $start_date;
                                        $time = $start_time . ' to ' . $end_time;
                                    } else {
                                        $date = $start_date . ' to ' . $end_date;
                                        $time = $start_date . ' ' . $start_time . ' to ' . $end_date . ' ' . $end_time;
                                    }
                                    $duration = getDuration($res['total_time']);
                                    if ($res['unit'] == 1) {
                                        $duration_text = 'Activity<br/>(' . $res['fee'] . ')';
                                        $time = '-';
                                        $amount = $res['fee'];
                                    } else {
                                        $amount = $duration * $res['fee'];
                                        $duration_text = $duration . ' hour(s)<br/>(' . (float)$res['fee'] . ' x ' . $duration . ')';
                                    }

                                    $grand_total += $amount;
                                    $result[] = [
                                        's_no'              => $i,
                                        'date'              => $date,
                                        'description'       => $res['particulars'],
                                        'duration'          => $duration_text,
                                        'time'              => $time,
                                        'amount'            => number_format((float)$amount, 2, '.', '')
                                    ];
                                }
                                $clerkage_per = $res['clerkage'];
                                $clerkage_type = $res['clerkage_type'];
                            }else{
                                throw new Exception('No Time Sheet Data found for bill generation', 400);
                            }

                            if (isset($lawyer_advisory_results)  && count($lawyer_advisory_results) > 0) {
                                foreach ($lawyer_advisory_results as $res) {
                                    array_push($lawyer_advisory_id, $res['lawyer_advisory_id']);
                                    ++$i;
                                    $date = date('d-m-Y', strtotime($res['date']));
                                    $description = 'Lawyer Advisory by ' . $res['lawyer_name'];
                                    $duration_text = 'Advisory<br/>(' . $res['fee'] . ')';
                                    $amount = $res['fee'];
                                    $grand_total += $amount;
                                    $result[] = [
                                        's_no'              => $i,
                                        'date'              => $date,
                                        'description'       => $description,
                                        'duration'          => $duration_text,
                                        'time'              => $time,
                                        'amount'            => number_format((float)$amount, 2, '.', '')
                                    ];
                                }
                            }

                            if (isset($case_daily_expense_results)  && count($case_daily_expense_results) > 0) {
                                $expense['Photocopy'] = 0;
                                $expense['Courier Domestic'] = 0;
                                $expense['Courier International'] = 0;
                                $expense['Hotel stay'] = 0;
                                $expense['Conveyance'] = 0;
                                $expense['Air ticket'] = 0;
                                $expense['Other expense'] = 0;
                                foreach ($case_daily_expense_results as $res) {
                                    $expense['Photocopy'] += $res['photocopy'];
                                    $expense['Courier Domestic'] += $res['courier_domestic'];
                                    $expense['Courier International'] += $res['courier_international'];
                                    $expense['Hotel stay'] += $res['hotel_stay'];
                                    $expense['Conveyance'] += $res['conveyance'];
                                    $expense['Air ticket'] += $res['air_ticket'];
                                    $expense['Other expense'] += $res['oth_expense'];
                                }
                                foreach ($expense as $k => $value) {
                                    if ($value > 0) {
                                        $grand_total += $value;
                                        $i++;
                                        $result[] = [
                                            's_no'              => '',
                                            'date'              => '',
                                            'description'       => '',
                                            'duration'          => '',
                                            'time'              => $k,
                                            'amount'            => '<input style="padding:0px; text-align:right;" type="number" name="" id="" value="' . number_format((float)$value, 2, '.', '') . '" class="invoice_expense form-control" disabled>',
                                        ];
                                    }
                                }
                            }

                            $result[] = [
                                's_no'              => '',
                                'date'              => '',
                                'description'       => '',
                                'duration'          => '',
                                'time'              => '<b>Total</b>',
                                'amount'            => '<b id="total">' . number_format((float)$grand_total, 2, '.', '') . '</b>'
                            ];

                            if ($clerkage_type == 'percentage') {
                                $clerkage = (float)($grand_total * $clerkage_per / 100);
                                $result[] = [
                                    's_no'              => '',
                                    'date'              => '',
                                    'description'       => '',
                                    'duration'          => '',
                                    'time'              => '<b>Clerkage(' . $clerkage_per . '%)</b>',
                                    'amount'            => '<input style="padding:0px; text-align:right;" type="number" value="' . number_format((float)$clerkage, 2, '.', '') . '" name="bill_clerkage" id="bill_clerkage" class="form-control" readonly="readonly">'
                                ];
                            } else {
                                $clerkage = 0;
                                $result[] = [
                                    's_no'              => '',
                                    'date'              => '',
                                    'description'       => '',
                                    'duration'          => '',
                                    'time'              => '<b>Clerkage</b>',
                                    'amount'            => '<input style="padding:0px; text-align:right;" type="number" value="0" name="bill_clerkage" id="bill_clerkage" class="form-control"> '
                                ];
                            }
                            $grand_total += $clerkage;
                            $result[] = [
                                's_no'              => '',
                                'date'              => '',
                                'description'       => '',
                                'duration'          => '',
                                'time'              => '<b>Bill Date :</b>',
                                'amount'            => '<div class="input-group date" id="bill_date_box" data-target-input="nearest"><input type="text" name="bill_date" id="bill_date" class="form-control datetimepicker-input" data-target="#bill_date" autocomplete="off"><div class="input-group-append" data-target="#bill_date" data-toggle="datetimepicker"><div class="input-group-text"><i class="fa fa-calendar"></i></div></div></div>'
                            ];
                            // $result[] = [
                            //     's_no'              => '',
                            //     'date'              => '',
                            //     'description'       => '',
                            //     'duration'          => '',
                            //     'time'              => '<b>Photocopy Charges</b>',
                            //     'amount'            => '<input type="number" value="0" name="copy_chrg" id="copy_chrg" class="form-control">'
                            // ];
                            // $result[] = [
                            //     's_no'              => '',
                            //     'date'              => '',
                            //     'description'       => '',
                            //     'duration'          => '',
                            //     'time'              => '<b>International Courier Charges</b>',
                            //     'amount'            => '<input type="number" value="0" name="courier_chrg" id="courier_chrg" class="form-control">'
                            // ];
                            // $result[] = [
                            //     's_no'              => '',
                            //     'date'              => '',
                            //     'description'       => '',
                            //     'duration'          => '',
                            //     'time'              => '<b>Other Charges</b>',
                            //     'amount'            => '<input type="number" value="0" name="other_chrg" id="other_chrg" class="form-control">'
                            // ];
                            $result[] = [
                                's_no'              => '',
                                'date'              => '',
                                'description'       => '',
                                'duration'          => '',
                                'time'              => '<b>Grand Total</b>',
                                'amount'            => '<b id="grand_total">' . number_format((float)$grand_total, 2, '.', '') . '</b>'
                            ];
                            $response = [
                                'success'           => 1,
                                'code'              => 200,
                                'msg'               => 'Billing details!',
                                'time_sheet_id'      => $time_sheet_id,
                                'data'              => $result
                            ];
                            $invoice->conn->commit();
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('No Data found for bill generation', 400);
                        }
                    } else if ($request->action == 'billing_data') {
                        $results =  $invoice->get();
                        $data = [];
                        if (isset($results) && count($results) > 0) {
                            $i = 0;
                            foreach ($results as $result) {
                                if ($result['is_final'] == 1) {
                                    $action = '<button type="button" disabled class="btn btn-block bg-gradient-secondary btn-xs edit disabled">Edit</button>';
                                    $action .= '<button style="margin-top:.5rem" type="button" class="btn btn-block bg-gradient-secondary btn-xs print" data-invoice_id="';
                                    $action .= $result['invoice_id'];
                                    $action .= '">Print</button>';

                                    $action .= '<button type="button" disabled class="btn btn-block bg-gradient-secondary btn-xs final disabled">Final</button>';
                                } else {
                                    $action  = '<a href="billing_new.php?id=';
                                    $action .= $result['invoice_id'];
                                    $action .= '">';
                                    $action .= '<button type="button" class="btn btn-block bg-gradient-secondary btn-xs edit">Edit</button>';
                                    $action .= '</a>';
                                    $action .= '<button style="margin-top:.5rem" type="button" class="btn btn-block bg-gradient-secondary btn-xs print" data-invoice_id="';
                                    $action .= $result['invoice_id'];
                                    $action .= '">Print</button>';

                                    $action .= '<button type="button" class="btn btn-block bg-gradient-secondary btn-xs final" data-invoice_id="';
                                    $action .= $result['invoice_id'];
                                    $action .= '">Final</button>';
                                }

                                $i++;
                                $data[] = [
                                    's_no'          => $i,
                                    'bill_no'       => $result['bill_no'],
                                    'bill_date'     => $result['bill_date'],
                                    'file_no'     => $result['file_no'],
                                    'client_name'   => $result['client_name'],
                                    'case_no'       => $result['case_no'],
                                    'bill_period'   => date('d-m-Y', strtotime($result['start_date'])) . ' to' . date('d-m-Y', strtotime($result['end_date'])),
                                    'amount'        => $result['grand_total'],
                                    'action'        => $action
                                ];
                            }
                        }
                        $response = [
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'Billing details!',
                            'data'              => $data
                        ];
                        $invoice->conn->commit();
                        http_response_code(200);
                        echo json_encode($response);
                    } else if ($request->action == 'print') {
                        if (isset($request->invoice_id) && $request->invoice_id > 0) {
                            $invoice->invoice_id = $request->invoice_id;
                            $invoice_data = $invoice->get();
                            if (count($invoice_data) > 0) {
                                $invoice_obj = (object)$invoice_data[0];
                                $time_sheet->file_id = $invoice_obj->file_id;
                                $time_sheet->start_time = $invoice_obj->start_date;
                                $time_sheet->end_time = $invoice_obj->end_date;
                                $time_sheet_results = $time_sheet->fetch(1);
                                $lawyer_advisory_results = $lawyer_advisory->get_lawyer_advisory_by_time($invoice_obj);
                                $case_daily_expense_results = $case_daily_expense->get_daily_expense_by_time($invoice_obj);

                                require_once 'print_bill.php';
                                if (!empty($invoice_obj->case_detail)) {
                                    $sub = $invoice_obj->case_detail;
                                } else {
                                    $sub = $invoice_obj->file_title;
                                }

                                $content = [
                                    'client_address'                => $invoice_obj->client_address,
                                    'bill_no'                       => $invoice_obj->bill_no,
                                    'case_detail'                   => $sub,
                                    'case_clerkage'                 => $invoice_obj->case_clerkage,
                                    'clerkage'                      => $invoice_obj->clerkage,
                                    'clerkage_rate'                 => $invoice_obj->clerkage_rate,
                                    'total'                         => $invoice_obj->total,
                                    'photocopy_charges'             => $invoice_obj->photocopy_charges,
                                    'int_courier_charges'           => $invoice_obj->int_courier_charges,
                                    'other_charge'                  => $invoice_obj->other_charge,
                                    'grand_total'                   => $invoice_obj->grand_total,
                                    'gst_no'                        => $invoice_obj->gst_no,
                                    'invoice_print_type'            => $request->invoice_print_type,
                                    'print_own_address'             => $request->print_own_address,
                                    'gst_on_bill'                   => $invoice_obj->gst_on_bill,
                                    'bill_date'                     => date('d F Y', strtotime($invoice_obj->bill_date)),
                                    'time_sheet_results'            => $time_sheet_results,
                                    'lawyer_advisory_results'       => $lawyer_advisory_results,
                                    'case_daily_expense_results'    => $case_daily_expense_results
                                ];
                                generate_bill($content);

                                $url = '../download/bill.pdf';
                                $response = [
                                    'success'           => 1,
                                    'code'              => 200,
                                    'msg'               => 'PDF Generated!',
                                    'data'              => $url
                                ];
                                $invoice->conn->commit();
                                http_response_code(200);
                                echo json_encode($response);
                            } else {
                                throw new Exception('Something went wrong', 400);
                            }
                        } else {
                            throw new Exception('Invalid request', 400);
                        }
                    } else if ($request->action == 'final') {
                        if (isset($request->invoice_id) && $request->invoice_id > 0) {
                            $invoice->invoice_id = $request->invoice_id;

                            $invoice_data = $invoice->get();
                            if (count($invoice_data) > 0) {
                                $invoice_obj = (object)$invoice_data[0];

                                $time_sheet->invoice_id = $request->invoice_id;
                                $time_sheet->file_id = $invoice_obj->file_id;
                                $time_sheet->start_time = $invoice_obj->start_date;
                                $time_sheet->end_time = $invoice_obj->end_date;
                                $time_sheet->updated_by = $login_user_id;

                                $lawyer_advisory->invoice_id = $request->invoice_id;
                                $lawyer_advisory->file_id = $invoice_obj->file_id;
                                $lawyer_advisory->start_time = $invoice_obj->start_date;
                                $lawyer_advisory->end_time = $invoice_obj->end_date;
                                $lawyer_advisory->updated_by = $login_user_id;

                                $case_daily_expense->invoice_id = $request->invoice_id;
                                $case_daily_expense->file_id = $invoice_obj->file_id;
                                $case_daily_expense->start_time = $invoice_obj->start_date;
                                $case_daily_expense->end_time = $invoice_obj->end_date;
                                $case_daily_expense->updated_by = $login_user_id;

                                $time_sheet->update_invoice_no();
                                $lawyer_advisory->update_invoice_no();
                                $case_daily_expense->update_invoice_no();

                                if ($invoice->final_invoice()) {
                                    $response = [
                                        'success'           => 1,
                                        'code'              => 200,
                                        'msg'               => 'Invoice finalized!',
                                        'data'              => []
                                    ];
                                    $invoice->conn->commit();
                                    http_response_code(200);
                                    echo json_encode($response);
                                } else {
                                    throw new Exception('Something went wrong', 500);
                                }
                            } else {
                                throw new Exception('Something went wrong', 500);
                            }
                        } else {
                            throw new Exception('Invalid request', 400);
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
    $invoice->conn->rollBack();
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code(500);
    echo json_encode($response);
} catch (Exception $e) {
    $invoice->conn->rollBack();
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code($e->getCode());
    echo json_encode($response);
}
