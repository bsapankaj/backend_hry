<?php
require_once '../third_party/dompdf/autoload.inc.php';
require_once '../helper/common.php';

use Dompdf\Dompdf;

function generate_cause_list($content = [])
{
    $dompdf = new Dompdf();
    $html = get_cause_list($content);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A3', 'landscape');
    $dompdf->render();
    $output = $dompdf->output();
    file_put_contents('../../download/cause_list.pdf', $output);
}

function get_cause_list($content = [])
{
    $html = '<style>
            table {
                font-size:16px;
                width:100%;
            }
            .data {
                border-spacing: 0px;
                border-collapse: collapse;
            }

            .data th, .data td {
                border: 1px solid black;
                padding: 5px;
            }

            .data table{
                border-spacing: 0px;
                border: 0px solid black !important;
                border-collapse: collapse;
            }

            .data table th, .data table td {
                border: 1px solid black !important;
                padding: 5px!important;
                margin: 0px!important;
            }
        </style>
            <table>
                <tr>
                    <td align=center>
                        <h3><b>' . $content['request_date'] . '</b></h3>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>';
    $html .= '</table>';
    $html .= '<table class="data">';
    $html .=    '<tr style="text-align:center;background-color:gray; height:40px;">
                    <td width="5%">COURT NO.</td>
                    <td width="10%">ITEM NO.</td>
                    <td width="50%">CAUSE TITLE</td>
                    <td width="15%">LAWYERS</td>
                    <td width="20%">APPEARANCE/REMARKS</td>
                </tr>';
    foreach ($content['data'] as $court_name => $d) {
        $html .=    '<tr style="text-align:center;background-color:gray; height:40px;">
                        <td></td>
                        <td></td>
                        <td>' . $court_name . '</td>
                        <td></td>
                        <td></td>
                    </tr>';
        $cause_data = get_cause_data($content['data'][$court_name]);

        $html .=    '<tr>
                        <td style="text-align:center">' . $cause_data['court_no'] . '</td>
                        <td style="text-align:center">' . $cause_data['item_no'] . '</td>
                        <td style="text-align:left">' . $cause_data['cause_title'] . '</td>
                        <td colspan="2" style="text-align:center; padding: 0px !important;"><table style="width:100% padding: 0px !important; margin:0px !important;">' . $cause_data['lawyer_table'] . '</table></td>
                    </tr>';
    }

    $html .= '</table>';
    return $html;
}

function get_cause_data($cause_data)
{
    $court_no = '';
    $item_no = '';
    $cause_title = '';
    $lawyers = '';
    $lawyer_table = '';
    $remarks = '';
    foreach ($cause_data as $value) {
        $court_no = $value['court_no'];
        $item_no = $value['item_no'];
        if ($value['case_no'] != '') {
            $cause_title = '<b>' . strtoupper($value['justice']) . '</b><br>' . $value['case_no'] . '<br>' . $value['short_title'];
        } else {
            $cause_title = '<b>' . strtoupper($value['justice']) . '</b><br>' . $value['file_no'] . '<br>' . $value['short_title'];
        }
        $lawyer_table .= '<tr><td width="43%" valign="top" align="center">'.strtoupper($value['name']).'</td><td width="57%">'.strtoupper($value['timesheet_description']).'</td></tr>';

        $lawyers .=  '<br>' . strtoupper($value['name']);
        $remarks .=  '<br>' . strtoupper($value['timesheet_description']);
    }
    $cause_output = [
        "court_no"      => $court_no,
        "item_no"       => $item_no,
        "cause_title"   => $cause_title,
        "lawyers"       => trim($lawyers, "<br>"),
        "remarks"       => trim($remarks, "<br>"),
        "lawyer_table"       => $lawyer_table,
    ];
    return $cause_output;
}
