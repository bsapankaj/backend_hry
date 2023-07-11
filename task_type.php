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
                    <h1 class="m-0">Task Type</h1>
                </div><!-- /.col -->
                <div class="col-sm-6 d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" id="add_task_type" data-toggle="modal" data-target="#task_type_modal">
                        Add Task Type
                    </button>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->


    <!-- Start content -->
    <section class="content">
        <!-- Add Client Modal -->
        <div class="modal fade" id="task_type_modal" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-md" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="task_type_modal_label" >Add Task Type</h5>
                        <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="task_type_form" name="task_type_form" method="post">
                        <div class="modal-body">
                            <!-- client code  -->
                            <div class="row">
                                <div id="msg"></div>
                            </div>
                            <div class="row">    
                                 <!-- Update Hidden id  -->
                                <input type="hidden" name="task_id" id="task_id" class="form-control" value="0">

                                <div class="col">
                                    <div class="form-group">
                                        <label for="task_type">Task Type<span class="must">*</span></label>
                                        <input type="text" name="task_type" id="task_type" class="form-control">
                                    </div>
                                </div>

                                <div class="col">
                                <div class="form-group">
                                        <label for="sort_code">Short Code</label>
                                        <input type="text" name="sort_code" id="sort_code" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col">
                                    <label for="task_description">Task Description</label>
                                    <textarea name="task_description" id="task_description" class="form-control"></textarea>
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
                <table id="task_type_table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Task Type</th>
                            <th>Short Code</th>
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
        $(document).on('click','.delete',function(e) {
            if (confirm("Are you sure delete this Task Detail!")) {
                let task_id = $(this).data('id');
                let arr = { 
                    action : 'delete',
                    task_id: task_id
                };
                var request = JSON.stringify(arr); 
                $.ajax({
                    method: "POST",
                    url: "controller/task_type.php",
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
                    $('#task_type_table').DataTable().ajax.reload();
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
            let task_id = $(this).data('id');            
            let arr = { 
                action : 'get',
                task_id: task_id
            };
            var request = JSON.stringify(arr); 
            $.ajax({
                method: "POST",
                url: "controller/task_type.php",
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
                title = $("#task_type_modal_label").html("Update Task Type");
                $.each(Response.data, function(index, value) {
                    console.log(value);
                    $("#task_id").val(value.task_id);
                    $("#task_type").val(value.task_type);
                    $("#sort_code").val(value.sort_code);
                    $("#task_description").val(value.task_description);
                });

                $("#task_type_modal").modal('show');

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
        });
        var DataTable =  $("#task_type_table").DataTable({
            "responsive": true,
            "lengthChange": true,
            "autoWidth": false,
            "paging": true,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
            processing: true,
            serverSide: true,
            ajax: {
                url: "controller/task_type.php",
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
                { "data": "task_type"},
                { "data": "sort_code"},
                { "data": "action", "searchable": false, "orderable": false },
            ]
        }).buttons().container().appendTo('#task_type_table_wrapper .col-md-6:eq(0)');
         
         // Add Button click
        let title = $("#task_type_modal_label");
        let save = $("#save");
        $(document).on('click','#add_task_type',function(){
            title.html("Add Task Type");
            save.html("save");
            $("#task_id").val(0);
            let task_type = $("#task_type");
            let sort_code = $("#sort_code");
            let task_type_error = $("#task_type-error");
            let sort_code_error = $("#sort_code-error");
            task_type.removeClass('is-invalid');
            sort_code.removeClass('is-invalid');
            cl_code_error.hide();
            sort_code_error.hide();
            $("#msg").hide();
            
            $("#task_type").val("");
            $("#task_type").val("").prop('disabled', false);
            $('#task_type_form').trigger("reset");
        });
        
        $.validator.setDefaults({
            submitHandler: function (e) {
                let task_id = $.trim($("#task_id").val());
                let task_type = $.trim($("#task_type").val());
                let sort_code = $.trim($("#sort_code").val());
                let task_description = $.trim($("#task_description").val());
                let action = 'add';
                if(task_id>0) {
                    action = 'update';
                }
                let arr = { 
                    action : action,
                    task_id: task_id,
                    task_type: task_type,
                    sort_code: sort_code, 
                    task_description: task_description 

                };
                var request = JSON.stringify(arr);                
                $.ajax({
                    method: "POST",
                    url: "controller/task_type.php",
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
                    $("#task_type_modal").modal('hide');
                    $('#task_type_table').DataTable().ajax.reload();
                    $("#message").html(Response.msg).show();
                    $("#task_id").val(0);
                    $("#task_type").val('');
                    $("#sort_code").val('');
                    $("#task_description").val('');
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
        $('#task_type_form').validate({
            rules: {
                task_type: {
                    required: true,
                    minlength: 3
                },
                task_description:{
                    maxlength: 150
                }

            },
            messages: {
                task_type: {
                    required: "Please enter a Task Type",
                    minlength: "Task Type must be at least 3 characters long"
                },
                task_description:{
                    maxlength: "Please enter no more than 150 characters",
                }
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