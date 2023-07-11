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
        <!-- Content Header (Page header) style="background-image: url(images/images/set-fresh-fruits.png);" -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-12">
                        <h1 class="m-0">User Request</h1>
                    </div>
                    <div class="alert alert-warning alert-dismissible fade hide d-none col-sm-12" role="alert" id="notice" style=" margin-top:5px;">
                        <p id="message"></p>
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="col-sm-12 d-flex justify-content-center ">
                        <form id="Contact_from" name="Contact_from" method="post">
                            <div class="modal-body">
                                <div class="row">
                                    <div id="msg"></div>
                                </div>
                                <div class="row">
                                    <input type="hidden" name="contactUs_id" id="contactUs_id" class="form-control" value="0">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name">Name :<span class="must">*</span></label>
                                            <input type="text" name="contact_name" id="contact_name" class="form-control" style="width:400px;">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
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
                                            <label for="mobile_no">Mobile :<span class="must">*</span></label>
                                            <input type="number" name="mobile_no" id="mobile_no" min="1" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <div class="form-group">
                                            <label for="name">Message :</label>
                                            <textarea name="contactMessage" id="contactMessage" class="form-control"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" id="save" class="btn btn-primary">Sand</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.content-header -->
    <?php
    }
    ?>
    <?php
    if ($type == "Manager") {
    ?>
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

                <!-- /.card-header   -->
                <div class="card-body">
                    <table id="contact_Us" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>S.No.</th>
                                <th>Name</th>
                                <th>Email Id</th>
                                <th>Mobile No</th>
                                <th>Message</th>
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
            </div>
            <!-- /.card -->
            <!-- Start Client Table -->
        </section>
        <!-- End content -->
    <?php
        }
    ?>
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
                let contactUs_id = $(this).data('id');
                let arr = {
                    action: 'delete',
                    contactUs_id: contactUs_id
                };
                var request = JSON.stringify(arr);
                $.ajax({
                    method: "POST",
                    url: "controller/problom.php",
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
        // $(document).on('click', '.edit', function(e) {
        //     $('#password').val('');
        //     let contactUs_id = $(this).data('id');
        //     let arr = {
        //         action: 'get',
        //         contactUs_id: contactUs_id,
        //     };
        //     var request = JSON.stringify(arr);
        //     $.ajax({
        //         method: "POST",
        //         url: "controller/problom.php",
        //         data: request,
        //         dataType: "JSON",
        //         async: false,
        //         headers: {
        //             "Content-Type": "application/json"
        //         },
        //         beforeSend: function() {
        //             console.log(request);
        //         },
        //     }).done(function(Response) {
        //         title = $("#add_user_modalLabel").html("Update user Detail");
        //         save.html("Update User");
        //         $.each(Response.data, function(index, value) {
        //             console.log(value);
        //             $("#contactUs_id").val(value.contactUs_id);
        //             $("#user_request_name").val(value.user_request_name);
        //             $("#email_id").val(value.email_id);
        //             $("#company_name").val(value.company_name);
        //             $("#mobile_no").val(value.mobile_no);
        //             $("#user_type_id").val(value.user_type_id);
        //             $("#father_name").val(value.father_name);
        //             $("#address").val(value.address);
        //             if (value.pincode != 0) {
        //                 $("#pincode").val(value.pincode);
        //             } else {
        //                 $("#pincode").val('');
        //             }
        //         });

        //         $("#add_user_modal").modal('show');

        //     }).fail(function(jqXHR, exception) {
        //         var msg = '';
        //         if (jqXHR.status === 0) {
        //             msg = 'Not connect.\n Verify Network.';
        //         } else if (jqXHR.status == 404) {
        //             msg = 'Requested page not found. [404]';
        //         } else if (jqXHR.status == 500) {
        //             msg = 'Internal Server Error [500].';
        //         } else if (exception === 'parsererror') {
        //             msg = 'Requested JSON parse failed.';
        //         } else if (exception === 'timeout') {
        //             msg = 'Time out error.';
        //         } else if (exception === 'abort') {
        //             msg = 'Ajax request aborted.';
        //         } else {
        //             msg = 'Uncaught Error.\n' + jqXHR.responseJSON.msg;
        //         }
        //         $("#message").html(msg).show();
        //     }).always(function(xhr) {
        //         console.log(xhr);
        //     });
        // });
        var DataTable = $("#contact_Us").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "paging": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            processing: true,
            serverSide: true,
            ajax: {
                url: "controller/problom.php",
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
                    "data": "contact_name"
                },
                {
                    "data": "email_id"
                },
                {
                    "data": "mobile_no"
                },
                {
                    "data": "contactMessage"
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
        }).buttons().container().appendTo('#contact_Us_wrapper .col-md-6:eq(0)');
        // Add Button click
        let title = $("#add_user_modalLabel");
        let save = $("#save");
        $(document).on('click', '#add_user', function() {
            save.html("Add User");
            $("#contactUs_id").val(0);
            let contact_name = $("#contact_name");
            let email_id = $("#email_id");
            let contactMessage = $("#contactMessage");
            let mobile_no = $("#mobile_no");
            
            let contact_name_error = $("#contact_name-error");
            let email_id_error = $("#email_id-error");
            let contactMessage_error = $("#contactMessage-error");
            let mobile_no_error = $("#mobile_no-error");
            contact_name.removeClass('is-invalid');
            email_id.removeClass('is-invalid');
            contactMessage.removeClass('is-invalid');
            mobile_no.removeClass('is-invalid');
            // user_request_name_error.hide();
            email_id_error.hide();
            contactMessage.hide();
            mobile_no_error.hide();
            pincode_error.hide();
            $("#msg").hide();
            $("#contact_name").val("");
            $("#contact_name").val("").prop('disabled', false);
            $("#email_id").val("");
            $("#email_id").val("").prop('disabled', false);
            $("#mobile_no").val("");
            $("#mobile_no").val("").prop('disabled', false);
            $("#contactMessage").val("");
            // $('#user_from').trigger("reset");
        });
        $.validator.setDefaults({
            submitHandler: function(e) {
                let contactUs_id = $.trim($("#contactUs_id").val());
                let contact_name = $.trim($("#contact_name").val());
                let email_id = $.trim($("#email_id").val());
                let mobile_no = $.trim($("#mobile_no").val());
                let action = 'add';
                let contactMessage = $.trim($("textarea#contactMessage").val());
                if (contactUs_id > 0) {
                    action = 'update';
                }
                let arr = {
                    action: action,
                    contactUs_id: contactUs_id,
                    contact_name: contact_name,
                    email_id: email_id,
                    contactMessage: contactMessage,
                    mobile_no: mobile_no
                };
                var request = JSON.stringify(arr);
                $.ajax({
                    method: "POST",
                    url: "controller/problom.php",
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
                    $("#contactUs_id").val(0);
                    $("#contact_name").val('');
                    $("#email_id").val('');
                    $("#contactMessage").val('');
                    $("#mobile_no").val('');
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
        $('#Contact_from').validate({
            rules: {
                contact_name: {
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
                }                
            },
            messages: {
                contact_name: {
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