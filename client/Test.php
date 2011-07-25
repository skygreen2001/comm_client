<?php
require_once("init.php");   
/**
  +---------------------------------<br/>
 * 模拟客户端发送请求到第三方<br/>
  +---------------------------------
 * @category betterlife
 * @package data.exchange.enjoyoung.client
 * @author skygreen
 */
class Test 
{
    public static $id=25;
    /**
     * 会员测试驱动数据
     * @param type $user 
     */
    public static function user_data($user)
    {  
        $user->name="skygreen";      
        $user->departmentId=1;          
        $user->password="13888888888";        
    }
    
    /**
    * 发送Get请求
    * @return type 
    */
    public static function user_get()
    {
        $user=new UserRO();   
        self::user_data($user);
        $result=$user->get();
        echo $result;        
    }     
    
    /**
    * 发送Post请求
    * @return type 
    */
    public static function user_post()
    {
        $user=new UserRO();   
        self::user_data($user);
        $result=$user->post();
        echo $result;        
    }    

    /**
    * 发送Put请求
    */
    public static function user_put()
    {
        $user=new UserRO(); 
        $user->id=self::$id; 
        self::user_data($user); 
        $result=$user->put();
        echo $result;
    }

    /**
    * 发送Delete请求
    */
    public static function user_delete()
    {
        $user=new UserRO(); 
        $user->id=self::$id;  
        $result=$user->delete();
        echo $result;
    }
}

Test::user_post(); 
Test::user_put();
Test::user_delete(); 
Test::user_get();
?>
