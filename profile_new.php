<?php
include "header.php";
?>

<?php
include "navbar.php";
include "sidebar.php";
require 'config/DBConnection.php';

$conn = new DBConnection();
$connection = $conn->connect();
$userId = $_SESSION['hryS_user_id'];
$statement = $connection->prepare("select u.name,u.company_name,u.email_id,ut.user_type,
u.user_type_id,u.mobile_no,u2.name as created_by,u.created_on from user u
LEFT OUTER JOIN user u2 ON u.created_by=u2.user_id
LEFT JOIN user_type ut ON u.user_type_id = ut.user_type_id
where u.user_id=$userId");
$statement->execute();
$result = $statement->fetch();
// print_r($result);exit;
$statement->closeCursor();

// $conn = new DBConnection();
// $connection = $conn->connect();
// $userId = $_SESSION['voucher_user_id'];
// $getUser = $conn->getOne("select u.emp_code,u.user_name,u.department,u.company_name,u.email,
// u.user_type,u.phone_no,u1.user_name as reportingTo,u2.user_name as created_by,u.created_on from users u
// LEFT OUTER JOIN users u1 ON u.reporting_to=u1.user_id
// LEFT OUTER JOIN users u2 ON u.created_by=u2.user_id
// where u.user_id=$userId");
?>
<div class="content-wrapper">
    <!-- Start content -->
    <section class="content">
        <!-- Add Client Modal -->
        <div style="margin-top:50px;" class="container-fluid">
            <div style="margin-top:30px;" class="container-fluid">
                <div class="col-md-11 col-md-offset-3">
                    <div class="row" style="border:2px solid #333546; padding-bottom: 10px; padding-top: 10px; background-color: #cdd7b4; border-radius: 15px 50px 30px 5px;">
                        <div style="margin: 10px;" class="col-md-3 col-xs-3">
                            <img src="images/user/<?php echo (file_exists("images/user/" . strtolower(substr($_SESSION['hryS_name'], 0, 1)) . ".png")) ? strtolower(substr($_SESSION['hryS_name'], 0, 1)) : 'def' ?>.png" style="width:90%; box-shadow: 0px 0px 14px 0px rgba(0,0,0,0.75); margin-top: 30px;" class="img-circle" />
                        </div>
                        <div style="background-color: #a1c5a9;" class="col-md-8 col-xs-8">
                            <table class="table table-condensed">
                                <tr>
                                    <!-- <td><b>Emp Code:</b> </td> -->
                                    <td><b>Name:</b> &nbsp;<?= ucfirst($result['name']) ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><b>Company Name:</b> &nbsp;<?= $result['company_name'] ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><b>Email:</b> &nbsp;<?= ucfirst($result['email_id']) ?></td>
                                </tr>
                                <tr>
                                    <td><b>Account Type:</b> <?= $result['user_type'] ?></td>
                                    <td><b>Phone No:</b> &nbsp;<?= $result['mobile_no'] ?></td>
                                </tr>
                                <tr>
                                    <td><b>Created By:</b> <?= ucfirst($result['created_by']) ?></td>
                                    <td><b>Created On:</b> &nbsp;<?= $result['created_on'] ?></td>
                                </tr>
                                <tr>
                                    <td colspan="2"><a href="http:#"><b>Update Profile:</b></a> <?php  ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="row">
                        <img src="images/shadow1.png" class="img-responsive" style="margin-top: 0px; width:100%;" />
                    </div>
                </div>
            </div>
        </div>
        <!-- End Client Modal -->
        <!-- Start Client Table -->
    </section>
    <!-- End content -->
</div>

<style>
    .addReadMore.showlesscontent .SecSec,
    .addReadMore.showlesscontent .readLess {
        display: none;
    }

    .addReadMore.showmorecontent .readMore {
        display: none;
    }

    .addReadMore .readMore,
    .addReadMore .readLess {
        font-weight: bold;
        margin-left: 2px;
        color: blue;
        cursor: pointer;
    }

    .addReadMoreWrapTxt.showmorecontent .SecSec,
    .addReadMoreWrapTxt.showmorecontent .readLess {
        display: block;
    }
</style>
<script src="js/jquery-1.9.0.min.js"></script>
<?php include "footer_js.php"; ?>
<script>
    $(document).ready(function() {

    });
</script>
<script>
    function AddReadMore() {
        //This limit you can set after how much characters you want to show Read More.
        var carLmt = 10;
        // Text to show when text is collapsed
        var readMoreTxt = " ... Read More";
        // Text to show when text is expanded
        var readLessTxt = " Read Less";


        //Traverse all selectors with this class and manupulate HTML part to show Read More
        $(".addReadMore").each(function() {
            if ($(this).find(".firstSec").length)
                return;

            var allstr = $(this).text();
            if (allstr.length > carLmt) {
                var firstSet = allstr.substring(0, carLmt);
                var secdHalf = allstr.substring(carLmt, allstr.length);
                var strtoadd = firstSet + "<span class='SecSec'>" + secdHalf + "</span><span class='readMore'  title='Click to Show More'>" + readMoreTxt + "</span><span class='readLess' title='Click to Show Less'>" + readLessTxt + "</span>";
                $(this).html(strtoadd);
            }

        });
        //Read More and Read Less Click Event binding
        $(document).on("click", ".readMore,.readLess", function() {
            $(this).closest(".addReadMore").toggleClass("showlesscontent showmorecontent");
        });
    }
    $(function() {
        //Calling function after Page Load
        AddReadMore();
    });
</script>
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap.min.css" />
<script src="js/model-js.js"></script>
<script src="js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/bootstrap-datetimepicker.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />
<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#datatable').DataTable({
            "ordering": false,
            "lengthChange": false
        });
    });
</script>
<!-- <script>
    $(document).ready(function() {
        $('#from_date').datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            onSelect: function(date) {
                var date2 = $('#from_date').datepicker('getDate');
                date2.setDate(date2.getDate() + 1);
                $('#to_date').datepicker('setDate', date2);
                //sets minDate to dt1 date + 1
                $('#to_date').datepicker('option', 'minDate', date2);
            }

        });
        $("#to_date").datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            onClose: function() {
                var dt1 = $('#from_date').datepicker('getDate');
                console.log(dt1);
                var dt2 = $('#to_date').datepicker('getDate');
                if (dt2 < dt1) {
                    var minDate = $('#to_date').datepicker('option', 'minDate');
                    $('#to_date').datepicker('setDate', minDate);
                }
            }

        });
        $('#voucher_date_from').datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            onSelect: function(date) {
                var date2 = $('#voucher_date_from').datepicker('getDate');
                //date2.setDate(date2.getDate() + 1);
                date2.setDate(date2.getDate());
                $('#voucher_date_to').datepicker('setDate', date2);
                //sets minDate to dt1 date + 1
                $('#voucher_date_to').datepicker('option', 'minDate', date2);
            }

        });
        $("#voucher_date_to").datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            onClose: function() {
                var dt1 = $('#voucher_date_from').datepicker('getDate');
                console.log(dt1);
                var dt2 = $('#voucher_date_to').datepicker('getDate');
                if (dt2 < dt1) {
                    var minDate = $('#voucher_date_to').datepicker('option', 'minDate');
                    $('#voucher_date_to').datepicker('setDate', minDate);
                }
            }

        });
        $('#e_voucher_date_from').datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            onSelect: function(date) {
                var date2 = $('#from_date').datepicker('getDate');
                date2.setDate(date2.getDate() + 1);
                $('#to_date').datepicker('setDate', date2);
                //sets minDate to dt1 date + 1
                $('#to_date').datepicker('option', 'minDate', date2);
            }

        });
        $("#e_voucher_date_to").datepicker({
            dateFormat: 'yy-mm-dd',
            todayHighlight: true,
            autoclose: true,
            onClose: function() {
                var dt1 = $('#from_date').datepicker('getDate');
                console.log(dt1);
                var dt2 = $('#to_date').datepicker('getDate');
                if (dt2 < dt1) {
                    var minDate = $('#to_date').datepicker('option', 'minDate');
                    $('#to_date').datepicker('setDate', minDate);
                }
            }

        });
    });
</script> -->
<?php include "footer.php"; ?>