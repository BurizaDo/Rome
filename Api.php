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
    
    public function updateUserProfile($userId, $name, $signature, $avatar, $images){
        return DBUtil::updateUserProfile($userId, $name, $signature, $avatar, $images);
    }
    
    public function getUser($userIds){
        return array_values(DBUtil::getUser($userIds));
    }
}