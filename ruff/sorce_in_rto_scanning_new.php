<?php
session_start();
if (!isset($_SESSION['bsa_user_id'])) {
    header("location: index.php");
    die;
}
require_once 'header.php';
require_once 'db.php';
?>
<!doctype html>
<html>

<head>
    <title>POD RTO Scanning</title>
    <style>
        .loader {
            border: 16px solid #f3f3f3;
            border-radius: 50%;
            border-top: 16px solid blue;
            border-right: 16px solid green;
            border-bottom: 16px solid red;
            border-left: 16px solid pink;
            width: 120px;
            height: 120px;
            -webkit-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-webkit-keyframes spin {
            0% {
                -webkit-transform: rotate(0deg);
            }

            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body background="images/background-bsa.jpg">
    <br />
    <br />
    <div id="loader" style="background-color: white; color: rgba(215, 127, 44, 0.4); position: fixed; width:100%; height: 100%; z-index: 2000; margin-top: -55px; display: none;">
        <div style="border:0px solid white; color: black; margin-top: 200px; text-align: center; padding-top: 0px; padding-bottom: 20px;" class="col-md-4 col-md-offset-4">
            <img src="images/Loading_icon.gif" width="50%" />
            <span style="color:white;" class="sr-only">Loading... </span>
            <p>Loading... Please Wait</p>
        </div>
    </div>
    <br />
    <div class="container" id="pod_div">
        <div style="border:0px solid;" class="col-md-12 col-md-offset-0">
            <div class="row">
                <div class="col-md-12 col-xs-12">
                    <div class="panel panel-primary">
                        <div class="panel-heading" align="center">
                            <h4>RTO Scanning</h4>
                        </div>
                        <div class="panel-body">
                            <form action="" method="post">
                                <div class="form-group col-md-12">
                                    <div class="form-group col-md-12">
                                        <div class="form-group col-md-3">
                                            <label>Scanning By</label>
                                            <select name="scanning_by" id="scanning_by" class="form-control">
                                                <option value="C">Client Code</option>
                                                <option value="B">Billcode</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3" id="ClientDis">
                                            <label>Select Client Code</label>
                                            <select name="cbo_client" id="cbo_client" class="form-control">
                                                <option value="0">Select Client</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3" id="BillDis">
                                            <label>Select Billcode</label>
                                            <select name="cbo_billcode" id="cbo_billcode" class="form-control">
                                                <option value="0">Select Billcode</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-3">
                                            <label>Current Lot No</label>
                                            <input type="text" class="form-control" readonly="" name="lot" id="lot" placeholder="Current Lot No">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="form-group col-md-3" id="">
                                            <label>Select Pickup Seller Address</label>
                                            <select class="form-control" name="pickSellrAdds" id="pickSellrAdds">
                                                <option value="0">Select Address</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="form-group col-md-3" align='left'>
                                            <label>Scan/Enter POD No.</label>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="text" name="pod_no_for_rebook" id="pod_no_for_rebook" maxlength="9" class="form-control" style="background-color:red;color:white;font-size:25px" />
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="form-group col-md-3" align='left'>
                                            <label>Last POD No Scanned</label>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <input type="text" name="previous_pod_no" id="previous_pod_no" class="form-control" readonly />
                                        </div>
                                    </div>
                                    <div style="text-align: right" class="col-md-12">
                                        <label>Total Scanned Packets: <font id="scanned_packets">0</font></label>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="loader col-md-3 col-md-offset-5 " id="loader" hidden></div>
                <div class="col-md-12">
                    <div class="panel panel-danger col-md-12" id="error_div">
                        <div class="panel-heading">Warning</div>
                        <div class="panel-body">
                            <div class="form-group col-md-12 col-sd-12">
                                <label>Error Message</label>
                                <input type="text" name="error_msg" id="error_msg" class="form-control" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" language="javascript">
        function pickSellrAdds($clientId=0) {
            // alert($clientId); return false;
            var client = $clientId;
            // var request = JSON.stringify(arr);
            $.ajax({
                method: "POST",
                url: "client_address.php",
                data: {
                    client: client
                },
                dataType: "JSON",
                beforeSend: function() {
                    $("#loader").show();
                },
            }).done(function(Response) {
                $("#loader").hide();
                console.log(Response.data);
                $('#pickSellrAdds').empty();
                $("#pickSellrAdds").append("<option value='0'>Select Address</option>");
                // $("#pickSellrAdds").append("<option value=" + 'All' + ">" + 'ALL' + "</option>");
                $.each(Response.data, function(key, value) {
                    $("#pickSellrAdds").append(
                        "<option value=" + value.pickup_add + ">" + value.pickup_add + "</option>"
                    );
                });
            }).fail(function() {
                $("#loader").hide();
                alert('fail');
            }).always(function() {

            });
        }
        $('#BillDis').hide();
        $("#loader").hide();
        $(document).ready(function() {

            $.getJSON("client_list.php", function(return_data) {
                $('#cbo_client').empty();
                $("#cbo_client").append("<option value='0'>Select Client</option>");
                $.each(return_data.data, function(key, value) {
                    $("#cbo_client").append(
                        "<option value=" + value.client_id + ">" + value.client_name + "</option>"
                    );
                });
            });

            $.getJSON("billcode_list.php", function(return_data) {
                $('#cbo_billcode').empty();
                $("#cbo_billcode").append("<option value='0'>Select Billcode</option>");
                $.each(return_data.data, function(key, value) {
                    $("#cbo_billcode").append(
                        "<option value=" + value.billcode_id + ">" + value.billcode + "</option>"
                    );
                });
            });
            $('#scanning_by').change(function() {
                var val = $(this).val();
                if (val == 'C') {
                    $('#ClientDis').show();
                    $('#BillDis').hide();
                    $('#cbo_billcode').val('0');
                    return false;
                } else if (val == 'B') {
                    $('#ClientDis').hide();
                    $('#BillDis').show();
                    $('#cbo_client').val('0');
                    return false;
                }
            });
            $('#cbo_client').change(function() {
                var client = $('#cbo_client').val();
                var billcode = $('#cbo_billcode').val();
                var scanning_by = $('#scanning_by').val();
                $.ajax({
                    method: "POST",
                    url: "rto_scaning_del.php",
                    dataType: "json",
                    data: {
                        client: client,
                        billcode: billcode,
                        scanning_by: scanning_by,
                        type: 'get_lot_no'
                    },
                    beforeSend: function() {
                        $("#loader").show();
                    },
                }).done(function(data) {
                    $("#loader").hide();
                    $('#lot').val(data.lot);
                    pickSellrAdds(client);
                }).fail(function() {
                    $("#loader").hide();
                    alert('fail');
                }).always(function() {

                });
            });
            $('#cbo_billcode').change(function() {
                var client = $('#cbo_client').val();
                var billcode = $('#cbo_billcode').val();
                var scanning_by = $('#scanning_by').val();
                $.ajax({
                    method: "POST",
                    url: "rto_scaning_del.php",
                    dataType: "json",
                    data: {
                        client: client,
                        billcode: billcode,
                        scanning_by: scanning_by,
                        type: 'get_lot_no'
                    }
                }).done(function(data) {
                    $('#lot').val(data.lot);

                }).fail(function() {
                    alert('fail');
                }).always(function() {

                });
            });
            $('#pod_no_for_rebook').keydown(function(e) {
                $('#error_msg').val('');
                if (e.keyCode == 27) {
                    $('#pod_no_for_rebook').val('');
                    $('#error_msg').val('');
                    $('#error_msg').css('background-color', 'white');
                    $('#error_msg').css({
                        'color': 'black',
                        'font-size': '150%'
                    });
                }
                if (e.keyCode == 13) {
                    var pickSellrAdds = $('#pickSellrAdds option:selected').text();
                    // alert(pickSellrAdds); return false;
                    var value = $('#pod_no_for_rebook').val();
                    var cbo_client = $('#cbo_client').val();
                    var cbo_billcode = $('#cbo_billcode').val();
                    var scanning_by = $('#scanning_by').val();
                    var lot = $('#lot').val();
                    // console.log(pickSellrAdds); return false;
                    if (value.length < 1) {
                        $("#pod_div").shake(10, 10, 300);
                        $("#error_div").shake(10, 10, 300);
                        $('#error_msg').val('Enter Pod No');
                        $('#error_msg').css('background-color', 'red');
                        $('#error_msg').css({
                            'color': 'white',
                            'font-size': '150%'
                        });
                        return false;
                    }
                    if (scanning_by == 'C' && cbo_client == '0') {
                        alert('Please Select Client');
                        $('#cbo_client').focus();
                        return false;
                    }
                    if (scanning_by == 'B' && cbo_billcode == '0') {
                        alert('Please Select Billcode');
                        $('#cbo_billcode').focus();
                        return false;
                    }

                    $.ajax({
                        method: "POST",
                        url: "rto_scaning_del.php",
                        datatype: "json",
                        data: {
                            type: "rto_scaning",
                            pod_number: value,
                            lot: lot,
                            client: cbo_client,
                            scanning_by: scanning_by,
                            billcode: cbo_billcode,
                            pickSellrAdds: pickSellrAdds
                        },
                        success: function(theResponse) {
                            // alert(theResponse);
                            var data = JSON.parse(theResponse);
                            if (data.success) {
                                var last_scanned = $("#scanned_packets").html();
                                var new_number = parseInt(last_scanned) + 1;
                                $("#scanned_packets").html(new_number);
                                $("#previous_pod_no").val(value);
                                $('#pod_no_for_rebook').val("");
                                $('#pickSellrAdds').val(value);

                                $('#error_msg').val("");
                                $('#error_msg').css('background-color', 'white');
                            } else {
                                $("#pod_div").shake(10, 10, 300);
                                $("#error_div").shake(10, 10, 300);
                                $('#error_msg').val(data.errors.error);
                                $('#error_msg').css('background-color', 'red');
                                $('#error_msg').css({
                                    'color': 'white',
                                    'font-size': '150%'
                                });
                            }
                        }
                    });
                }

            });
            jQuery.fn.shake = function(intShakes, intDistance, intDuration) {
                this.each(function() {
                    $(this).css("position", "relative");
                    for (var x = 1; x <= intShakes; x++) {
                        $(this).animate({
                                left: (intDistance * -1)
                            }, (((intDuration / intShakes) / 4)))
                            .animate({
                                left: intDistance
                            }, ((intDuration / intShakes) / 2)).animate({
                                left: 0
                            }, (((intDuration / intShakes) / 4)));
                    }
                });
                return this;
            }; 
        });
    </script>
</body>

</html>