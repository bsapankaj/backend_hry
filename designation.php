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
                    <h1 class="m-0">User Designation</h1>
                </div><!-- /.col -->
                <div class="col-sm-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="add_user_designation" data-toggle="modal" data-target="#user_designation_modal">
                        Click to Add User Designation
                    </button>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Start content -->
    <section class="content">
        <!-- Add Client Modal -->
        <div class="modal fade" id="user_designation_modal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="user_designation_modalLabel">Add User Designation</h5>
                        <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="user_designation_form" name="user_designation_form" method="post">
                        <div class="modal-body">
                            <!-- client code  -->
                            <div class="row">
                                <div id="msg"></div>
                            </div>
                            <!-- Update Hidden id  -->
                            <input type="hidden" name="user_designation_id" id="user_designation_id" class="form-control" value="0">
                            <div class="col">
                                <div class="form-group">
                                    <label for="user_designation">User Designation<span class="must">*</span></label>
                                    <input type="text" name="user_designation" id="user_designation" class="form-control">
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="user_type">User Type<span class="must">*</span></label>
                                    <select name="user_type_id" id="user_type_id" class="form-control">
                                        <option value="">Select User Type</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col">
                                <div class="form-group">
                                    <label for="sort_code">Short Code<span class="must"></span></label>
                                    <input type="text" name="sort_code" id="sort_code" class="form-control">
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
                <table id="user_designation_table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>User Designation</th>
                            <th>User Type</th>
                            <th>Short code</th>
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
<script>
    $(function() {
        $('#datetimepicker').datetimepicker({
            format: 'DD-MM-YYYY'
        });
        $(document).on('click', '.delete', function(e) {
            if (confirm("Are you sure delete this Designation!")) {
                let user_designation_id = $(this).data('id');
                let arr = {
                    action: 'delete',
                    user_designation_id: user_designation_id
                };
                var request = JSON.stringify(arr);
                $.ajax({
                    method: "POST",
                    url: "controller/designation.php",
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
                    $('#user_designation_table').DataTable().ajax.reload();
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

        $(document).on('click', '.edit', function(e) {
            let user_designation_id = $(this).data('id');
            let arr = {
                action: 'get',
                user_designation_id: user_designation_id
            };
            var request = JSON.stringify(arr);
            $.ajax({
                method: "POST",
                url: "controller/designation.php",
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
                $("#user_designation_modalLabel").html("Update User Designation");
                $("#save").html("Save");
                $.each(Response.data, function(index, value) {
                    $("#user_designation_id").val(value.user_designation_id);
                    $("#user_designation").val(value.user_designation);
                    $("#user_type_id").val(value.user_type_id);
                    $("#sort_code").val(value.sort_code);
                });
                $("#user_designation_modal").modal('show');
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
        var DataTable = $("#user_designation_table").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "paging": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            processing: true,
            serverSide: true,
            ajax: {
                url: "controller/designation.php",
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
                    "data": "user_designation"
                },
                {
                    "data": "user_type"
                },
                {
                    "data": "sort_code"
                },
                {
                    "data": "action",
                    "searchable": false,
                    "orderable": false
                },
            ]
        }).buttons().container().appendTo('#user_designation_table_wrapper .col-md-6:eq(0)');

        $(document).on('click', '#add_user_designation', function() {
            $("#user_designation_modalLabel").html("Add User Designation");
            $("#save").html("Add User Designation");
            $("#user_designation_id").val(0);
            let user_designation = $("#user_designation");
            let sort_code = $("#sort_code");
            let user_designation_error = $("#user_designation-error");
            let sort_code_error = $("#sort_code-error");
            user_designation.removeClass('is-invalid');
            sort_code.removeClass('is-invalid');
            user_designation_error.hide();
            sort_code_error.hide();
            $("#msg").hide();

            $("#user_designation").val("");
            $("#user_designation").val("").prop('disabled', false);

            $("#sort_code").val("");
            $("#sort_code").val("").prop('disabled', false);
            $('#user_designation_form').trigger("reset");
        });

        $.validator.setDefaults({
            submitHandler: function(e) {
                let user_designation_id = $.trim($("#user_designation_id").val());
                let user_designation = $.trim($("#user_designation").val());
                let user_type_id = $.trim($("#user_type_id").val());
                let sort_code = $.trim($("#sort_code").val());

                let action = 'add';
                if (user_designation_id > 0) {
                    action = 'update';
                }
                let arr = {
                    action: action,
                    user_designation_id: user_designation_id,
                    user_designation: user_designation,
                    user_type_id: user_type_id,
                    sort_code: sort_code,
                };
                var request = JSON.stringify(arr);
                $.ajax({
                    method: "POST",
                    url: "controller/designation.php",
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
                    $("#user_designation_modal").modal('hide');
                    $('#user_designation_table').DataTable().ajax.reload();
                    $("#message").html(Response.msg).show();
                    $("#user_designation_id").val(0);
                    $("#user_designation").val('');
                    $("#user_type_id").val('');
                    $("#sort_code").val('');
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
        $('#user_designation_form').validate({
            rules: {
                user_designation: {
                    required: true,
                    minlength: 3
                },
                user_type_id: {
                    required: true,
                },

            },
            messages: {
                user_designation: {
                    required: "Please enter a User Designation",
                    minlength: "User Designation must be at least 3 characters long"
                },
                user_type_id: {
                    required: "Please Select User Type",
                },
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
        user_type();
    });

    function user_type() {
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

            },
        }).done(function(Response) {
            console.log(Response.data);
            $("#user_type_id").html('<option value="">Select User Type</option>');
            $.each(Response.data, function(i, v) {
                if (v.user_type == 'Admin' || v.user_type == 'admin') {
                    $("#user_type_id").append('<option disabled value="' + v.user_type_id + '">' + v.user_type + '</option>');
                } else {
                    $("#user_type_id").append('<option value="' + v.user_type_id + '">' + v.user_type + '</option>');
                }
            })
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
</script>
<?php include "footer.php"; ?>