<?php
require_once 'DBUtil.php';
/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Api{
    
    public function addMessage($userId, $destination, $start_time, $end_time, $message){
        return DBUtil::insertMessage($userId, $destination, $start_time, $end_time, $message);
    }
    
    public function getMessage($start = 0, $size = 30){
        $msgs = DBUtil::getMessage($start, $size);
        $users = [];
        foreach($msgs as $msg){
            $users[$msg['userId']] = $msg['userId'];
        }
        
        $userString = implode(',', array_values($users));
        $userAry = DBUtil::getUser($userString);
        foreach($msgs as $key => $msg){
            $msg['user'] = $userAry[$msg['userId']];
            unset($msg['userId']);
            $msgs[$key] = $msg;
        }
        return $msgs;
    }
    
    public function getUserMessage($userId, $start = 0, $size = 30){
        $msgs = DBUtil::getUserMessage($userId, $start, $size);
        $users = [];
        foreach($msgs as $msg){
            $users[$msg['userId']] = $msg['userId'];
        }
        
        $userString = implode(',', array_values($users));
        $userAry = DBUtil::getUser($userString);
        foreach($msgs as $key => $msg){
            $msg['user'] = $userAry[$msg['userId']];
            unset($msg['userId']);
            $msgs[$key] = $msg;
        }
        return $msgs;        
    }
    
    public function updateUserProfile($userId, $name, $age, $gender, $signature, $avatar, $images){
        return DBUtil::updateUserProfile($userId, $name, $age, $gender, $signature, $avatar, $images);
    }
    
    public function getUser($userIds){
        $ret = array_values(DBUtil::getUser($userIds));
        if(count($ret) == 0){
            throw new \Exception('no user found', 1002);
        }
        return $ret;
    }
    
    public function deleteMessage($userId, $messageId){
        $messages = DBUtil::getMessageById($messageId);
        if($messages){
            $message = $messages[0];
            if($message['userId'] == $userId){
                return DBUtil::deleteMessageById($messageId);
            }
        }
        return FALSE;
    }
    
    public function getMarkedCount($messageId){
        return DBUtil::getMarkUserCount($messageId);
    }
    
    public function markAsBeenTo($userId, $messageId, $hasBeenTo = TRUE){
        $user = DBUtil::getUser($userId);
        if(!$user) {
            throw new \Exception ('no user found', 1002);
        }
        $message = DBUtil::getMessageById($messageId)[0];
        if(!$message){
            throw new \Exception('no message found', 1003);
        }
        return DBUtil::markAsBeenTo($userId, $message, $hasBeenTo);
    }
    
    public function register($name, $password){
        $user = DBUtil::getUser(md5($name));
        if($user && !empty($user)){
            throw new Exception('已注册', 1101);
        }
        return DBUtil::addUser($name, $password);
    }
    
    public function login($name, $password){
        $users = DBUtil::getUser(md5($name));
        if(!$users || empty($users)){
            throw new Exception('用户不存在', 1102);
        }
        $user = array_values($users)[0];
        if($user['password'] != md5($password)){
            throw new Exception('密码错误', 1103);
        }
        unset($user['password']);
        return $user;
    }
}