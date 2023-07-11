<?php  require_once '../model/file_master.php';
try {
    if(isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD']=='POST') {
        $json = file_get_contents('php://input');
        if(isset($json) && !empty($json)) {
            $request = json_decode($json);
            if(isset($request) && !empty($request)) {
                @session_start();
                $user_id = $_SESSION['hryS_user_id'];
                if(isset($request->action)) {
                    $file_master = new File_master();
                    if($request->action=='add') {
                        $file_master->file_no = $request->file_no;
                        $file_master->client_code_id = $request->client_code_id;
                        $file_master->file_title = $request->file_title;
                        $file_master->file_description = $request->file_description;
                        $file_master->created_by = $user_id;
                        if($file_master->check() == 0) {
                            $file_master->insert();
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'File successfully added!'
                            ];

                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('File Already Exists',400);
                        }
                    } else if($request->action=='update') {
                        $file_master->file_id = $request->file_id;
                        $file_master->file_no = $request->file_no;
                        $file_master->client_code_id = $request->client_code_id;
                        $file_master->file_title = $request->file_title;
                        $file_master->file_description = $request->file_description;
                        $file_master->updated_by = $user_id;
                        if($file_master->check() == 0) {
                            $file_master->update();
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'File successfully updated!'
                            ];

                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            throw new Exception('File Already Exists',400);
                        }
                    } else if($request->action=='delete') {
                        $file_master->file_id = $request->file_id;
                        $file_master->delete();
                        $response = [
                            'success' => 1,
                            'code' => 200,
                            'msg' => 'File Successfully deleted!',
                        ];

                        http_response_code(200);
                        echo json_encode($response);
                    } else if($request->action=='get') {
                        $result = [];
                        if(isset($request->file_id) && $request->file_id>0) {
                            $file_master->file_id = $request->file_id;
                            $results = $file_master->get();
                        }else if(isset($request->client_id) && $request->client_id>0) {
                            $file_master->client_code_id = $request->client_id;
                            $results = $file_master->get();
                        }else{
                            $results = $file_master->get($request);
                        }
                        if(isset($request->start)) {
                            $i = $request->start;
                        } else {
                            $i=0;
                            $request->draw=0;
                        }
                        foreach($results as $res) {
                           
                            ++$i;
                            $result [] = [
                                's_no'              => $i,
                                'file_id'           => $res['file_id'],
                                'file_no'           => $res['file_no'],
                                'client_code'       => $res['client_code'],
                                'client_code_id'    => $res['client_code_id'],
                                'file_title'        => $res['file_title'],
                                'file_description'  => $res['file_description'],
                                'is_active'         => $res['is_active'],
                                "action"            => "<a class='edit cursor-pointer' data-id='".$res['file_id']."'><i class='fa fa-pencil-square-o' aria-hidden='true'></i></a>&nbsp;&nbsp;&nbsp;
                                <a class='delete cursor-pointer text-danger' data-id='".$res['file_id']."'><i class='fa fa-trash' aria-hidden='true'></i></a>"
                            ];
                        }
                        $response = [
                            'draw'              => intval($request->draw),
                            'recordsTotal'      => count($results),
                            'recordsFiltered'   => $file_master->get_total_count(),
                            'success'           => 1,
                            'code'              => 200,
                            'msg'               => 'File Fetch Successfully!',
                            'data'              => $result,
                        ];
                        http_response_code(200);
                        echo json_encode($response);
                    } else {
                        throw new Exception('Invalid action type',400);
                    }
                } else {
                    throw new Exception('action key missing in request body',400);
                }
                
            } else {
                throw new Exception('Invalid JSON',400);
            }
        } else {
            throw new Exception('Request body missing',400);
        }
    } else {
        throw new Exception('Invalid Request METHOD - METHOD must be POST',400);
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
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage(),
    ];
    // http_response_code($e->getCode());
    echo json_encode($response);
}
?>