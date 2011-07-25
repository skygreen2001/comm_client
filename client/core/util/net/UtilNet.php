<?php
  
  /**
  +---------------------------------<br/>
  * 工具类：网络<br/>
  +---------------------------------
  * @category betterlife
  * @package util.net
  * @author skygreen
  */
class UtilNet extends Util 
{
    /**
     * 获取网站的根路径
    *  @param string $with_file 如指定文件名。
     * @return 网站的根路径
     */
    public static function urlbase(){
        $with_file=$_SERVER["SCRIPT_FILENAME"];
        $file_sub_dir=dirname($with_file).DIRECTORY_SEPARATOR;
        $file_sub_dir=str_replace("/", DIRECTORY_SEPARATOR, $file_sub_dir);
        $file_sub_dir=str_replace(Gc::$nav_root_path, "", $file_sub_dir);
        $file_sub_dir=str_replace(DIRECTORY_SEPARATOR, "/", $file_sub_dir);
        $url_base=Gc::$url_base;
        $url_base=str_replace($file_sub_dir, "", $url_base);
        return $url_base;
    }
    
} 
  
?>
