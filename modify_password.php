<?php
include "header.php";
include "navbar.php";
include "sidebar.php";
?>

<div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
                <div class="container-fluid">
                        <div class="row mb-2">
                                <div class="col-sm-6">
                                        <h1 class="m-0">Modify Password</h1>
                                </div><!-- /.col -->

                        </div><!-- /.row -->
                </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->


        <!-- Start content -->
        <section class="content">



                <!-- Start Client Table -->
                <div class="card">
                        <div class="card-body">
                                <form id="modify_password_form" style="margin:0px 15% 0px 15%;">
                                        <div class="col">
                                                <div class="form-group">
                                                        <label for="task_type">Current Password<span class="must">*</span></label>
                                                        <input type="password" name="current_password" id="current_password" class="form-control">
                                                </div>
                                        </div>
                                        <div class="col">
                                                <div class="form-group">
                                                        <label for="task_type">New Password<span class="must">*</span></label>
                                                        <input type="password" name="new_password" id="new_password" class="form-control">
                                                </div>
                                        </div>
                                        <div class="col">
                                                <div class="form-group">
                                                        <label for="task_type">Confirm New Password<span class="must">*</span></label>
                                                        <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control">
                                                </div>
                                        </div>
                                        <div class="error text-danger text-center" style="font-size:14px;"></div>
                                        <div class="success text-success text-center" style="font-size:14px;"></div>
                                        <div class="col">
                                                <div class="form-group">
                                                        <button class="btn btn-primary" type="submit" id="modify_password">Done</button>
                                                </div>
                                        </div>

                                </form>
                        </div>
                        <!-- /.card-body -->
                </div>
                <!-- /.card -->
                <!-- Start Client Table -->
        </section>
        <!-- End content -->
</div>




<!-- Start Footer -->
<?php include "footer_js.php"; ?>
<!-- End Footer -->
<script src="theme/plugins/jquery-validation/jquery.validate.min.js"></script>
<script src="theme/plugins/jquery-validation/additional-methods.min.js"></script>
<script>
        $(function() {
                $(document).on('click', '#modify_password', function(e) {
                        e.preventDefault();
                        var current_password = $('#current_password').val();
                        var new_password = $('#new_password').val();
                        var confirm_new_password = $('#confirm_new_password').val();
                        $('.error').text('').hide();

                        if (current_password == '' || current_password == "") {
                                $('.error').text('Current Password is required').show();
                                $('#current_password').focus();
                                return false;
                        }

                        if (new_password == '' || new_password == "") {
                                $('.error').text('New Password Password is required').show();
                                $('#new_password').focus();
                                return false;
                        }

                        if (confirm_new_password == '' || confirm_new_password == "") {
                                $('.error').text('Confirm New Password Password is required').show();
                                $('#confirm_new_password').focus();
                                return false;
                        }

                        if (new_password.length < 7) {
                                $('.error').text('New Password is must have 8 length').show();
                                $('#new_password').focus();
                                return false;
                        }

                        if (confirm_new_password.length < 7) {
                                $('.error').text('Confirm New Password Password is must have 8 length').show();
                                $('#confirm_new_password').focus();
                                return false;
                        }

                        if (!password_fromat(new_password)) {
                                $('.error').text('New Password must be contain a upercase,number and spaciel character').show();
                                $('#new_password').focus();
                                return false;
                        }

                        if (new_password != confirm_new_password) {
                                $('.error').text('New Password must be same as Confirm Password').show();
                                $('#new_password').focus();
                                return false;
                        }

                        if (new_password == current_password) {
                                $('.error').text('New Password must be diffrent from current password').show();
                                $('#new_password').focus();
                                return false;
                        }

                        let arr = {
                                activity: 'modify_password',
                                current_password: current_password,
                                new_password: new_password,
                                confirm_new_password: confirm_new_password
                        };
                        var request = JSON.stringify(arr);

                        $.ajax({
                                method: "POST",
                                url: "controller/login.php",
                                data: request,
                                dataType: "JSON",
                                async: false,
                                headers: {
                                        "Content-Type": "application/json"
                                },
                                beforeSend: function() {
                                        console.log(request);
                                },
                        }).done(function(Response) {
                                $('.error').text('').hide();
                                $('.success').text('Password Modified Successfully').show();
                                $('#current_password').val('');
                                $('#new_password').val('');
                                $('#confirm_new_password').val('');
                        }).fail(function(jqXHR, exception) {
                                var msg = '';
                                if (jqXHR.status === 0) {
                                        msg = 'Not connect.\n Verify Network.';
                                } else if (jqXHR.status == 404) {
                                        msg = 'Requested page not found. [404]';
                                } else if (jqXHR.status == 500) {
                                        msg = 'Internal Server Error [500].';
                                } else if (exception === 'parsererror') {
                                        msg = 'Requested JSON parse failed.';
                                } else if (exception === 'timeout') {
                                        msg = 'Time out error.';
                                } else if (exception === 'abort') {
                                        msg = 'Ajax request aborted.';
                                } else {
                                        msg = 'Uncaught Error.\n' + jqXHR.responseJSON.msg;
                                }
                                $("#message").html(msg).show();
                        }).always(function(xhr) {
                                console.log(xhr);
                        });
                });
        });

        function password_fromat(new_password) {
                var format = /[ `!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?~]/;
                if (!format.test(new_password)) {
                        return false;
                }
                if (!/\d/.test(new_password)) {
                        return false;
                }
                if (!/^[A-Z]/.test(new_password)) {
                        return false;
                }
                return true;
        }
</script>
<?php include "footer.php"; ?>