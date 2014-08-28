<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'Api.php';
require_once 'DBUtil.php';

if($_GET['api']){
    $params = (new ReflectionMethod('Api', $_GET['api']))->getParameters();
    $call_pars = [];
    $rawPost = file_get_contents("php://input");    
    $values = (($_SERVER['REQUEST_METHOD'] === 'POST') ? (empty($rawPost) ? [] : json_decode($rawPost, TRUE)) : $_GET);
    try{
        foreach($params as $par){        
            $key = $par->getName();

            if (isset($values[$key])) {
                $call_pars[] = $values[$key];
            } elseif ($par->isDefaultValueAvailable()) {
                $call_pars[] = $par->getDefaultValue();
            } else {
                throw new Exception('params missing', 1001);
            }        
        }
        $result = call_user_func_array([new Api(), $_GET['api']], $call_pars);
        echo json_encode(['result' => $result], JSON_UNESCAPED_UNICODE);
    }  catch (Exception $e){
        http_response_code(404);
        echo json_encode(['code' => $e->getCode(), 'msg' => $e->getMessage()]);
        
    }
}
