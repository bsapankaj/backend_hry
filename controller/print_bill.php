<?php
require_once '../third_party/dompdf/autoload.inc.php';
require_once '../helper/common.php';

use Dompdf\Dompdf;

function generate_bill($content = [])
{
    $dompdf = new Dompdf();
    $html = get_billing_html($content);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    //$dompdf->stream();
    //$dompdf->stream('my.pdf',array('Attachment'=>0));
    $output = $dompdf->output();
    file_put_contents('../../download/bill.pdf', $output);
}

function get_billing_html($content = [])
{
    // print_r($content);
    // exit;
    $prt_gst_no = '';
    $igst = false;
    $sgst = false;
    $cgst = false;
    if (isset($content['gst_no']) && $content['gst_no'] != '') {
        $prt_gst_no = 'GST: ' . $content['gst_no'];
        if (isset($content['gst_on_bill']) && $content['gst_on_bill'] == 1) {
            if (substr($content['gst_no'], 0, 2) == '07') {
                $cgst = true;
                $sgst = true;
            } else {
                $igst = true;
            }
        }
    } else {
        $prt_gst_no = '';
    }

    $html = '<style>
            table {
                font-size:12px;
            }
            .data {
                border-spacing: 0px;
                border-collapse: collapse;
            }

            .data th, .data td {
                border: 1px solid black;
                padding: 5px;
            }
        </style>
        <table style="width: 100%;">
            <tr>
                <td colspan=3">
                    <h3><b>RS PRABHU</b></h3>
                </td>
            </tr>
            <tr height="5">
                <td colspan=3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" align="center"><u><b>MEMO OF FEES</b></u></td>
            </tr>';
    if ($content['print_own_address'] == 'Y') {
        $html .= '<tr>
                <td colspan="3" >
                    <table style="width: 100%;">
                        <tr>
                            <td valign="top" width="30%">
                            R.S. Prabhu & Co.LLP<br>
                            j-15,Jangpura Extension,<br>
                            New Delhi-110014<br>
                            PAN No. AAYFR2865G<br>
                            BAR Council No. D/462/1984<br>
                            State Name: Delhi<br>
                            State Code: 07
                            </td>
                            <td valign="top" width="50%">&nbsp;</td>
                            <td valign="top" nowrap="nowrap">&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>';
    }

    $html .= '<tr>
                <td colspan="3" >
                    <table style="width: 100%;">
                        <tr>
                            <td valign="top" width="30%">' . $content['client_address'] . '<br>' . $prt_gst_no . '</td>
                            <td valign="top" width="50%">&nbsp;</td>
                            <td valign="top" nowrap="nowrap">Memo No: ' . $content['bill_no'] . '<br />' . $content['bill_date'] . '</td>
                        </tr>
                    </table>
                </td>
            </tr>';
    $html .= '<tr height="3">
                <td colspan="3" >&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3" >
                    <table style="width: 100%;">
                        <tr>
                            <td valign="top" style="text-align:left; width: 90%;"><b> Sub: &nbsp;&nbsp;&nbsp;' . $content['case_detail'] . '</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr height="3">
                <td colspan=3">&nbsp;</td>
            </tr>
            <tr>';
    if ($content['invoice_print_type'] == 'service_type') {
        $html .= '<td colspan="3">
                    <table class="data" width="100%">
                        <tr valign="top" valign="middle" bgcolor="#ADD8E6">
                            <th colspan="5"><b>Service Description</b></th>
                            <th><b>Amount</b></th>
                        </tr>';
    } else {
        $html .= '<td colspan="3">
                    <table class="data" width="100%">
                        <tr valign="top" valign="middle" bgcolor="#ADD8E6">
                            <th style="height:50px;"><b>S.No.</b></th>
                            <th><b>Date</b></th>
                            <th><b>Particulars</b></th>
                            <th><b>Duration</b></th>
                            <th><b>Time</b></th>
                            <th><b>Amount</b></th>
                        </tr>';
    }

    $sno = 0;
    $grand_total = 0;
    $clerkage_per = 0;
    $print_clerkage_type = '';
    $clerkage_type = $content['case_clerkage'];
    if ($clerkage_type == 'percentage') {
        $print_clerkage_type = '(' . $content['clerkage_rate'] . '%)';
    }
    $clerkage_per = $content['clerkage'];
    if (count($content['time_sheet_results']) > 0 ) {
        if (count($content['time_sheet_results']) > 0) {
            $time_sheet_results = [];
            foreach ($content['time_sheet_results'] as $res) {
                $sno++;
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

                $grand_total += $amount;
                $time_sheet_results[] = [
                    's_no'              => $sno,
                    'date'              => $date,
                    'particulars'       => $res['particulars'],
                    'duration'          => $duration_text,
                    'time'              => $time,
                    'amount'            => $amount
                ];
            }
            // $html .= '<tr style="color:red;text-align:center;"><td colspan="6">TIME SHEET</td></tr>';
            foreach ($time_sheet_results as $res) {
                if ($content['invoice_print_type'] == 'service_type') {
                    $html .= '<tr>';
                    $html .= '<td td colspan="5">' . $res['particulars'] . '</td>';
                    $html .= '<td align="right">' . $res['amount'] . '</td>';
                    $html .= '</tr>';
                } else {
                    $html .= '<tr>';
                    $html .= '<td align="center">' . $res['s_no'] . '</td>';
                    $html .= '<td align="center">' . $res['date'] . '</td>';
                    $html .= '<td>' . $res['particulars'] . '</td>';
                    $html .= '<td align="center">' . $res['duration'] . '</td>';
                    $html .= '<td align="center">' . $res['time'] . '</td>';
                    $html .= '<td align="right">' . $res['amount'] . '</td>';
                    $html .= '</tr>';
                }
            }
        }

        if (count($content['lawyer_advisory_results']) > 0) {
            $lawyer_advisory_results = [];
            foreach ($content['lawyer_advisory_results'] as $res) {
                $sno++;
                $date = date('d-m-Y', strtotime($res['date']));
                $description = 'Lawyer Advisory by ' . $res['lawyer_name'];
                $duration_text = 'Advisory<br/>(' . $res['fee'] . ')';
                $amount = $res['fee'];
                $grand_total += $amount;
                $lawyer_advisory_results[] = [
                    's_no'              => $sno,
                    'date'              => $date,
                    'description'       => $description,
                    'duration'          => $duration_text,
                    'time'              => $time,
                    'amount'            => number_format((float)$amount, 2, '.', '')
                ];
            }
            // $html .= '<tr style="color:red;text-align:center;"><td colspan="6">SENIOR LAWYER ADVISORY</td></tr>';
            foreach ($lawyer_advisory_results as $res) {
                if ($content['invoice_print_type'] == 'service_type') {
                    $html .= '<tr>';
                    $html .= '<td td colspan="5">' . $res['description'] . '</td>';
                    $html .= '<td align="right">' . $res['amount'] . '</td>';
                    $html .= '</tr>';
                } else {
                    $html .= '<tr>';
                    $html .= '<td align="center">' . $res['s_no'] . '</td>';
                    $html .= '<td align="center">' . $res['date'] . '</td>';
                    $html .= '<td>' . $res['description'] . '</td>';
                    $html .= '<td align="center">' . $res['duration'] . '</td>';
                    $html .= '<td align="center">' . $res['time'] . '</td>';
                    $html .= '<td align="right">' . $res['amount'] . '</td>';
                    $html .= '</tr>';
                }
            }
        }

        if (count($content['case_daily_expense_results']) > 0) {
            $expense['Photocopy'] = 0;
            $expense['Courier Domestic'] = 0;
            $expense['Courier International'] = 0;
            $expense['Hotel stay'] = 0;
            $expense['Conveyance'] = 0;
            $expense['Air ticket'] = 0;
            $expense['Oth expense'] = 0;
            $case_daily_expense_results = [];
            foreach ($content['case_daily_expense_results'] as $res) {
                $expense['Photocopy'] += $res['photocopy'];
                $expense['Courier Domestic'] += $res['courier_domestic'];
                $expense['Courier International'] += $res['courier_international'];
                $expense['Hotel stay'] += $res['hotel_stay'];
                $expense['Conveyance'] += $res['conveyance'];
                $expense['Air ticket'] += $res['air_ticket'];
                $expense['Oth expense'] += $res['oth_expense'];
            }
            foreach ($expense as $k => $value) {
                if ($value > 0) {
                    $sno++;
                    $case_daily_expense_results[] = [
                        's_no'              => $sno,
                        'date'              => '',
                        'description'       => $k,
                        'duration'          => '',
                        'time'              => '',
                        'amount'            => number_format((float)$value, 2, '.', '')
                    ];
                }
            }
            // $html .= '<tr style="color:red;text-align:center;"><td colspan="6">CASE DAILY EXPENSE</td></tr>';
            foreach ($case_daily_expense_results as $res) {

                if ($content['invoice_print_type'] == 'service_type') {
                    $html .= '<tr>';
                    $html .= '<td td colspan="5">' . $res['description'] . '</td>';
                    $html .= '<td align="right">' . $res['amount'] . '</td>';
                    $html .= '</tr>';
                } else {
                    $html .= '<tr>';
                    $html .= '<td align="center">' . $res['s_no'] . '</td>';
                    $html .= '<td align="center">' . $res['date'] . '</td>';
                    $html .= '<td>' . $res['description'] . '</td>';
                    $html .= '<td align="center">' . $res['duration'] . '</td>';
                    $html .= '<td align="center">' . $res['time'] . '</td>';
                    $html .= '<td align="right">' . $res['amount'] . '</td>';
                    $html .= '</tr>';
                }
            }
        }
        $html .= '<tr>';
        $html .= '<td colspan="5"><b>Total</b></td>';
        $html .= '<td align="right"><b>' . $content['total'] . '</b></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="5"><b>Clerkage' . $print_clerkage_type . '</b></td>';
        $html .= '<td align="right"><b>' . $content['clerkage'] . '</b></td>';
        $html .= '</tr>';
        // $html .= '<tr>';
        // $html .= '<td colspan="5"><b>Photocopy Charges</b></td>';
        // $html .= '<td align="right"><b>' . $content['photocopy_charges'] . '</b></td>';
        // $html .= '</tr>';
        // $html .= '<tr>';
        // $html .= '<td colspan="5"><b>International Courier Charges</b></td>';
        // $html .= '<td align="right"><b>' . $content['int_courier_charges'] . '</b></td>';
        // $html .= '</tr>';
        // $html .= '<tr>';
        // $html .= '<td colspan="5"><b>Other Charges</b></td>';
        // $html .= '<td align="right"><b>' . $content['other_charge'] . '</b></td>';
        // $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="5"><b>Grand Total</b></td>';
        $html .= '<td align="right"><b>' . $content['grand_total'] . '</b></td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td colspan="6">(' . getIndianCurrency($content['grand_total']) . ' only)</td>';
        $html .= '</tr>';
        if ($igst) {
            $igst_amount = ($content['grand_total'] / 100) * 18;
            $html .= '<tr>';
            $html .= '<td colspan="5"><b>IGST(18%)</b></td>';
            $html .= '<td align="right"><b>' . $igst_amount . '</b></td>';
            $html .= '</tr>';
        } else if ($sgst && $cgst) {
            $sgst_amount = ($content['grand_total'] / 100) * 9;
            $cgst_amount = ($content['grand_total'] / 100) * 9;
            $html .= '<tr>';
            $html .= '<td colspan="5"><b>CGST(9%)</b></td>';
            $html .= '<td align="right"><b>' . $sgst_amount . '</b></td>';
            $html .= '</tr>';
            $html .= '<tr>';
            $html .= '<td colspan="5"><b>SGST(9%)</b></td>';
            $html .= '<td align="right"><b>' . $cgst_amount . '</b></td>';
            $html .= '</tr>';
        }
        $html .= '<tr>';
        $html .= '<td colspan="6">GST to be paid by service by recipient by virtue of Notifiacton No. 13/2017 dated 28th June 2017 (Sr. No. 2)</td>';
        $html .= '</tr>';
    } else {
        $html .= '<tr><td colspan="6" align="center"> No Data Available</td></tr>';
    }
    $html .= '
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" >
                    <table style="width: 100%;">
                        <tr>
                            <td valign="top" align="center"><b>PAN NO. AAYRF2865G</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" >
                    <table style="width: 100%;">
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" >
                    <table style="width: 100%;">
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" >
                    <table style="width: 100%;">
                        <tr>
                            <td width="30%">&nbsp;</td>
                            <td width="40%">&nbsp;</td>
                            <td width="30%" valign="top" align="left">KR SASIPRABHU</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" >
                    <table style="width: 100%;">
                        <tr>
                            <td valign="top" align="center"><b>BANK ACCOUNT DETAILS</b></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" >
                    <table class="data" style="width: 100%;">
                        <tr align="center">
                            <td valign="top">BENEFICIARY NAME</td>
                            <td valign="top">PAN NO.</td>
                            <td valign="top">BANK</td>
                            <td valign="top">ACCOUNT NO.</td>
                            <td valign="top">IFSC CODE</td>
                            <td valign="top">SWIFT CODE</td>
                        </tr>
                        <tr align="center">
                            <td>RS PRABHU & COMPANY LLP</td>
                            <td>AAYFR2865G</td>
                            <td>HDFC Sector 1, Noida 201301,India</td>
                            <td>50200034055916</td>
                            <td>HDFC0001897</td>
                            <td>HDFCINBB</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="3" >
                    <table style="width: 100%;">
                        <tr>
                            <td valign="top" align="center"><u>IF PAYMENT IS BEING MADE ONLINE, PLEASE QUOTE THE MEMO NUMBER AND SUBJECT MATTER</u></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>';
    return $html;
}
