<?php
require_once '../model/contact_us.php';

$contactUs = new contact_Us();                        

$contactUs->conn->beginTransaction();

try {
    if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
        $json = file_get_contents('php://input');
        if (isset($json) && !empty($json)) {
            $request = json_decode($json);
            if (isset($request) && !empty($request)) {
                // ADD UPDATE DELETE FETCH
                if (isset($request->action)) {
                    if ($request->action == 'add') {
                        @session_start();
                        $contactUs->name = $request->name;
                        $contactUs->email_id = $request->email_id;
                        $contactUs->contactMessage = $request->contactMessage;
                        $contactUs->mobile_no = $request->mobile_no;
                        $contactUs->created_by = $_SESSION['hryS_user_id'];
                        print_r($contactUs);exit;

                        if ($contactUs->check() === false && $contactUs->check() === false) {
                            // print_r('abc');exit;
                            $contactUs->insert();
                            $response = [
                                'success' => 1,
                                'code' => 200,
                                'msg' => 'Your User Request Has Been Accepted!'
                            ];
                            $contactUs->conn->commit();
                            http_response_code(200);
                            echo json_encode($response);
                        } else {
                            if($contactUs->check() === true){
                                //  print_r('xyz');exit;
                               throw new Exception('User Request Name Already Exists', 400);
                            }else{
                                // print_r('def');exit;
                                throw new Exception('User Request EmailID Already Exists', 400);
                            }
                        }
                    } else {
                        throw new Exception('Invalid action type', 400);
                    }
                } else {
                    throw new Exception('action key missing in request body', 400);
                }
            } else {
                throw new Exception('Invalid JSON', 400);
            }
        } else {
            throw new Exception('Request body missing', 400);
        }
    } else {
        throw new Exception('Invalid Request METHOD - METHOD must be POST', 400);
    }
} catch (PDOException $e) {
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage()
    ];
    http_response_code(500);
    echo json_encode($response);
} catch (Exception $e) {
    $response = [
        'success' => 0,
        'code' => $e->getCode(),
        'msg' => $e->getMessage(),
    ];
    // http_response_code($e->getCode());
    echo json_encode($response);
}