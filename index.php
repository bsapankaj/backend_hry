<?php
session_start();
if (isset($_SESSION['hryS_user_id'])) {
  header("location: home.php");
  die;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title> HRY-SHOP | Log in </title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="theme/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="theme/css/adminlte.min.css">
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a class="h1"><b>HRY-SHOP</b> Backend</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form method="post">
          <div class="input-group mb-3">
            <input type="text" id="username" class="form-control" placeholder="Username">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-3">
            <input type="password" id="password" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <button type="submit" id="login" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>


        <!-- /.social-auth-links -->
        <br>
        <!-- <p class="mb-1">
          <a href="forgot-password.html">I forgot my password</a>
        </p> -->

      </div>
      <!-- /.card-body -->
    </div>

    <div class="modal fade" id="password_change" tabindex="-1" role="dialog" aria-labelledby="password_change" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header text-center" style="display: inline!important;">
            <h4 class="text-primary"><b>Change Default Password</b></h4>
          </div>
          <div class="modal-body">
            <div class="col">
              <div class="form-group">
                <label for="default_usernamee">Username<span style="color:red;">*</span></label>
                <input type="text" name="default_username" id="default_username" class="form-control">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="task_type">Default Password<span style="color:red;">*</span></label>
                <input type="password" name="current_password" id="current_password" class="form-control">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="task_type">New Password<span style="color:red;">*</span></label>
                <input type="password" name="new_password" id="new_password" class="form-control">
              </div>
            </div>
            <div class="col">
              <div class="form-group">
                <label for="task_type">Confirm New Password<span style="color:red;">*</span></label>
                <input type="password" name="confirm_new_password" id="confirm_new_password" class="form-control">
              </div>
            </div>
            <div class="error text-danger text-center" style="font-size:14px;"></div>
            <div class="success text-success text-center" style="font-size:14px;"></div>
            <div class="col">
              <div class="form-group">
                <button class="btn btn-primary" type="submit" style="width:100%;" id="modify_password">Done</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- /.card -->
  </div>
  <!-- /.login-box -->

  <!-- jQuery -->
  <script src="theme/js/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="theme/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="theme/js/adminlte.min.js"></script>

  <script>
    $(document).ready(function() {
      $(document).on('click', '#modify_password', function(e) {
        e.preventDefault();
        var current_password = $('#current_password').val();
        var new_password = $('#new_password').val();
        var default_username = $('#default_username').val();
        var confirm_new_password = $('#confirm_new_password').val();
        $('.error').text('').hide();

        if(default_username == ''){
          $('.error').text('Please enter Username').show();
          $('#default_username').focus();
          return false;
        }

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
          activity: 'change_password',
          current_password: current_password,
          new_password: new_password,
          default_username:default_username,
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
          $('#current_password').val('');
          $('#new_password').val('');
          $('#confirm_new_password').val('');
          $('#password_change').modal('hide');
          alert('Password Change Successfully Please login');
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
      $("#login").on("click", function(e) {
        e.preventDefault();
        var username = $.trim($("#username").val());
        if (username == "") {
          alert('Please enter your username');
          $("#username").focus();
          return false;
        }
        var password = $.trim($("#password").val());
        if (username == "") {
          alert('Please enter your password');
          $("#password").focus();
          return false;
        }
        if (username != "" && password != "") {
          $.ajax({
            url: "controller/login.php",
            type: "POST",
            dataType: "json",
            async: false,
            headers: {
              "Content-Type": "application/json"
            },
            data: JSON.stringify({
              'activity': 'login',
              'username': username,
              'password': password
            }),
            success: function(response) {
              window.location.href = "home.php";
            },
            error: function(jqXHR, exception) {
              var msg = '';
              if (jqXHR.status === 0) {
                msg = 'Not connect.\n Verify Network.';
              } else if (jqXHR.status == 404) {
                msg = 'Requested page not found. [404]';
              } else if (jqXHR.status == 500) {
                msg = 'Internal Server Error [500].';
              } else if (jqXHR.status == 401) {
                msg = jqXHR.responseJSON.msg;
              } else if (jqXHR.status == 402) {
                $('#password_change').modal('show');
              } else if (exception === 'parsererror') {
                msg = 'Requested JSON parse failed.';
              } else if (exception === 'timeout') {
                msg = 'Time out error.';
              } else if (exception === 'abort') {
                msg = 'Ajax request aborted.';
              } else {
                msg = 'Uncaught Error.\n' + jqXHR.rsponseJSON.msg;
              }
              if (msg != '') {
                alert(msg);
              }
            }
          });
        };
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

</body>

</html>