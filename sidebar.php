<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="home.php" class="brand-link text-center">
        <!-- <img src="theme/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> -->
        <span class="brand-text font-weight-light">SHOP</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">

        <!-- SidebarSearch Form -->
        <!-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> -->

        <!-- Sidebar Menu -->
        <?php
        $master_link = [];
        // $master_link[] = '/hry_s/user_type.php';
        // $master_link[] = '/hry_s/user.php';
        // $master_link[] = '/rsp/client_code.php';
        // $master_link[] = '/rsp/case_detail.php';
        // $master_link[] = '/rsp/task_type.php';
        // $master_link[] = '/rsp/lawyer_assignment.php';
        // $master_link[] = '/rsp/court.php';
        // $master_link[] = '/rsp/justice.php';
        // $master_link[] = '/rsp/client_rate.php';
        // $master_link[] = '/rsp/file_master.php';
        ?>
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                <li class="nav-item">
                    <a href="home.php" class="nav-link 
                        <?php
                        if ($_SERVER['SCRIPT_NAME'] == '/hry_s/home.php') {
                            echo "active";
                        }
                        ?>">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php if($_SESSION['hryS_user_type'] == 'Manager') { ?>
                <li class="nav-item">
                    <a href="user.php" class="nav-link 
                        <?php
                        if ($_SERVER['SCRIPT_NAME'] == '/hry_s/user.php') {
                            echo "active";
                        } ?>">
                        <i class="far fa-user nav-icon"></i>
                        <p>User</p>
                    </a>
                </li>
                <?php } ?>
                <!-- <li class="nav-item 
                    <?php
                    if (in_array($_SERVER['SCRIPT_NAME'], $master_link)) {
                        echo 'menu-open';
                    } ?>">
                    <a href="#" class="nav-link 
                        <?php
                        if (in_array($_SERVER['SCRIPT_NAME'], $master_link)) {
                            echo "active";
                        } ?>">
                        <i class="nav-icon fas fa-star"></i>
                        <p>Master <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" 
                        <?php
                        if (in_array($_SERVER['SCRIPT_NAME'], $master_link)) {
                            echo 'style="display: block;"';
                        } ?>>
                        <li class="nav-item">
                            <a href="file_master.php" class="nav-link 
                                <?php
                                if ($_SERVER['SCRIPT_NAME'] == '/rsp/file_master.php') {
                                    echo "active";
                                } ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>File</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="client_code.php" class="nav-link 
                                <?php
                                if ($_SERVER['SCRIPT_NAME'] == '/rsp/client_code.php') {
                                    echo "active";
                                } ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Client Code</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="user_type.php" class="nav-link 
                                <?php
                                if ($_SERVER['SCRIPT_NAME'] == '/rsp/user_type.php') {
                                    echo "active";
                                } ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User Type</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="user.php" class="nav-link 
                                <?php
                                if ($_SERVER['SCRIPT_NAME'] == '/rsp/user.php') {
                                    echo "active";
                                } ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>User</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="task_type.php" class="nav-link 
                                <?php if ($_SERVER['SCRIPT_NAME'] == '/rsp/task_type.php') {
                                    echo "active";
                                } ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Task Type</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="case_detail.php" class="nav-link 
                                <?php if ($_SERVER['SCRIPT_NAME'] == '/rsp/case_detail.php') {
                                    echo "active";
                                } ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Case Detail</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="lawyer_assignment.php" class="nav-link 
                                <?php // if($_SERVER['SCRIPT_NAME']=='/rsp/lawyer_assignment.php') { echo "active";} 
                                ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Lawyer Assignment</p>
                            </a>
                        </li>



                        <li class="nav-item">
                            <a href="client_rate.php" class="nav-link 
                                <?php if ($_SERVER['SCRIPT_NAME'] == '/rsp/client_rate.php') {
                                    echo "active";
                                } ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Client Rate</p>
                            </a>
                        </li>

                    </ul>
                </li> -->

                <!-- <li class="nav-item 
                    <?php if ($_SERVER['SCRIPT_NAME'] == '/rsp/billing_new.php' || $_SERVER['SCRIPT_NAME'] == '/rsp/print_bill.php') {
                        echo 'menu-open';
                    } ?>">
                    <a href="#" class="nav-link 
                        <?php if ($_SERVER['SCRIPT_NAME'] == '/rsp/billing_new.php' || $_SERVER['SCRIPT_NAME'] == '/rsp/print_bill.php') {
                            echo "active";
                        } ?>">
                        <i class="nav-icon fas fa-file-invoice"></i>
                        <p>Invoice <i class="fas fa-angle-left right"></i></p>
                    </a>
                    <ul class="nav nav-treeview" 
                        <?php
                            if ($_SERVER['SCRIPT_NAME'] == '/rsp/billing_new.php' || $_SERVER['SCRIPT_NAME'] == '/rsp/print_bill.php') {
                                echo 'style="display: block;"';
                            } ?>>
                        <li class="nav-item">
                            <a href="billing_new.php" class="nav-link 
                                <?php if ($_SERVER['SCRIPT_NAME'] == '/rsp/billing_new.php') {
                                    echo "active";
                                } ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Generate Bill</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="print_bill.php" class="nav-link 
                                <?php if ($_SERVER['SCRIPT_NAME'] == '/rsp/print_bill.php') {
                                    echo "active";
                                } ?>">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Edit/Print Bill </p>
                            </a>
                        </li>
                    </ul>
                </li> -->

                <!-- <li class="nav-item">
                    <a href="time_sheet.php" class="nav-link 
                        <?php if ($_SERVER['SCRIPT_NAME'] == '/rsp/time_sheet.php') {
                            echo "active";
                        } ?>">
                        <i class="nav-icon fa fa-regular fa-clock"></i>
                        <p>Time Sheet</p>
                    </a>
                </li> -->

                <!-- <li class="nav-item">
                    <a href="lawyer_advisory.php" class="nav-link 
                        <?php if ($_SERVER['SCRIPT_NAME'] == '/rsp/lawyer_advisory.php') {
                            echo "active";
                        } ?>">
                        <i class="nav-icon fa fa-briefcase"></i>
                        <p>Senior Lawyer Advisory</p>
                    </a>
                </li> -->

                <!-- <li class="nav-item">
                    <a href="cause_list.php" class="nav-link 
                        <?php if ($_SERVER['SCRIPT_NAME'] == '/rsp/cause_list.php') {
                            echo "active";
                        } ?>">
                        <i class="nav-icon fa fa-list"></i>
                        <p>Cause List Details</p>
                    </a>
                </li> -->

                <!-- <li class="nav-item">
                    <a href="case_daily_expense.php" class="nav-link 
                        <?php if ($_SERVER['SCRIPT_NAME'] == '/rsp/case_daily_expense.php') {
                            echo "active";
                        } ?>">
                        <i class="nav-icon fa fa-money"></i>
                        <p>Case Daily Expenses</p>
                    </a>
                </li> -->

            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>