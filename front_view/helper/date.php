<?php
    if(!function_exists('time_difference')) {        
        function time_difference($start_time, $end_time){
            $hour1 = 0; $hour2 = 0;

            $date1 = strtotime($start_time);
            $date2 = strtotime($end_time);
            $diff = $date2-$date1;
            return $diff/60;

            // $date1 = new DateTime($start_time);
            // $date2 = new DateTime($end_time);
            // $interval = $date1->diff($date2);
            // if($interval->format('%a') > 0){
            //     $hour1 = $interval->format('%a')*24*60;
            // }
            // if($interval->format('%i') > 0){
            //     $hour2 = $interval->format('%i');
            // }
            // return ($hour1 + $hour2);
        }
    }

    
    if(!function_exists('getDuration')) {        
        function getDuration($minutes=0){
            return round($minutes/60);
        }
    }

?>