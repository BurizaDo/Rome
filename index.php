<?php
header('Content-Type: text/html; charset=utf-8');
require_once 'Api.php';
require_once 'DBUtil.php';
if($_GET['api']){
    $params = (new ReflectionMethod('Api', $_GET['api']))->getParameters();
    $call_pars = [];
    foreach($params as $par){
        
        $key = $par->getName();
        if (isset($_GET[$key])) {
            $call_pars[] = $_GET[$key];
        } elseif ($par->isDefaultValueAvailable()) {
            $call_pars[] = $par->getDefaultValue();
        } else {
            throw new Exception('params missing');
        }        
    }
    $result = call_user_func_array([new Api(), $_GET['api']], $call_pars);
    echo json_encode(['result' => $result], JSON_UNESCAPED_UNICODE);
}
