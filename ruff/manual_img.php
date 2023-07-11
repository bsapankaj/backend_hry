<?php
if ($row['connection_type'] == "individual") {
    if (!empty($row['modified_address'])) {
        $case_story .= " Modified Address=" . $row['modified_address'] . ",";
    }
    if (!empty($row['location_of_house'])) {
        $case_story .= " Location Of House=" . $row['location_of_house'] . ",";
    }
    if (!empty($row['negative_area'])) {
        $case_story .= " Negative Area=" . $row['negative_area'] . ",";
    }
    if (!empty($row['type_of_residence'])) {
        $case_story .= " Type Of Residence=" . $row['type_of_residence'] . ",";
    }
    if (!empty($row['name_of_person_met'])) {
        $case_story .= " Name Of Person Met=" . $row['name_of_person_met'] . ",";
    }
    if (!empty($row['relation_with_applicant'])) {
        $case_story .= " Relation With Applicant=" . $row['relation_with_applicant'] . ",";
    }
    if (!empty($row['connection_applied'])) {
        $case_story .= " Connection Applied=" . $row['connection_applied'] . ",";
    }
    if (!empty($row['no_of_connection_applied'])) {
        $case_story .= " No Of Connection Applied=" . $row['no_of_connection_applied'] . ",";
    }
    if (!empty($row['plan_awareness'])) {
        $case_story .= " Plan Awareness=" . $row['plan_awareness'] . ",";
    }
    if (!empty($row['ownership_of_house'])) {
        $case_story .= " Ownership Of House=" . $row['ownership_of_house'] . ",";
    }
    if (!empty($row['tenure_of_stay'])) {
        $case_story .= " Tenure Of Stay=" . $row['tenure_of_stay'] . ",";
    }
    if (!empty($row['area_in_sq_ft'])) {
        $case_story .= " Area In Sq. Ft.=" . $row['area_in_sq_ft'] . ",";
    }
    if (!empty($row['color_of_building'])) {
        $case_story .= " Color Of Building=" . $row['color_of_building'] . ",";
    }
    if (!empty($row['educational_qualification'])) {
        $case_story .= " Educational Qualification=" . $row['educational_qualification'] . ",";
    }
    if (!empty($row['employement_type'])) {
        $case_story .= " Employement Type=" . $row['employement_type'] . ",";
    }
    if (!empty($row['no_of_years_in_business_job'])) {
        $case_story .= " No Of Years In Business Job=" . $row['no_of_years_in_business_job'] . ",";
    }
    if (!empty($row['educational_qualification'])) {
        $case_story .= " Educational Qualification=" . $row['educational_qualification'] . ",";
    }
    if (!empty($row['employement_type'])) {
        $case_story .= " Employement Type=" . $row['employement_type'] . ",";
    }
    if (!empty($row['no_of_years_in_business_job'])) {
        $case_story .= " No Of Years In Business Job=" . $row['no_of_years_in_business_job'] . ",";
    }
    if (!empty($row['designation'])) {
        $case_story .= " Designation=" . $row['designation'] . ",";
    }
    if (!empty($row['go_green'])) {
        $case_story .= " Go Green=" . $row['go_green'] . ",";
    }
    if (!empty($row['email'])) {
        $case_story .= " Email=" . $row['email'] . ",";
    }
    if (!empty($row['alternate_no'])) {
        $case_story .= " Alternate No=" . $row['alternate_no'] . ",";
    }
} else if ($row['connection_type'] == "profiling") {
    if (!empty($row['modified_address'])) {
        $case_story .= " Modified Address=" . $row['modified_address'] . ",";
    }
    if (!empty($row['tracebility'])) {
        $case_story .= " Location Tracebility=" . $row['tracebility'] . ",";
    }
    if (!empty($row['type_of_office'])) {
        $case_story .= " Type Of Office=" . $row['type_of_office'] . ",";
    }
    if (!empty($row['company_exist_at_given_address'])) {
        $case_story .= " Company Exist At Given Address=" . $row['company_exist_at_given_address'] . ",";
    }
    if (!empty($row['name_of_person_met'])) {
        $case_story .= " Name Of Person Met=" . $row['name_of_person_met'] . ",";
    }
    if (!empty($row['designation'])) {
        $case_story .= " Met Person Designation=" . $row['designation'] . ",";
    }
    if (!empty($row['no_of_connection_applied'])) {
        $case_story .= " No Of Connection Applied=" . $row['no_of_connection_applied'] . ",";
    }
    if (!empty($row['purpose_of_connection'])) {
        $case_story .= " Purpose Of Connection=" . $row['purpose_of_connection'] . ",";
    }
    if (!empty($row['total_employees'])) {
        $case_story .= " Total Employees=" . $row['total_employees'] . ",";
    }
    if (!empty($row['no_of_employees_seen'])) {
        $case_story .= " No Of Employees Seen=" . $row['no_of_employees_seen'] . ",";
    }
    if (!empty($row['nature_of_business'])) {
        $case_story .= " Nature Of Business=" . $row['nature_of_business'] . ",";
    }
    if (!empty($row['office_ownership'])) {
        $case_story .= " Office Ownership=" . $row['office_ownership'] . ",";
    }
    if (!empty($row['area_in_sq_ft'])) {
        $case_story .= " Office Area In Sq. Ft.=" . $row['area_in_sq_ft'] . ",";
    }
    if (!empty($row['office_setup'])) {
        $case_story .= " Office Setup=" . $row['office_setup'] . ",";
    }
    if (!empty($row['multi_city_branches'])) {
        $case_story .= " Multi City Branches=" . $row['multi_city_branches'] . ",";
    }
    if (!empty($row['reception_availability'])) {
        $case_story .= " Reception Availability=" . $row['reception_availability'] . ",";
    }
    if (!empty($row['office_name_on_building'])) {
        $case_story .= " Office Name On Building=" . $row['office_name_on_building'] . ",";
    }
    if (!empty($row['business_activity'])) {
        $case_story .= " Business Activity=" . $row['business_activity'] . ",";
    }
    if (!empty($row['color_of_building'])) {
        $case_story .= " Color Of Building=" . $row['color_of_building'] . ",";
    }
    if (!empty($row['go_green'])) {
        $case_story .= " Go Green=" . $row['go_green'] . ",";
    }
    if (!empty($row['email'])) {
        $case_story .= " Email=" . $row['email'] . ",";
    }

    if (!empty($row['scoring'])) {
        $case_story .= " Scoring=" . $row['scoring'] . ",";
    }
} else {
    if (!empty($row['modified_address'])) {
        $case_story .= " Modified Address=" . $row['modified_address'] . ",";
    }
    if (!empty($row['location_of_house'])) {
        $case_story .= " Location Of House=" . $row['location_of_house'] . ",";
    }
    if (!empty($row['negative_area'])) {
        $case_story .= " Negative Area=" . $row['negative_area'] . ",";
    }
    if (!empty($row['type_of_residence'])) {
        $case_story .= " Type Of Residence=" . $row['type_of_residence'] . ",";
    }
    if (!empty($row['name_of_person_met'])) {
        $case_story .= " Name Of Person Met=" . $row['name_of_person_met'] . ",";
    }
    if (!empty($row['relation_with_applicant'])) {
        $case_story .= " Relation With Applicant=" . $row['relation_with_applicant'] . ",";
    }
    if (!empty($row['connection_applied'])) {
        $case_story .= " Connection Applied=" . $row['connection_applied'] . ",";
    }
    if (!empty($row['no_of_connection_applied'])) {
        $case_story .= " No Of Connection Applied=" . $row['no_of_connection_applied'] . ",";
    }
    if (!empty($row['plan_awareness'])) {
        $case_story .= " Plan Awareness=" . $row['plan_awareness'] . ",";
    }
    if (!empty($row['ownership_of_house'])) {
        $case_story .= " Ownership Of House=" . $row['ownership_of_house'] . ",";
    }
    if (!empty($row['tenure_of_stay'])) {
        $case_story .= " Tenure Of Stay=" . $row['tenure_of_stay'] . ",";
    }
    if (!empty($row['employement_type'])) {
        $case_story .= " Employement Type=" . $row['employement_type'] . ",";
    }
    if (!empty($row['employement_type'])) {
        $case_story .= " Employement Type=" . $row['employement_type'] . ",";
    }
    if (!empty($row['email'])) {
        $case_story .= " Email=" . $row['email'] . ",";
    }
    if (!empty($row['alternate_no'])) {
        $case_story .= " Alternate No=" . $row['alternate_no'] . ",";
    }
}
