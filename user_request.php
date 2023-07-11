<?php
include "header.php";
// echo "<pre>";
$type = $_SESSION['hryS_user_type'];
// print_r($_SESSION);
// exit;

?>
<!-- Select2 -->
<link rel="stylesheet" href="theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<?php
include "navbar.php";
include "sidebar.php";

?>

<div class="content-wrapper">
    <?php
    if ($type == "Entry Level") {
    ?>
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="m-0">User Request</h1>
                    </div><!-- /.col -->
                    <div class="alert alert-warning alert-dismissible fade hide d-none col-sm-12" role="alert" id="notice" style=" margin-top:5px;">
                        <p id="message"></p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="col-sm-10 d-flex justify-content-end">
                        <form id="user_form" name="user_form" method="post" style="background-color: #49bfa4; padding:10px; margin-top:10px; border-radius: 5px;">
                            <div class="modal-body">
                                <div class="row">
                                    <div id="msg"></div>
                                </div>
                                <div class="row">
                                    <input type="hidden" name="user_request_id" id="user_request_id" class="form-control" value="0">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name">Name :<span class="must">*</span></label>
                                            <input type="text" name="user_request_name" id="user_request_name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name">Father's Name :</label>
                                            <input type="text" name="father_name" id="father_name" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name">Address :</label>
                                            <textarea name="address" id="address" class="form-control"></textarea>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="company_name">Company Name :<span class="must">*</span></label>
                                            <textarea name="company_name" id="company_name" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name">Pincode :</label>
                                            <input type="number" name="pincode" id="pincode" min="1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="mobile_no">Mobile :<span class="must">*</span></label>
                                            <input type="number" name="mobile_no" id="mobile_no" min="1" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="email_id">Email :<span class="must">*</span></label>
                                            <input type="text" name="email_id" id="email_id" class="form-control">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="save" class="btn btn-lg btn-primary">Save</button>
                            </div>
                        </form>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->
    <?php
    }
    ?>

    <!-- Start content -->
    <section class="content">
        <!-- Start Client Table -->
        <div class="card">
            <!-- <div class="col-md-12" id="result"></div> -->
            <?php
            if ($type == "Manager") {
            ?>
                <!-- /.card-header   -->
                <div class="card-body">
                    <table id="user_table" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Father's Name</th>
                                <th>Company Name</th>
                                <th>Email Id</th>
                                <th>Address</th>
                                <th>Mobile No</th>
                                <th>Pincode</th>
                                <th>Request By</th>
                                <th>Del</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                        <tfoot></tfoot>
                    </table>
                </div>
                <!-- /.card-body -->
            <?php
            }
            ?>
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
<!-- Select2 -->
<script src="theme/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(function() {

        // Delete
        $(document).on('click', '.delete', function(e) {
            if (confirm("Are you sure delete this user Detail!")) {
                let user_request_id = $(this).data('id');
                let arr = {
                    action: 'delete',
                    user_request_id: user_request_id
                };
                var request = JSON.stringify(arr);
                $.ajax({
                    method: "POST",
                    url: "controller/user_request.php",
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
                    $('#user_table').DataTable().ajax.reload();
                    $("#message").html(Response.msg).show();
                    $("#notice").removeClass("d-none");
                    $("#notice").removeClass("hide");
                    $("#notice").addClass("d-block");
                    $("#notice").addClass("show");
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
            }
        });

        // Update
        $(document).on('click', '.edit', function(e) {
            $('#password').val('');
            let user_request_id = $(this).data('id');
            let arr = {
                action: 'get',
                user_request_id: user_request_id,
            };
            var request = JSON.stringify(arr);
            $.ajax({
                method: "POST",
                url: "controller/user_request.php",
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
                title = $("#add_user_modalLabel").html("Update user Detail");
                save.html("Update User");
                $.each(Response.data, function(index, value) {
                    console.log(value);
                    $("#user_request_id").val(value.user_request_id);
                    $("#user_request_name").val(value.user_request_name);
                    $("#email_id").val(value.email_id);
                    $("#company_name").val(value.company_name);
                    $("#mobile_no").val(value.mobile_no);
                    $("#user_type_id").val(value.user_type_id);
                    $("#father_name").val(value.father_name);
                    $("#address").val(value.address);
                    if (value.pincode != 0) {
                        $("#pincode").val(value.pincode);
                    } else {
                        $("#pincode").val('');
                    }
                });

                $("#add_user_modal").modal('show');

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
        var DataTable = $("#user_table").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "paging": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            processing: true,
            serverSide: true,
            ajax: {
                url: "controller/user_request.php",
                type: "POST",
                dataType: "json",
                async: false,
                headers: {
                    "Content-Type": "application/json"
                },
                data: function(d) {
                    d.action = 'get';
                    return JSON.stringify(d);
                }
            },
            "columns": [{
                    "data": "s_no",
                    "searchable": false,
                    "orderable": false
                },
                {
                    "data": "user_request_name"
                },
                {
                    "data": "father_name"
                },
                {
                    "data": "company_name"
                },
                {
                    "data": "email_id"
                },
                {
                    "data": "address"
                },
                {
                    "data": "mobile_no"
                },
                {
                    "data": "pincode"
                },
                {
                    "data": "rqst_by"
                },
                {
                    "data": "action",
                    "searchable": false,
                    "orderable": false
                },
            ]
        }).buttons().container().appendTo('#user_table_wrapper .col-md-6:eq(0)');
        // Add Button click
        let title = $("#add_user_modalLabel");
        let save = $("#save");
        $(document).on('click', '#add_user', function() {
            title.html("Add User Request");
            save.html("Add User");
            $("#user_request_id").val(0);
            let user_request_name = $("#user_request_name");
            let email_id = $("#email_id");
            let company_name = $("#company_name");
            let mobile_no = $("#mobile_no");
            let father_name = $("#father_name");
            let address = $("#address");
            let pincode = $("#pincode");
            alart(father_name); return false;
            let user_request_name_error = $("#user_request_name-error");
            let email_id_error = $("#email_id-error");
            let company_name_error = $("#company_name-error");
            let mobile_no_error = $("#mobile_no-error");
            let father_name_error = $("#father_name-error");
            let address_error = $("#address-error");
            let pincode_error = $("#pincode-error");
            user_request_name.removeClass('is-invalid');
            email_id.removeClass('is-invalid');
            company_name.removeClass('is-invalid');
            mobile_no.removeClass('is-invalid');
            father_name.removeClass('is-invalid');
            address.removeClass('is-invalid');
            pincode.removeClass('is-invalid');
            // user_request_name_error.hide();
            email_id_error.hide();
            company_name_error.hide();
            mobile_no_error.hide();
            pincode_error.hide();
            father_name_error.hide();
            address_error.hide();
            $("#msg").hide();
            $("#user_request_name").val("");
            $("#user_request_name").val("").prop('disabled', false);
            $("#email_id").val("");
            $("#email_id").val("").prop('disabled', false);
            $("#company_name").val("");
            $("#mobile_no").val("");
            $("#password").val("");
            $("#mobile_no").val("").prop('disabled', false);
            $("#father_name").val("");
            $("#email_personal").val("");
            $("#address").val("");
            $("#pincode").val("");
            $('#user_from').trigger("reset");
        });
        $.validator.setDefaults({
            submitHandler: function(e) {
                let user_request_id = $.trim($("#user_request_id").val());
                let user_request_name = $.trim($("#user_request_name").val());
                let email_id = $.trim($("#email_id").val());
                let company_name = $.trim($("#company_name").val());
                let mobile_no = $.trim($("#mobile_no").val());
                let action = 'add';
                let father_name = $.trim($("#father_name").val());
                let address = $.trim($("textarea#address").val());
                let pincode = $.trim($("#pincode").val());
                if (user_request_id > 0) {
                    action = 'update';
                }
                let arr = {
                    action: action,
                    user_request_id: user_request_id,
                    user_request_name: user_request_name,
                    email_id: email_id,
                    company_name: company_name,
                    mobile_no: mobile_no,
                    father_name: father_name,
                    address: address,
                    pincode: pincode
                };
                var request = JSON.stringify(arr);
                $.ajax({
                    method: "POST",
                    url: "controller/user_request.php",
                    data: request,
                    dataType: "JSON",
                    async: false,
                    headers: {
                        "Content-Type": "application/json",
                    },
                    beforeSend: function() {
                        console.log(request);
                    },
                }).done(function(Response) {
                    $("#add_user_modal").modal('hide');
                    // $("#user_table").DataTable().ajax.reload();
                    $("#message").html(Response.msg).show();
                    $("#user_request_id").val(0);
                    $("#user_request_name").val('');
                    $("#email_id").val('');
                    $("#company_name").val('');
                    $("#mobile_no").val('');
                    $("#father_name").val('');
                    $("#address").val('');
                    $("#pincode").val('');
                    $("#notice").removeClass("d-none");
                    $("#notice").removeClass("hide");
                    $("#notice").addClass("d-block");
                    $("#notice").addClass("show");
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
            }
        });
        // form validation
        $('#user_form').validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                email_id: {
                    required: true,
                    minlength: 8
                },
                company_name: {
                    required: true,
                    minlength: 5,
                },
                mobile_no: {
                    required: true,
                    minlength: 10,
                    maxlength: 10,
                    digits: true,
                },
                // user_type_id: {
                //     required: true,
                // },
                // // username: {
                //     required: {
                //         depends: function(element) {
                //             return $("#login_access").is(":checked");
                //         }
                //     }
                // },
                // password: {
                //     required: {
                //         depends: function(element) {
                //             if ($("#login_access").is(":checked")) {
                //                 let user_request_id = $.trim($("#user_request_id").val());
                //                 if (user_request_id == 0) {
                //                     return true;
                //                 } else {
                //                     return false;
                //                 }

                //             }
                //         }
                //     }
                // },
                pincode: {
                    minlength: 6,
                    maxlength: 6,
                    digits: true,
                }
            },
            messages: {
                name: {
                    required: "Please enter a User Name",
                    minlength: "User Name must be at least 3 characters long.",
                },
                email_id: {
                    required: "Please provide a User Email",
                    minlength: "User Email must be at least 8 characters long.",
                },
                company_name: {
                    required: "Please provide a Company Name",
                    minlength: "Company Name must be at least 10 digits long.",
                },
                mobile_no: {
                    required: "Please provide a User Mobile",
                    minlength: "User Mobile must be at least 10 digits long.",
                },
                pincode: {
                    minlength: "This field must be have 6 digits",
                    maxlength: "This field must be have 6 digits",
                    digits: true,
                }
            },
            errorElement: 'span',
            errorPlacement: function(error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function(element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function(element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });
</script>
<?php include "footer.php"; ?>