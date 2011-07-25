<?php
header("Content-Type:text/html; charset=UTF-8");

/**
 * 初始化加载类和路径
 */
function init(){    
    require_once 'core/Enum.php';   
    require_once 'core/common.php';     
    /**
     * 加载全局变量文件
     */
    require_once 'Gc.php'; 
    //定义异常报错信息
    if (Gc::$dev_debug_on){
        if(defined('E_DEPRECATED')) error_reporting(E_ALL ^ E_DEPRECATED);
        else error_reporting(E_ALL);
    }else{
        error_reporting(0);
    }

    $root_core="core";
    $nav_core_path=Gc::$nav_framework_path.$root_core.DIRECTORY_SEPARATOR;
    $core_util="util";
    $include_paths=array(
            $nav_core_path,
            $nav_core_path.$core_util,
            $nav_core_path.$core_util.DIRECTORY_SEPARATOR."common",
    );
    set_include_path(get_include_path().PATH_SEPARATOR.join(PATH_SEPARATOR, $include_paths));
    $dirs_root=UtilFileSystem::getAllDirsInDriectory($nav_core_path);
    $include_paths=$dirs_root;    
    set_include_path(get_include_path().PATH_SEPARATOR.join(PATH_SEPARATOR, $include_paths));
    
    $path_remoteobject=Gc::$nav_framework_path."remoteobject".DIRECTORY_SEPARATOR;
    set_include_path(get_include_path().PATH_SEPARATOR.$path_remoteobject.PATH_SEPARATOR);   
}

/**
 * 相当于__autoload加载方式<br/>
 * 自动加载指定的类对象
 * @param string $class_name 类名
 */
function class_autoloader($class_name) {
    class_exists($class_name) ||require($class_name.".php");
}

spl_autoload_register("class_autoloader");

init();
?>
