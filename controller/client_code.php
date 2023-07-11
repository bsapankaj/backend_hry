<?php  require_once '../model/client_code.php';
try {
    $json = file_get_contents('php://input');
    if(isset($json) && !empty($json)) {
        $request = json_decode($json);
        $client_code = new Client_code();
        if(isset($request->action) && $request->action=='add') {
            $client_code->client_code               = $request->client_code;
            $client_code->client_name               = $request->client_name;
            $client_code->client_address            = $request->client_address;
            $client_code->state_id                  = $request->state_id;
            $client_code->gst_no                    = $request->gst_no;
            $client_code->clerkage                  = $request->clerkage;
            $client_code->clerkage_type             = $request->clerkage_type;
            $client_code->gst_on_bill               = $request->gst_on_bill;
            $client_code->contact_person_name       = $request->contact_person_name;
            $client_code->contact_person_mobile_no  = (int)$request->contact_person_mobile_no;
            $client_code->contact_person_email_id   = $request->contact_person_email_id;
            if(!$client_code->check()) {
                $client_code->insert();
            }else{
                throw new Exception('Client already exists!',400);
            }
            http_response_code(200);
            $response = [
                'success' => 1,
                'code' => 200,
                'msg' => 'Client Add Successfully!'
            ];
            echo json_encode($response);
        } else if(isset($request->action) && $request->action=='update') {
            $client_code->client_code               = $request->client_code;
            $client_code->client_name               = $request->client_name;
            $client_code->client_address            = $request->client_address;
            $client_code->state_id                  = $request->state_id;
            $client_code->gst_no                    = $request->gst_no;
            $client_code->clerkage                  = $request->clerkage;
            $client_code->clerkage_type             = $request->clerkage_type;
            $client_code->gst_on_bill               = $request->gst_on_bill;
            $client_code->contact_person_name       = $request->contact_person_name;
            $client_code->contact_person_mobile_no  = (int)$request->contact_person_mobile_no;
            $client_code->contact_person_email_id   = $request->contact_person_email_id;
            $client_code->client_code_id            = $request->client_code_id;
            if(isset($client_code->client_code_id)){
                $client_code->update();
                http_response_code(200);
                $response = [
                    'success' => 1,
                    'code' => http_response_code(200),
                    'msg' => 'Client Update Successfully!'
                ];
                echo json_encode($response);
            }else{
                throw new Exception("client_code_id missing",400);
            }
        } else if(isset($request->action) && $request->action=='delete') {
            if( isset($request->client_code_id) && $request->client_code_id>0 ){
                $client_code->client_code_id = $request->client_code_id;
                $client_code->delete();
                http_response_code(200);
                $response = [
                    'success' => 1,
                    'code' => http_response_code(200),
                    'msg' => 'Client Delete Successfully!'
                ];
                echo json_encode($response);          
            }else{
                throw new Exception("client_code_id missing",400);
            }
        } else if(isset($request->action) && $request->action=='singleRecord') {
            $result = [];
            if(isset($request->client_code_id)){
                $client_code->client_code_id = $request->client_code_id;
                $results = $client_code->getSingleRecord();
                $i = 0;
                foreach($results as $res) {
                    $result[] = [
                        'client_code_id'    => $res['client_code_id'],
                        'client_code'       => $res['client_code'],
                        'client_name'       => $res['client_name'],
                        'client_address'    => $res['client_address'],
                        'state_id'          => $res['state_id'],
                        'gst_no'            => $res['gst_no'],
                        'gst_on_bill'       => $res['gst_on_bill'],
                        'clerkage'          => $res['clerkage'],
                        'clerkage_type'     => $res['clerkage_type'],
                        'person_name'       => $res['contact_person_name'],
                        'mobile_no'         => $res['contact_person_mobile_no'],
                        'email_id'          => $res['contact_person_email_id']
                    ];
                    $i++;
                }
                http_response_code(200);
                $response = [
                    'success' => 1,
                    'code' => 200,
                    'msg' => 'Client Data Fetch Successfully!',
                    'data' => $result
                ];
                echo json_encode($response);

            } else {
                throw new Exception('Data not Exists!',400);
            }            
        } else if(isset($request->action) && $request->action=='get') {
            $result = [];
            $results = $client_code->get($request);
            if(!isset($request->draw)) {
                $request->draw = 1;
                $request->start = 0;
            }
            $i = $request->start;
            foreach($results as $res) {
                ++$i;
                $result[] = [
                    'sno'               => $i,
                    'client_code_id'    => $res['client_code_id'],
                    'client_code'       => $res['client_code'],
                    'client_name'       => $res['client_name'],
                    'client_address'    => $res['client_address'],
                    'state_id'          => $res['state_id'],
                    'gst_no'            => $res['gst_no'],
                    'gst_on_bill'       => $res['gst_on_bill'],
                    'clerkage'          => $res['clerkage'],
                    'clerkage_type'     => $res['clerkage_type'],
                    'person_name'       => $res['contact_person_name'],
                    'mobile_no'         => $res['contact_person_mobile_no'],
                    'email_id'          => $res['contact_person_email_id'],
                    'action'            => "<a class='edit cursor-pointer' data-id='".$res['client_code_id']."'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;<a class='delete cursor-pointer text-danger' data-id='".$res['client_code_id']."'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                ];
            }
            http_response_code(200);
            $response = [
                'draw'              => intval($request->draw),
                'recordsTotal'      => count($results),
                'recordsFiltered'   => $client_code->get_total_count(),
                'success'           => 1,
                'code'              => 200,
                'msg'               => 'Client Data Fetch Successfully!',
                'data'              => $result
            ];
            echo json_encode($response);
        } else {
            throw new Exception('Invalid action type',400);
        }
    } else {
        throw new Exception('Please post request in json format or it can not be blank',400);
    }
}catch(PDOException $e){
    $response =[

        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
      http_response_code(500);
     echo json_encode($response);

}catch(Exception $e) {
    http_response_code($e->getCode());
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    echo json_encode($response);
}

?>