<?php
include "header.php";
?>
<!-- Select2 -->
<link rel="stylesheet" href="theme/plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="theme/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
<?php
include "navbar.php";
include "sidebar.php";

// print_r($_SESSION);exit;
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">User Master</h1>
                </div><!-- /.col -->
                <div class="col-sm-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="add_user" data-toggle="modal" data-target="#add_user_modal">
                        Add New User Detail
                    </button>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Start content -->
    <section class="content">
        <!-- Add Client Modal -->
        <div class="modal fade" id="add_user_modal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="add_user_modalLabel">Add User Detail</h5>
                        <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="user_form" name="user_form" method="post">
                        <div class="modal-body">
                            <div class="row">
                                <div id="msg"></div>
                            </div>
                            <div class="row">
                                <input type="hidden" name="user_id" id="user_id" class="form-control" value="0">
                                <div class="col">
                                    <div class="form-group">
                                        <label for="name">Name :<span class="must">*</span></label>
                                        <input type="text" name="name" id="name" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="name">Father's Name :</label>
                                        <input type="text" name="father_name" id="father_name" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="email_id">Email :<span class="must">*</span></label>
                                        <input type="text" name="email_id" id="email_id" class="form-control">
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
                                        <input type="text" name="pincode" id="pincode" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="mobile_no">Mobile :<span class="must">*</span></label>
                                        <input type="number" name="mobile_no" id="mobile_no" class="form-control">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group">
                                        <label for="user_type_id">User Type<span class="must">*</span></label>
                                        <select class="form-control select2bs4" id="user_type_id" name="user_type_id" style="width: 100%;">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col">
                                    <div class="form-check">
                                        <input type="checkbox" style="margin-top:45px;" name="login_access" class="form-check-input" value="1" id="login_access">
                                        <label class="form-check-label" for="login_access" style="margin-top:38px;">Login access:</label>
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group check_form ">
                                        <label class="col-form-label" for="username">Username :<span class="must">*</span></label>
                                        <input type="text" class="form-control" name="username" id="username" placeholder="Username">
                                    </div>
                                </div>
                                <div class="col">
                                    <div class="form-group check_form">
                                        <label class="col-form-label" for="password">Password :<span class="must">*</span></label>
                                        <input type="password" value="" class="form-control" name="password" id="password" placeholder="Password" autocomplete="off">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" id="save" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Client Modal -->


        <!-- Start Client Table -->
        <div class="card">
            <div class="alert alert-warning alert-dismissible fade hide d-none" role="alert" id="notice">
                <p id="message"></p>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- <div class="col-md-12" id="result"></div> -->
            <!-- /.card-header -->
            <div class="card-body">
                <table id="user_table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Name</th>
                            <th>User Type</th>
                            <th>Email Id</th>
                            <th>Mobile No</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                    <tfoot></tfoot>
                </table>
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
<!-- Select2 -->
<script src="theme/plugins/select2/js/select2.full.min.js"></script>
<script>
    $(function() {
        var $select = $('#user_type_id').select2({
            theme: 'bootstrap4'
        });
        let arr = {
            action: 'get'
        };
        var request = JSON.stringify(arr);
        $.ajax({
            method: "POST",
            url: "controller/user_type.php",
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
            $select.empty();
            $select.append('<option value="">Select User Type</option>');
            $.each(Response.data, function(index, value) {
                $select.append('<option value="' + value.user_type_id + '">' + value.user_type + '</option>');
            });
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

        $(".check_form").hide();
        $(".form-check-input").click(function() {
            if ($(this).is(":checked")) {
                $(".check_form").show();
            } else {
                $(".check_form").hide();
            }
        });
    });
    $(function() {

        // Delete
        $(document).on('click', '.delete', function(e) {
            if (confirm("Are you sure delete this user Detail!")) {
                let user_id = $(this).data('id');
                let arr = {
                    action: 'delete',
                    user_id: user_id
                };
                var request = JSON.stringify(arr);
                $.ajax({
                    method: "POST",
                    url: "controller/user.php",
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
            let user_id = $(this).data('id');
            let arr = {
                action: 'get',
                user_id: user_id,
            };
            var request = JSON.stringify(arr);
            $.ajax({
                method: "POST",
                url: "controller/user.php",
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
                    $("#user_id").val(value.user_id);
                    $("#name").val(value.name);
                    $("#email_id").val(value.email_id);
                    $("#company_name").val(value.company_name);
                    $("#mobile_no").val(value.mobile_no);
                    $("#user_type_id").val(value.user_type_id);
                    $("#username").val(value.username);
                    $("#user_type_id").trigger('change');
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
                url: "controller/user.php",
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
                    "data": "name"
                },
                {
                    "data": "user_type"
                },
                {
                    "data": "email_id"
                },
                {
                    "data": "mobile_no"
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
            title.html("Add User");
            save.html("Add User");
            $("#user_id").val(0);
            let name = $("#name");
            let email_id = $("#email_id");
            let company_name = $("#company_name");
            let mobile_no = $("#mobile_no");
            let user_type_id = $("#user_type_id");
            let father_name = $("#father_name");
            let address = $("#address");
            let pincode = $("#pincode");

            let name_error = $("#name-error");
            let email_id_error = $("#email_id-error");
            let company_name_error = $("#company_name-error");
            let mobile_no_error = $("#mobile_no-error");
            let user_type_id_error = $("#user_type_id_error");
            let pincode_error = $("#pincode-error");
            name.removeClass('is-invalid');
            email_id.removeClass('is-invalid');
            company_name.removeClass('is-invalid');
            mobile_no.removeClass('is-invalid');
            user_type_id.removeClass('is-invalid');
            name_error.hide();
            email_id_error.hide();
            company_name_error.hide();
            mobile_no_error.hide();
            user_type_id_error.hide();
            pincode_error.hide();
            $("#msg").hide();
            $("#username").val('');
            $("#user_type_id").val('');
            $("#user_type_id").trigger('change');
            $("#name").val("");
            $("#name").val("").prop('disabled', false);
            $("#email_id").val("");
            $("#email_id").val("").prop('disabled', false);
            $("#company_name").val("");
            $("#mobile_no").val("");
            $("#password").val("");
            $("#mobile_no").val("").prop('disabled', false);
            $("#user_type_id").val("");
            $("#father_name").val("");
            $("#email_personal").val("");
            $("#address").val("");
            $("#pincode").val("");
            $("#user_type_id").val("").prop('disabled', false);
            $('#user_from').trigger("reset");
        });
        $.validator.setDefaults({
            submitHandler: function(e) {
                let user_id = $.trim($("#user_id").val());
                let name = $.trim($("#name").val());
                let email_id = $.trim($("#email_id").val());
                let company_name = $.trim($("#company_name").val());
                let mobile_no = $.trim($("#mobile_no").val());
                let user_type_id = $.trim($("#user_type_id").val());
                let username = '';
                let password = '';
                let action = 'add';
                let login_access = 0;
                if ($("#login_access").is(":checked")) {
                    login_access = 1;
                    username = $.trim($("#username").val());
                    password = $.trim($("#password").val());
                }
                let father_name = $.trim($("#father_name").val());
                let address = $.trim($("textarea#address").val());
                let pincode = $.trim($("#pincode").val());
                if (user_id > 0) {
                    action = 'update';
                }
                let arr = {
                    action: action,
                    user_id: user_id,
                    name: name,
                    email_id: email_id,
                    company_name: company_name,
                    mobile_no: mobile_no,
                    user_type_id: user_type_id,
                    login_access: login_access,
                    username: username,
                    password: password,
                    father_name: father_name,
                    address: address,
                    pincode: pincode
                };
                var request = JSON.stringify(arr);
                $.ajax({
                    method: "POST",
                    url: "controller/user.php",
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
                    $("#user_table").DataTable().ajax.reload();
                    $("#message").html(Response.msg).show();
                    $("#user_id").val(0);
                    $("#name").val('');
                    $("#email_id").val('');
                    $("#company_name").val('');
                    $("#mobile_no").val('');
                    $("#father_name").val('');
                    $("#address").val('');
                    $("#pincode").val('');
                    $("#user_type_id").val('');
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
                user_type_id: {
                    required: true,
                },
                username: {
                    required: {
                        depends: function(element) {
                            return $("#login_access").is(":checked");
                        }
                    }
                },
                password: {
                    required: {
                        depends: function(element) {
                            if ($("#login_access").is(":checked")) {
                                let user_id = $.trim($("#user_id").val());
                                if (user_id == 0) {
                                    return true;
                                } else {
                                    return false;
                                }

                            }
                        }
                    }
                },
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
                user_type_id: {
                    required: "Please provide a User Type.",
                },
                username: {
                    required: "Please provide a Username.",
                    minlength: "User Name must be at least 8 Character long.",
                },
                password: {
                    required: "Please provide a Password.",
                    minlength: "Password must be at least 6 Character long.",
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