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
                    <h1 class="m-0">User Type</h1>
                </div><!-- /.col -->
                <div class="col-sm-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="add_user_type" data-toggle="modal" data-target="#user_type_modal">
                        Click to Add User Type
                    </button>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Start content -->
    <section class="content">
        <!-- Add Client Modal -->
        <div class="modal fade" id="user_type_modal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="user_type_modalLabel" >Add User Type</h5>
                        <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="user_type_form" name="user_type_form" method="post">
                        <div class="modal-body">
                            <!-- client code  -->
                            <div class="row">
                                <div id="msg"></div>
                            </div>
                            <div class="row">    
                            <!-- Update Hidden id  -->
                                <input type="hidden" name="user_type_id" id="user_type_id" class="form-control" value="0">
                             <div class="col">
                                <div class="form-group">
                                        <label for="user_type">User Type<span class="must">*</span></label>
                                        <input type="text" name="user_type" id="user_type" class="form-control">
                                    </div>
                                </div>
                             <div class="col">
                                  <div class="form-group">
                                        <label for="sort_code">Short Code<span class="must"></span></label>
                                        <input type="number" name="sort_code" id="sort_code" class="form-control" min="1">
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
                <table id="user_type_table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No.</th>
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
    $(function () {
        $('#datetimepicker').datetimepicker({
            format: 'DD-MM-YYYY'
        });
        $(document).on('click','.delete',function(e) {
            if (confirm("Are you sure delete this Case Detail!")) {
                let user_type_id = $(this).data('id');
                let arr = { 
                    action : 'delete',
                    user_type_id: user_type_id
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
                }).done(function (Response) {
                    $('#user_type_table').DataTable().ajax.reload();
                    $("#message").html(Response.msg).show();
                    $("#notice").removeClass("d-none");
                    $("#notice").removeClass("hide");
                    $("#notice").addClass("d-block");
                    $("#notice").addClass("show");
                }).fail(function (jqXHR, exception) {
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
                }).always(function (xhr) {
                    console.log(xhr);
                });
            }
        });
    
        $(document).on('click','.edit',function(e){
            let user_type_id = $(this).data('id');
            let user_type = $(this).data('utype');
            let user_Scode = $(this).data('scode');
            $("#user_type_id").val(user_type_id);
            $("#user_type").val(user_type);
            $("#sort_code").val(user_Scode);
            $("#user_type_modal").modal('show');
        });
        var DataTable =  $("#user_type_table").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "paging": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            processing: true,
            serverSide: true,
            ajax: {
                url: "controller/user_type.php",
                type: "POST",
                dataType: "json",
                async: false,
                headers: {
                    "Content-Type": "application/json"
                },
                data: function (d) {
                    d.action = 'get';
                    return JSON.stringify(d);
                }
            },
            "columns": [
                { "data": "s_no", "searchable": false, "orderable": false },
                { "data": "user_type"},
                { "data": "sort_code"},
                { "data": "action", "searchable": false, "orderable": false },
            ]
        }).buttons().container().appendTo('#user_type_table_wrapper .col-md-6:eq(0)');

        // Add Button click
        let title = $("#user_type_modalLabel");
        let save = $("#save");
        // from submit
        $(document).on('click','#add_user_type',function(){
            title.html("Add User Type");
            save.html("Add User Type");
            $("#user_type_id").val(0);
            let user_type= $("#user_type");
            let sort_code = $("#sort_code");
            let user_type_error = $("#user_type-error");
            let sort_code_error = $("#sort_code-error");
            user_type.removeClass('is-invalid');
            sort_code.removeClass('is-invalid');
            user_type_error.hide();
            sort_code_error.hide();
            $("#msg").hide();
            
            $("#user_type").val("");
            $("#user_type").val("").prop('disabled', false);

            $("#sort_code").val("");
            $("#sort_code").val("").prop('disabled', false);
            $('#user_type_form').trigger("reset");
        });
        $.validator.setDefaults({
            submitHandler: function (e) {
                let user_type_id = $.trim($("#user_type_id").val());
                let user_type = $.trim($("#user_type").val());
                let sort_code = $.trim($("#sort_code").val());
             
                let action = 'add';
                if(user_type_id>0) {
                    action = 'update';
                }
                let arr = {
                    action : action,
                    user_type_id: user_type_id,
                    user_type: user_type,
                    sort_code: sort_code, 
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
                }).done(function (Response) {
                    $("#user_type_modal").modal('hide');
                    $('#user_type_table').DataTable().ajax.reload();
                    $("#message").html(Response.msg).show();
                    $("#user_type_id").val(0);
                    $("#user_type").val('');
                    $("#sort_code").val('');
                    $("#notice").removeClass("d-none");
                    $("#notice").removeClass("hide");
                    $("#notice").addClass("d-block");
                    $("#notice").addClass("show");
                }).fail(function (jqXHR, exception) {
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
                }).always(function (xhr) {
                    console.log(xhr);
                });
            }
        });
        // form validation
        $('#user_type_form').validate({
            rules: {
                user_type: {
                    required: true,
                    minlength: 3
                },
               
            },
            messages: {
                user_type: {
                    required: "Please enter a User Type",
                    minlength: "User Type must be at least 3 characters long"
                },
                

            },
            errorElement: 'span',
            errorPlacement: function (error, element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass('is-invalid');
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass('is-invalid');
            }
        });
    });  
</script>
<?php include "footer.php"; ?>