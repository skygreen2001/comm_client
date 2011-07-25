<?php

/**
 +--------------------------------<br/>
 * 工具类：加载Javascript库和发送请求<br/>
 * JavaScript loader, which by default delivers each library with ****gzip**** compression.<br/>
 * These days every browser supports gzip compression for faster loading of any object.<br/>
 * built-in support for ****jQuery, mootoos, prototype and script.aculo.us*****.<br/>
 +--------------------------------
 * @category betterlife
 * @package util.view
 * @author skygreen
 */
class UtilJavascript extends Util 
{
    /**
     * 动态加载应用指定的Js文件。
     * 可通过分组标识动态加载Ajax Javascript Framework库
     * @param string $jsFile：相对网站的根目录的Javascript文件名相对路径 
     * @param bool $isGzip 是否使用Gzip进行压缩。
     */
    public static function loadJs($jsFile,$isGzip=false) 
    {
        UtilAjax::loadJs($jsFile, $isGzip);
    }    
    
    //<editor-fold defaultstate="collapsed" desc="Ajax部分">
    /**
     * 加载默认的Ajax框架
     */
    public static function loadDefaultAjax()
    {        
        return UtilAjax::loadDefaultAjax();
    }
    
    /**
     * 发送Ajax请求的语句
     * @param string $url 通信的Url地址。
     * @param array $dataArray 传送的数据。
     * @param array $method HTTP的标准协议动词【get|post|put|delete】。
     * @param enum $response_type 返回的数据类型
     * @param string $callback Javascript调用的回执方法名。
     * @return 发送Ajax请求的语句
     */
    public static function ajaxRequstStatement($url,$dataArray,$method,$response_type=EnumResponseType::XML,$callback=null){
        $result=self::loadDefaultAjax(); 
        $loadJsLibrary=UtilAjax::name().ucfirst(UtilAjax::$ajax_fw_name_default);
        $resultAjaxRequestStatement=call_user_func_array("$loadJsLibrary::ajaxRequstStatement",array($url,$dataArray,$method,$response_type,$callback)); 
        return $result.$resultAjaxRequestStatement;
    }   
    //</editor-fold>     
}
?>
