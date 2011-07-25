<?php

class EnumJsFramework extends Enum{
    const JS_FW_YUI="yui"; 
}

/**
 +---------------------------------<br/>
 * 所有Javascript Ajax 框架的工具类的父类<br/>
 +---------------------------------
 * @category betterlife
 * @package util.view.ajax
 * @author skygreen
 */
class UtilAjax extends Util
{
    //<editor-fold defaultstate="collapsed" desc="定义部分">
    /**
     * 对JS进行Gzip操作的路径
     * @var string 
     */
    protected static $JS_GZIP="common/js/gzip.php?js=";
    /**
     * JS框架名称键名称
     * @var string 
     */
    protected static $JS_FLAG_GROUP="g";
    /**
     * JS框架版本键名称
     * @var type 
     */
    protected static $JS_FLAG_VERSION="v"; 
    /**
     * 默认使用的Ajax框架名称
     * @var enum 
     */
    public static $ajax_fw_name_default=EnumJsFramework::JS_FW_YUI;
    /**
     * 默认使用的Ajax框架版本
     * @var string 
     */
    public static $ajax_fw_version_default="3.3.0";
    /**
     * 推荐的Ajax框架和可使用的版本。<br/>
     * 选用Ajax框架和可使用的版本可参考该列表
     * @link http://code.google.com/intl/zh-CN/apis/libraries/devguide.html
     * @var array 
     */
    public static $ajax_fw_list=array(
        "yui"=>"3.3.0",//YUI可使用3.3.0以上版本。
    );
    
    /**
     * 是否采用google library api加载Ajax库。<br/>
     * @link http://code.google.com/intl/zh-CN/apis/libraries/devguide.html
     * @var bool
     */
    public static $IsGoogleApi=false;
    /**
     * 加载过的Js文件。
     * $value Js文件名
     * @var array 
     */
    public static $JsLoaded=array();    
    /**
     * 回调函数的内容会显示在页面上，当一次调用多个Ajax请求时，因此只需要写一次<html><body>。<br/>
     * 该状态记录是否已经显示出过<html><body>
     * @var bool 
     */
    public static $IsHtmlBody=false;
    //</editor-fold>     
    /**
     * 初始化方能加载枚举类型。
     */
    public static function init(){}

    /**
     * @return string 当前类名
     */
    public static function name(){
        return __CLASS__;
    }
    
    /**
     * 加载默认的Ajax框架
     */
    public static function loadDefaultAjax(){
        $version=UtilAjax::$ajax_fw_version_default;
        $loadJsLibrary=UtilAjax::name().ucfirst(UtilAjax::$ajax_fw_name_default);
        $result=call_user_func("$loadJsLibrary::load",$version); 
        return $result;
    }
        
    /**
     * 动态加载应用指定的Js文件。
     * 可通过分组标识动态加载Ajax Javascript Framework库
     * @param string $jsFile：相对网站的根目录的Javascript文件名相对路径 
     * @param bool $isGzip 是否使用Gzip进行压缩。
     * @param string $jsFlag Ajax Javascript Framework 标识
     * @param string $version javascript框架的版本号
     */
    public static function loadJs($jsFile,$isGzip=false,$jsFlag=null,$version="") 
    {
        echo self::loadJsSentence($jsFile,$isGzip,$jsFlag,$version);
    }   
    
    /**
     * 动态加载应用指定的Js文件的语句。
     * 可通过分组标识动态加载Ajax Javascript Framework库
     * @param string $jsFile：相对网站的根目录的Javascript文件名相对路径 
     * @param bool $isGzip 是否使用Gzip进行压缩。
     * @param string $jsFlag Ajax Javascript Framework 标识
     * @param string $version javascript框架的版本号
     */   
    public static function loadJsSentence($jsFile,$isGzip=false,$jsFlag=null,$version="")
    {
        $result="";
        if (isset($jsFile)){
            $url_base=UtilNet::urlbase(); 
            if (in_array($jsFile, self::$JsLoaded)){
                return ;
            }                
            if (startWith($jsFile, "http")){
                $result= "\n<script type=\"text/javascript\" src=\"".$jsFile."\"></script>\n";  
            }else{
                $result= "\n<script type=\"text/javascript\" src=\"".$url_base.$jsFile."\"></script>\n";  
            }  
            self::$JsLoaded[]=$jsFile;
        }
        return $result;
    } 
}

?>
