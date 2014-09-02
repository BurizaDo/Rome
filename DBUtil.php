<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class DBUtil{
    private static function connectDB(){
        $con = mysql_connect('127.0.0.1', 'root', 'root');
        mysql_query("SET NAMES 'UTF8'", $con);          
        mysql_select_db('rome', $con);
        return $con;
    }
    
    public static function insertMessage($userId, $destination, $start_time, $end_time, $message){
        self::connectDB();
        $result = mysql_query("insert into message (userId, destination, start_time, end_time, message) values ('{$userId}', '{$destination}', '{$start_time}', '{$end_time}', '{$message}')");
        mysql_close();
        return $result != FALSE ? TRUE : FALSE;
    }
    
    public static function getMessage($start = 0, $size = 30){
        $con = self::connectDB();
        $result = mysql_query("SELECT * FROM message order by id desc limit {$start}, {$size}", $con);
        $msgs = [];
       
        while($row = mysql_fetch_array($result)){
            $r = [];
            $r['userId'] = $row['userId'];
            $r['destination'] = $row['destination'];
            $r['start_time'] = $row['start_time'];
            $r['end_time'] = $row['end_time'];
            $r['message'] = $row['message'];
            $msgs[] = $r;
        }
        mysql_close();        
        return $msgs;
    }
    
    public static function getMessageById($messageId){
        $con = self::connectDB();
        $result = mysql_query("SELECT * FROM message where id={$messageId}", $con);
       
        while($row = mysql_fetch_array($result)){

            $r['userId'] = $row['userId'];
            $r['destination'] = $row['destination'];
            $r['start_time'] = $row['start_time'];
            $r['end_time'] = $row['end_time'];
            $r['message'] = $row['message'];
            $msgs[] = $r;
        }
        mysql_close();        
        return $msgs;        
    }
    
    public static function deleteMessageById($messageId){
        $con = self::connectDB();
        mysql_query("DELETE FROM message where id={$messageId}", $con);
        mysql_close();
        return mysql_errno() == 0;
    }
    
    public static function getUserMessage($userId, $start = 0, $size = 30){
        $con = self::connectDB();
        $result = mysql_query("SELECT * FROM message where userId = '{$userId}' order by id desc limit {$start}, {$size}", $con);
        $msgs = [];
       
        while($row = mysql_fetch_array($result)){
            $r = [];
            $r['id'] = $row['id'];
            $r['userId'] = $row['userId'];
            $r['destination'] = $row['destination'];
            $r['start_time'] = $row['start_time'];
            $r['end_time'] = $row['end_time'];
            $r['message'] = $row['message'];
            $msgs[] = $r;
        }
        mysql_close();        
        return $msgs;        
    }
    
    public static function updateUserProfile($userId, $name, $age, $gender, $signature, $avatar, $images){
        $con = self::connectDB();
        $result = mysql_query("select * from user where userId='{$userId}'");
        $exist = mysql_num_rows($result) > 0;
        $ret = TRUE;
        if($exist){
            $ret = mysql_query("update user set name='{$name}',signature='{$signature}',avatar='{$avatar}',images='{$images}', age='{$age}', gender='{$gender}' where userId='{$userId}'");
        }else{
            $ret = mysql_query("insert into user (userId,name,age,gender,signature,avatar,images) VALUES('{$userId}','{$name}','{$age}','{$gender}', '{$signature}','{$avatar}','{$images}')");
        }
        mysql_close($con);
        return $ret;
    }
    
    public static function getUser($userId){
        $con = self::connectDB();
        $ids = explode(',', $userId);
        $query = '';
        foreach($ids as $id){
           $query = $query . "userId='{$id}' or ";
        }
        $query = trim($query, ' or ');
        $result = mysql_query("SELECT * FROM user where " . $query, $con);
        $users = [];
        while($row = mysql_fetch_array($result)){
            $r = [];
            $r['userId'] = $row['userId'];
            $r['name'] = $row['name'];
            $r['age'] = $row['age'];
            $r['signature'] = $row['signature'];
            $r['avatar'] = $row['avatar'];
            $r['images'] = $row['images'];
            $r['gender'] = $row['gender'];
            $users[$row['userId']]= $r;
        }
        mysql_close();
        return $users;
    }
}