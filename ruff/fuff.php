<?php
    $activity_date = date('Y-m-d');
    $statement = $connection->prepare("select a.activity_id , f.is_cheque_collected  from activity_dbfc a
    inner join feedback_dbfc f on f.activity_id=a.activity_id
    where  activity_date='$activity_date' and is_cheque_collected IN('N','Y')");
    $statement->execute();
    // print_r($statement);exit;
    $row = $statement->fetch();
    $statement->closeCursor();

    $activity_id = $row['activity_id'];
    $statement = $connection->prepare("select a.activity_id,a.activity_date,cc.client_code,
    ci.pickup_time_to as pickup_to, ci.pickup_time_from as pickup_from,f.transaction_date,
    customer_name,f.is_cheque_collected, f.latitude as lati_tude, f.longitude as long_tude
    from activity_dbfc a
    inner join customer_information ci on ci.customer_id=a.customer_id
    inner join client_code_dbfc cc on cc.client_code_id=ci.client_code_id
    inner join franchisee_master fm on fm.franchisee_id=a.franchisee_id
    inner join fe_master fe on fe.fe_id=a.fe_id
    left outer join doxcol.mobile_login df on df.bsagroup_fe_id=fe.fe_id
    left outer join feedback_dbfc f on f.activity_id=a.activity_id
    where a.activity_id='$activity_id'");
    $statement->execute();
    $row = $statement->fetch();
    // print_r($row);exit;
    $statement->closeCursor();
    $lati_longi_tuds = [
        $row['lati_tude'],
        $row['long_tude']
    ];
    // print_r($lati_longi_tuds);exit;
    // $lati_tude=$row['lati_tude'];
    // $long_tude=$row['long_tude'];

    if ($row['is_cheque_collected'] == 'Y' || $row['is_cheque_collected'] == 'N') {
        $standard_activity_start_time = $row['activity_date'] . ' ' . $row['pickup_from'];
        $standard_activity_end_time = $row['activity_date'] . ' ' . $row['pickup_to'];
        $activity_time = $row['transaction_date'];
        if (strtotime($standard_activity_start_time) <= strtotime($activity_time) && strtotime($activity_time) <= strtotime($standard_activity_end_time)) {
            $color = '<b style="color:green;">With in Tat</b>';
        } else {
            $color = '<b style="color:red;">Out of Tat</b>';
        }
    }
?>