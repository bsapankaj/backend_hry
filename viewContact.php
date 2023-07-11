<?php
@session_start();
// print_r($_SESSION);exit;
$type = $_SESSION['hryS_user_type'];
// print_r($type);exit;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- basic -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- mobile metas -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <!-- site metas -->
    <title>Harry|Shop</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- bootstrap css -->
    <link rel="stylesheet" href="css/css/bootstrap.min.css">
    <!-- style css -->
    <link rel="stylesheet" href="css/css/style.css">
    <!-- Responsive-->
    <link rel="stylesheet" href="css/css/responsive.css">
    <!-- fevicon -->
    <link rel="icon" href="images/images/fevicon.png" type="image/gif" />
    <!-- Scrollbar Custom CSS -->
    <link rel="stylesheet" href="css/css/jquery.mCustomScrollbar.min.css">
    <!-- Tweaks for older IEs-->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script><![endif]-->
    <style>
        .btn_sand {
            background: #282e40;
            color: #fff;
            padding: 0;
            float: left;
            font-size: 20px;
            font-weight: 300;
            border-radius: 0;
            margin-right: 0;
            width: 220px;
            height: 65px;
            text-align: center;
            line-height: 65px;
        }

        a.readmore_bt {
            color: #fff;
            font-weight: 300;
            text-decoration: underline !important;
        }

        .btn_sand:hover,
        .btn_sand:focus {
            background: #5293b7;
            color: #fff;
        }
        button.btn_sand {
            background: #12c6f4;
            color: #111;
        }
    </style>
</head>
<!-- body -->

<body class="main-layout">
    <!-- loader  -->
    <!-- <div class="loader_bg">
        <div class="loader"><img src="images/images/loading.gif" alt="#" /></div>
    </div> -->
    <!-- end loader -->
    <!-- header -->
    <header id="home">
        <!-- header inner -->
        <div class="header">
            <div class="container">
                <div class="row">
                    <div class="col-xl-2 col-lg-2 col-md-2 col-sm-2 col logo_section">
                        <div class="full">
                            <div class="center-desk">
                                <div class="logo"> <a href="home.php"><img src="images/images/logo.png" alt="#"></a> </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-10 col-lg-10 col-md-10 col-sm-10">
                        <div class="menu-area">
                            <div class="limit-box">
                                <nav class="main-menu">
                                    <ul class="menu-area-main" style="margin-right: -7px;">
                                        <li class="active"><a href="viewHome.php">Home</a></li>
                                        <li><a href="viewAbout.php">About Us</a></li>
                                        <li><a href="viewFruits.php">Fruits</a></li>
                                        <li><a href="viewBlog.php">Blog</a></li>
                                        <li><a href="viewContact.php">Contact Us</a></li>
                                        <li><a href="home.php">Dashboard</a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- end header inner -->
    </header>
    <!-- end header -->
    <!-- slider -->
    <section class="slider_section">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="full">
                        <h1><strong class="cur">Best</strong><br>Fresh Red Apple</h1>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="full text_align_center">
                        <img class="slide_img" src="images/images/slider_img.png" alt="#" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end slider -->
    <!-- Contact Us -->
    <div id="contact" class="section layout_padding">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6 padding_left_0">
                    <div class="full">
                        <img class="float-left" src="images/images/fruit_img.png" alt="#" />
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="heading">
                        <h2>Request a <strong class="theme_blue">Call Back</strong></h2>
                    </div>
                    <div class="full margin_top_20">
                        <form id="contact_Us" name="contact_Us" method="post">
                            <div class="row">
                                <input type="hidden" name="contactUs_id" id="contactUs_id" class="form-control" value="0">
                                <div class="col-sm-12">
                                    <input class="form-control" name="name" id="name" style="color:white;" placeholder="Your Name" type="text">
                                </div>
                                <div class="col-sm-12">
                                    <input class="form-control" name="email_id" id="email_id" style="color:white;" placeholder="Email" type="Email">
                                </div>
                                <div class="col-sm-12">
                                    <input class="form-control" name="mobile_no" id="mobile_no" style="color:white;" placeholder="Phone" type="number" min="0">
                                </div>
                                <div class="col-sm-12">
                                    <textarea class="form-control textarea" name="contactMessage" id="contactMessage" style="color:white;" placeholder="Message"></textarea>
                                </div>
                            </div>
                            <!-- class="main_bt" -->
                            <button id="save" class="btn_sand">Send</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- end Contact Us -->
    <!-- end footer -->
    <footer>
        <div class="footer layout_padding">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <h3>Contact Us</h3>
                        <p>Healing Center, 176 W Street name, New York, NY<br><br>(+91) 987 654 3210<br><br>demo@gmail.com</p>
                    </div>
                    <div class="col-lg-2 col-md-6 col-sm-12">
                        <h3>Pages</h3>
                        <p>
                            <a href="viewHome.php">Home</a><br>
                            <a href="viewAbout.php">About Us</a><br>
                            <a href="viewFruits.php">Fruits</a><br>
                            <a href="viewBlog.php">Blog</a><br>
                            <a href="viewContact.php">Contact Us</a><br>
                            <a href="home.php">Dashboard</a>
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <h3>Recent Post</h3>
                        <p>
                            <span><img src="images/images/f_b1.png"></span>
                            <span>consectetur adipisc elit,<br>sed do eiusmod</span>
                        </p>
                        <p class="margin_top_20">
                            <span><img src="images/images/f_b2.png"></span>
                            <span>consectetur adipisc elit,<br>sed do eiusmod</span>
                        </p>
                        <p class="margin_top_20">
                            <span><img src="images/images/f_b3.png"></span>
                            <span>consectetur adipisc elit,<br>sed do eiusmod</span>
                        </p>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <h3>User Request</h3>
                        <p>
                            <a href="user_request.php">
                                <button type="button" class="btn btn-primary w-100">New User Request</button>
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- end footer -->
    <!-- Javascript files-->
    <script src="js/js/jquery.min.js"></script>
    <script src="js/js/popper.min.js"></script>
    <script src="js/js/bootstrap.bundle.min.js"></script>
    <script src="js/js/jquery-3.0.0.min.js"></script>
    <script src="js/js/plugin.js"></script>


    <script src="theme/plugins/jquery-validation/jquery.validate.min.js"></script>
    <script src="theme/plugins/jquery-validation/additional-methods.min.js"></script>
    <!-- sidebar -->
    <script src="js/js/jquery.mCustomScrollbar.concat.min.js"></script>
    <script src="js/js/custom.js"></script>
    <script>
        $(function() { // from is not working ..
            // $('#contact_Us').submit(function() {
            //     $("#contactUs_id").val(0);
            //     var name = $('#name').val();
            //     var email_id = $('#email_id').val();
            //     var mobile_no = $('#mobile_no').val();
            //     var contactMessage = $('#contactMessage').text();
            // });
            $(document).on('click', '#save', function() {
                // alert("abc");
                $("#user_request_id").val(0);
                let name = $("#name").val();
                if (name == '') {
                    alert("Please enter a User Name");
                    return false;
                }
                let email_id = $("#email_id").val();
                if (email_id == '') {
                    alert("Please enter a Email ID");
                    return false;
                }
                let mobile_no = $("#mobile_no").val();
                if (mobile_no == '') {
                    alert("Please enter a Mobile No");
                    return false;
                }
                let contactMessage = $("#contactMessage").val();
                $("#msg").hide();
                // $('#contact_Us').trigger("reset");
            });
            $.validator.setDefaults({
                submitHandler: function(e) {
                    let contactUs_id = $.trim($("#contactUs_id").val());
                    let name = $.trim($("#name").val());
                    let email_id = $.trim($("#email_id").val());
                    let contactMessage = $.trim($("#contactMessage").val());
                    let mobile_no = $.trim($("#mobile_no").val());
                    let action = 'add';
                    let arr = {
                        action: action,
                        contactUs_id: contactUs_id,
                        name: name,
                        email_id: email_id,
                        contactMessage: contactMessage,
                        mobile_no: mobile_no

                    };
                    var request = JSON.stringify(arr);
                    $.ajax({
                        method: "POST",
                        url: "controller/contact_us.php",
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
                        // $("#user_table").DataTable().ajax.reload();
                        $("#message").html(Response.msg).show();
                        $("#contactUs_id").val(0);
                        $("#name").val('');
                        $("#email_id").val('');
                        $("#contactMessage").val('');
                        $("#mobile_no").val('');
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
            // $('#contact_Us').validate({
            //     rules: {
            //         name: {
            //             required: true,
            //             minlength: 3
            //         },
            //         email_id: {
            //             required: true,
            //             minlength: 8
            //         },
            //         mobile_no: {
            //             required: true,
            //             minlength: 10,
            //             maxlength: 10,
            //             digits: true,
            //         }

            //     },
            //     messages: {
            //         name: {
            //             required: "Please enter a User Name",
            //             minlength: "User Name must be at least 3 characters long.",
            //         },
            //         email_id: {
            //             required: "Please provide a User Email",
            //             minlength: "User Email must be at least 8 characters long.",
            //         },
            //         mobile_no: {
            //             required: "Please provide a User Mobile",
            //             minlength: "User Mobile must be at least 10 digits long.",
            //         }
            //     },
            //     errorElement: 'span',
            //     errorPlacement: function(error, element) {
            //         error.addClass('invalid-feedback');
            //         element.closest('.form-group').append(error);
            //     },
            //     highlight: function(element, errorClass, validClass) {
            //         $(element).addClass('is-invalid');
            //     },
            //     unhighlight: function(element, errorClass, validClass) {
            //         $(element).removeClass('is-invalid');
            //     }
            // });
        });
    </script>
</body>

</html>