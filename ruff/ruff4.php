<?php
include "../header/header.php";
?>
<link rel="stylesheet" href="../css/jquery.iviewer.css"/>
<link href="../css/jquery.magnify.css" rel="stylesheet">
<style>
    .viewer
    {
        width: 100%;
        height: 200px;
        border: 1px solid black;
        position: relative;
    }

    .wrapper
    {
        overflow: hidden;
    }
</style>
<style>
    fieldset{
        padding-left: .65em;
        padding-right: .65em;
        padding-bottom: 10px;
        margin: 0 2px;
        border: 1px solid #2980B9;
    }
    legend {
        display: block;
        width: auto;
        padding: 0;
        margin-bottom: 20px;
        font-size: 15px;
        font-weight: bold;
        line-height: inherit;
        color: #333;
        border: 0;

    }
/*    .view_table>tbody>tr>td,
    .view_table>tbody>tr>th, 
    .view_table>tfoot>tr>td, 
    .view_table>tfoot>tr>th, 
    .view_table>thead>tr>td, 
    .view_table>thead>tr>th {
        padding: 4px;
        vertical-align: top;
        border: 1px solid #ddd;
    }*/
</style>
<!-- Right side column. Contains the navbar and content of the page -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Operation
            <small>Audit</small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Operation</a></li>
            <li class="active">Audit</li>
        </ol>
    </section>
    <section style="padding-bottom: 50px;" class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="row">
                            <div style="border:1px solid #ccc; border-radius: 10px; padding: 10px;" class="col-md-8 col-md-offset-2">
                                <div class="form-group col-md-6">
                                    <label>Select Collection Date </label>
                                    <input type="text" name="from_collection_date" id="from_collection_date" class="form-control Last15DaysAllowed" value="<?php echo date('Y-m-d') ?>" readonly style="background-color: white"/>
                                </div>
                                <div class="form-group col-md-6">
                                    <button id="get_grid_data" style="margin-top:18px;" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <div class="box-body">
                        <div class="col-md-12">
                            <table id="userTable" class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>S No</th>
                                        <th>Card No</th>
                                        <th>Client Code</th>
                                        <th>Customer Name</th>
                                        <th>Box No</th>
                                        <th>Address</th>
                                        <th>Area</th>
                                        <th>Contact Person</th>
                                        <th>#</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="auditModal" class="modal" data-easein="bounceUpIn"  tabindex="-1" role="dialog" aria-labelledby="costumModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close exit" data-dismiss="modal" aria-hidden="true">×</button>
                    <h6 class="modal-title">Audit</h6>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="view_table" style="width:100%" border="1">
                                <tr>
                                    <th colspan="3" style="text-align: center; background-color: #2980B9; color:white"><b>Customer Information</b></th>
                                </tr>
                                <tr>
                                    <td><b>Client Code : </b><span id="client_code"></span></td>
                                    <td><b>Card No : </b><span id="card_no"></span></td>
                                    <td><b>Box No : </b><span id="boxno"></span></td>
                                </tr>
                                <tr>
                                    <td><b>Location Name : </b><span id="customer_name"></span></td>
                                    <td colspan="2"><b>Address : </b><span id="address"></span></td>
                                </tr>
                                <tr>
                                    <td><b>Service Provider : </b><span id="franchisee_name"></span></td>
                                    <td colspan="2"><b>FE Name : </b><span id="fe_name"></span></td>
                                </tr>
                                <tr>
                                    <td><b>Contact Person : </b><span id="contact_person"></span></td>
                                    <td colspan="2"><b>Contact No : </b><span id="contact_no"></span></td>
                                </tr>
                                <tr>
                                    <th colspan="2" style="text-align: center; background-color: #2980B9; color:white"><b>Feedback</b></th>
                                    <th style="text-align: center; background-color: #2980B9;" id="color"></th>                               
                                </tr>
                                <tr>
                                    <td width="50%"><b>Transaction Date : </b><span id="transaction_date"></span></td>
                                    <td><b>Is Collected : </b><span id="is_cheque_collected"></span></td>
                                    <td><b>Reason : </b><span id="reason"></span></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><b>Cheque Count : </b><span id="cheque_count"></span></td>
                                    <td><b>Envelope Count : </b><span id="envelope_count"></span></td>
                                    <td><b>Open Doc. Count : </b><span id="open_doc_count"></span></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><b>Other Count : </b><span id="other_count"></span></td>
                                    <td colspan="2"><b>Grand Total : </b><span id="grand_total"></span></td>

                                </tr>

                                <tr style="display: none;">
                                    <td><b>Receiving Mode : </b><span id="receving_type"></span></td>
                                    <td colspan="2"><b>Receiving Mode Category <span style="font-size: 10px;">(if Manual) </span> : </b><span id="manual_mode"></span></td>

                                </tr>
                                <tr style="display: none;">
                                    <td><b>Physical Received : </b><span id="physical_received"></span></td>
                                    <td colspan="2"><b>Mismatch Count : </b><span id="mismatch_count"></span></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><b>Type Of Dropbox : </b><span id="type_of_dropbox"></span></td>
                                    <td colspan="2"><b>Name Of Person <span style="font-size: 10px;">(If Dropbox Is Person)</span> : </b><span id="name_of_person_when_dropbox_is_person"></span></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><b>Audit Done : </b><span id="audit_done"></span></td>
                                    <td><b>Audit Dropbox Found : </b><span id="audit_dropbox_found"></span></td>
                                    <td><b>Audit Dropbox Damage : </b><span id="audit_dropbox_damage"></span></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><b>Audit Dropbox Lock Working : </b><span id="audit_dropbox_lock_working"></span></td>
                                    <td colspan="2"><b>Audit Dropbox Key Available With FE : </b><span id="audit_dropbox_key_available_with_fe"></span></td>


                                </tr>
                                <tr style="display: none;"> 
                                    <td><b>Audit Dropbox Sticker : </b><span id="audit_dropbox_sticker"></span></td>
                                    <td colspan="2"><b>Audit Dropbox Painted: </b><span id="audit_dropbox_painted"></span></td>
                                </tr>
                                <tr style="display: none;"> 
                                    <td><b>Destination Location : </b><span id="destination_location"></span></td>
                                    <td colspan="2"><b>POD No : </b><span id="pod_no"></span></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><b>Contract Form Nos : </b><span id="contract_nos"></span></td>
                                    <td colspan="2"><b>Count of Contract Form : </b><span id="no_of_contract"></span></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><b>Nach Form Nos. : </b><span id="nach_form_nos"></span></td>
                                    <td colspan="2"><b>Count of Nach Form : </b><span id="no_of_nach_form"></span></td>
                                </tr>
                                <tr style="display: none;">
                                    <td colspan="3"><b>Count of Other Document : </b><span id="no_of_other_document"></span></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><b>Weight(Kg.) : </b><span id="weight_in_kg"></span></td>
                                    <td colspan="2"><b>No of Bags : </b><span id="no_of_bag"></span></td>
                                </tr>
                                <tr style="display: none;">
                                    <td><b>Latitude : </b><span id="latitude"></span></td>
                                    <td colspan="2"><b>Longitude : </b><span id="longitude"></span></td>
                                </tr>
                                <tr>
                                    <td colspan="3"><b>Google Address : </b><span id="google_address"></span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <span name="lat_lng" id="lat_lng"></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <label>Audit Status</label>
                                        <input type="hidden" name="a_activity_id" id="a_activity_id" value="0"/>
                                        <select class="form-control" name="audit_status" id="audit_status">
                                            <option value="0">Select</option>
                                            <option value="P">Pass</option>
                                            <option value="F">Fail</option>
                                        </select>
                                    </td>
                                    <td colspan="2">
                                        <label>Audit Remarks</label>
                                        <textarea class="form-control" name="audit_remarks" id="audit_remarks"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" style="text-align: center;">
                                        <button id="update_audit" class="btn btn-success">Audit</button>
                                    </td>
                                </tr>
                            </table>
                            <?php
                            $photoArray = [
                                "DbfcDropBox"   => "Dropbox Photo",
                                "DbfcDropBox2"  => "Dropbox Photo 2",
                                "DropBoxPhoto" => "Dropbox Photo",
                                "Map" => "Map Photo",
                                "DbfcLocationPhoto" => "Location Photo",
                                "DbfcMap" => "Map Photo"
                            ];
                            $i = 0;
                            foreach ($photoArray as $key => $value) {
                                ++$i;
                                ?>
                                <div class="col-md-6" id="<?php echo $key ?>">
                                    <center style="font-size: 15px; font-weight: bold; margin-top: 10px;">
                                        <?php echo $value ?>
                                    </center>
<!--                                    <div id="viewer<?php echo $i ?>" class="viewer"></div>-->
                                    <span id="viewer<?php echo $i?>">
                                        
                                    </span>
                                </div>
                                <?php
                            }
                            ?>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div id="imgVIewModal" class="modal" data-easein="bounceUpIn"  tabindex="-1" role="dialog" aria-labelledby="costumModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close exit" data-dismiss="modal" aria-hidden="true">×</button>
                    <h6 class="modal-title">IMAGE VIEW</h6>
                </div>
                <div class="modal-body">
                    <span id="imgs">
                        
                    </span>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
</div>

</div><!-- /.content-wrapper -->
<div id="loader" style="width:100%; height: 50%; width:50%; position: absolute; top:40%;left: 30%; z-index: 10000; display: none;" >
    <center>
        <img src="../images/giphy.gif"/>
    </center>
</div>
<script src="../js/jquery-1.9.1.js"></script>
<script>
    $(document).ready(function () {
        $("#auditModal").modal('hide');
        $("#imgVIewModal").modal('hide');
        var UTable = $("#userTable").DataTable({
            "ordering": false,
            "lengthChange": false,
            "processing": true,
            data: [],
            rowCallback: function (row, data) {
            },
            //"pageLength": 5,
            "dom": 'lBfrtip',
            "paging": false,
        });
        $(window).load(function () {
            $.ajax({
                method: "POST",
                url: "../functions/controller/auditDfpController.php",
                datatype: "json",
                data: {
                    type: "get_cases_for_audit",
                    date: $("#from_collection_date").val()
                },
                success: function (theResponse) {
                    var obj = JSON.parse(theResponse);
                    if (obj.success) {
                        UTable.clear().draw();
                        UTable.rows.add(obj.data).draw();
                    } else {
                        UTable.clear().draw();
                        alert(obj.errors.error);
                    }
                }
            });
        });
        $(document).on('click', '#get_grid_data', function () {
            var from_date = $("#from_collection_date").val();
            if (from_date == "" || from_date == " ") {
                alert("Please Select Collection Date!");
                $("#from_collection_date").focus();
                return false;
            }
            $.ajax({
                method: "POST",
                url: "../functions/controller/auditDfpController.php",
                datatype: "json",
                data: {
                    type: "get_cases_for_audit",
                    date: from_date
                },
                success: function (theResponse) {
                    var obj = JSON.parse(theResponse);
                    if (obj.success) {
                        UTable.clear().draw();
                        UTable.rows.add(obj.data).draw();
                    } else {
                        UTable.clear().draw();
                        alert(obj.errors.error);
                    }
                }
            });
        });
        $(document).on('click', '.audit', function () {
            var activity_id = $(this).data('acid');
            $.ajax({
                method: "POST",
                url: "../functions/controller/auditDfpController.php",
                data: {
                    type: "get_single",
                    activity_id: activity_id
                },
                success: function (theResponse) {
                    var obj = JSON.parse(theResponse);
                    if (obj.success) {
                        $("#auditModal").modal('show');
                        $("#a_activity_id").val(activity_id);
                        $.each(obj.data, function (key, value) {
                            if (key == "contract_nos" || key == "nach_form_nos") {
                                if (value === null) {
                                    $("#" + key).html(value);
                                } else {
                                    var res = value.replace(/,/gi, " ,");
                                    $("#" + key).html(res);
                                }    
                            } else {
                                $("#" + key).html(value);
                            }
                        });
                        var i = 0;
                        $.each(obj.images, function (key, value) {
                            ++i;
                            if (value) {
                                $("#" + key).show();
                                $("#viewer" + i).html(value);
                            } else {
                                $("#" + key).hide();
                            }
                        });
                    } else {
                        alert(obj.errors.error);
                    }
                }
            });
        });
        $(document).on('click', '#update_audit', function () {
            var activity_id = $("#a_activity_id").val();
            var audit_status = $("#audit_status").val();
            var audit_remarks = $("#audit_remarks").val();
            var card_no = $("#card_no").text();
            var update_lat_lng = false;
            var standard_latitude = null;
            var standard_longitude = null;
            if (audit_status == "0") {
                alert("Please Select Audit Status!");
                $("#audit_status").focus();
                return false;
            }
            if (audit_status == "F") {
                if (audit_remarks.length <= 0) {
                    alert("Audit Remarks Cannot Empty!");
                    $("#audit_remarks").focus();
                    return false;
                }
            }
            if($("#standard_lat_lng").prop('checked') == true) {
                update_lat_lng = true;
                standard_latitude = $("#latitude").text();
                standard_longitude = $("#longitude").text();
            }
            if (activity_id == 0 || activity_id.length <= 0) {
                alert("Activity Id Not Set. Please Contact To BSA");
                return false;
            }
            $.ajax({
                method: "POST",
                url: "../functions/controller/auditDfpController.php",
                datatype: "JSON",
                data: {
                    type: "update_audit_status",
                    activity_id: activity_id,
                    audit_status: audit_status,
                    audit_remarks: audit_remarks,
                    update_lat_lng:update_lat_lng,
                    card_no:card_no,
                    standard_latitude:standard_latitude,
                    standard_longitude:standard_longitude
                },
                success: function (theResponse) {
                    var obj = JSON.parse(theResponse);
                    if (obj.success) {
                        $("#auditModal").modal('hide');
                        $(window).trigger('load');
                    } else {
                        alert(obj.errors.error);
                    }
                }
            });
        });
        $(document).on('click','.imgView',function(){
           var imgsrc=$(this).data('imgsrc');
           if(imgsrc.length>0){
               $("#imgs").html("<img src='"+imgsrc+"' width='100%'/>");
               $("#imgVIewModal").modal('show');
           }else{
               $("#imgVIewModal").modal('hide');
           }
           
        });
    });
</script>
<script type="text/javascript" src="../js/jquery.js" ></script>
<script type="text/javascript" src="../js/jqueryui.js" ></script>
<?php
include "../footer/footer.php";
?>
<script type="text/javascript" src="../js/jquery.mousewheel.min.js" ></script>
<script type="text/javascript" src="../js/jquery.iviewer.js" ></script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css"/>
<!--<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css"/>
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>-->

<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script src="https://cdn.bootcss.com/prettify/r298/prettify.min.js"></script>
<script src="../js/jquery.magnify.js"></script>
<script>window.prettyPrint && prettyPrint();</script>
