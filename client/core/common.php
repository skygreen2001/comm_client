<?php
/**
 * 加载指定模块下的模块文件
 * @var string $moduleName 模块名
 * @var string $module_dir 模块目录
 * @var array $excludes 排除在外要加载的子文件夹
 */
function load_module($moduleName,$module_dir,$excludes=null) {
    $require_dirs=UtilFileSystem::getSubDirsInDirectory($module_dir);
    ///需要包含本目录下的文件。
    
    $tmps=UtilFileSystem::getFilesInDirectory($module_dir);
    foreach ($tmps as $tmp) {
        Initializer::$moduleFiles[$moduleName][basename($tmp,".php")]=$tmp;
    }
    
    if (!empty($excludes)) {
        foreach ($excludes as $exclude) {
            if (array_key_exists($exclude, $require_dirs)) {
                unset ($require_dirs[$exclude]);
            }
        }
    }
    foreach ($require_dirs as $dir) {
        $tmps=UtilFileSystem::getAllFilesInDirectory($dir);
        foreach ($tmps as $tmp) {
            Initializer::$moduleFiles[$moduleName][basename($tmp,".php")]=$tmp;
        }
    }
}

/**
 * 获取对象实体|对象名称的反射类。
 * @param mixed $object 对象实体|对象名称
 * @return 对象实体|对象名称的反射类
 */
function object_reflection($object){
    $class=null;
    if (is_object($object)) {
        $class=new ReflectionClass($object);
    }else{
        if (is_string($object)){
            if (class_exists($object)) {
                $class=new ReflectionClass($object);
            }            
        }
    }
    return $class;
}

function ping_url($url,$data=null){
    $url = parse_url($url);
    if (array_key_exists('query',$url)){
        parse_str($url['query'],$out);
    }
    if (($data!=null)&&(is_array($data))){
        $out=array_merge($out,$data);
    }
    if (isset($out)){
        $url['query'] = '?'.http_build_query($out);
    }
    $host=gethostbyname($url['host']);
    $fp = fsockopen($host, isset($url['port'])?$url['port']:80, $errno, $errstr, 2);
    if (!$fp) {
        return false;
    } else {
        if (array_key_exists('query',$url)){
            $fullUrl="{$url['path']}{$url['query']}";
        }else{
            $fullUrl="{$url['path']}";
        }
        $out = "GET $fullUrl HTTP/1.1\r\n";
        $out .= "Host: {$url['host']}\r\n";
        $out .= "Connection: Close\r\n\r\n";
        fwrite($fp, $out);
        $content="";
        while (!feof($fp)) {
            $content.=fgets($fp, 128);
        }
        return $content;
    }
}

/**
 * 查看字符串里是否包含指定字符串
 * @param mixed $subject
 * @param mixed $needle
 */
function contain($subject,$needle) {
    if (strpos($subject,$needle)!== false) {
        return true;
    }else {
        return false;
    }
}

/**
 * 需要的字符是否在目标字符串的开始
 * @param string $haystack 目标字符串
 * @param string $needle 需要的字符
 * @param bool $strict 是否严格区分字母大小写
 * @return bool true:是，false:否。
 */
function startWith($haystack, $needle,$strict=true) {
    if (!$strict){
        $haystack=strtoupper($haystack);
        $needle=strtoupper($needle);
    }
    return strpos($haystack, $needle) === 0;
} 

/**
 * 需要的字符是否在目标字符串的结尾
 * @param string $haystack 目标字符串
 * @param string $needle 需要的字符
 * @param bool $strict 是否严格区分字母大小写
 * @return bool true:是，false:否。
 */
function endWith($haystack, $needle,$strict=true){
    if ($strict){
        return ereg($needle."$", $haystack);
    }else{
        return eregi($needle."$", $haystack);        
    }
}

/**
 * 返回指定类所有的常量
 * @param mixed $object 对象实体|对象名称
 * @return mixed 常量键值对数组
 */
function getClassConsts($object) {
    $class=object_reflection($object);
    if (isset ($class)){
        $consts = $class->getConstants();
//            foreach ($consts as $constant => $value) {
//                echo "$constant = $value\n";
//            }
        return $consts;
    }
    return null;
}
    
/**
* 返回指定类指定常量值的名称。
* @param mixed $object 对象实体|对象名称
* @param string $propertyValue
* @param string $prefix 指定前缀或者后缀的名称
* @param bool $isprefix 是否前缀，true:前缀,false:后缀
* @return string 指定类指定常量值的名称
*/
function getClassConstNameByValue($object,$propertyValue,$pre1sufix="",$isprefix=true){
    $consts=getClassConsts($object);        
    return UtilArray::array_search($consts,$propertyValue,$pre1sufix,$isprefix);
}

/**
 +--------------------------------------------------<br/>
 * 功能参看PHP 5.3手册get_called_class方法<br/>
 * 以下方法是支持低于PHP 5.3版本的相同功能<br/>
 * 说明：<br/>
 *     该函数与引用经过改写<br/>
 *     主要是因为是应用中发现如果出现了换行，将无法找到静态引用的类<br/>
 *     主要针对以下情况：<br/>
 *     User::get(array("name"=>$user->getName(),<br/>
 *                   "password"=>md5($user->getPassword())));<br/>
 *     因为出现了换行 debug_backtrace的行号显示了下一行；就找不到上一行出现的类名了<br/>
 *     改写就是先找到debug_backtrace的行号，再向上找到第一个出现::的行，再进行匹配查找静态方法引用的类<br/>
 * line:progman at centrum dot sk<br/>
 * 10-Mar-2009 08:49<br/>
 * @see http://php.net/manual/en/function.get-called-class.php
 +--------------------------------------------------<br/>
 */
if (!function_exists('get_called_class')) {
    function get_called_class() {
        $bt = debug_backtrace();
        $lines = file($bt[1]['file']);
        $match_line_start_pos=$bt[1]['line']-1;
        if (contain($lines[$match_line_start_pos],"::")) {
           $match_line_start_pos=$match_line_start_pos-1;
          while (contain($lines[$match_line_start_pos],"::")){
            $match_line_start_pos=$match_line_start_pos-1;
          }  
        }
        preg_match('/([a-zA-Z0-9\_]+)::'.$bt[1]['function'].'/',
                $lines[$match_line_start_pos],
                $matches);
        return $matches[1];
    }
}

/**
* 写字符串到文件。
*/
if(!function_exists('file_put_contents')){
    define('FILE_APPEND', 1);
    function file_put_contents($n, $d, $flag = false) {
        $mode = ($flag == FILE_APPEND || strtoupper($flag) == 'FILE_APPEND') ? 'a' : 'wb';
        $f = @fopen($n, $mode);
        if ($f === false) {
            return 0;
        } else {
            if (is_array($d)) $d = implode($d);
            flock($f, LOCK_EX);
            $bytes_written = fwrite($f, $d);
            flock($f, LOCK_UN);
            fclose($f);
            return $bytes_written;
        }
    }
}

/**
* 该函数只对Utf8编码的值进行Json编码。<br/>
* 返回值的JSON编码呈现。 <br/>
* @param mixed $value Utf8编码的值，除resource以外的类型，最常用的是array数组。
* @return 值的JSON编码呈现
*/
if(!function_exists('json_encode')){
    function json_encode($value) {
        switch(gettype($value)) {
        case 'double':
        case 'integer':
            return $value>0?$value:'"'.$value.'"';
        case 'boolean':
            return $value?'true':'false';
        case 'string':
            return '"'.str_replace(
                array("\n","\b","\t","\f","\r"),
                array('\n','\b','\t','\f','\r'),
                addslashes($value)
            ).'"';
        case 'NULL':
            return 'null';
        case 'object':
            return '"Object '.get_class($value).'"';
        case 'array':
            if (isVector($value)){
                if(!$value){
                    return $value;
                }
                foreach($value as $v){
                    $result[] = json_encode($v);
                }
                return '['.implode(',',$result).']';
            }else {
                $result = '{';
                foreach ($value as $k=>$v) {
                    if ($result != '{') $result .= ',';
                    $result .= json_encode($k).':'.json_encode($v);
                }
                return $result.'}';
            }
        default:
            return '"'.addslashes($value).'"';
        }
    }
}

/**
* 将Json编码的字符串转换成对象或者数组。
* @param string $json Json编码的字符串。
* @param mixed $assoc 当为true的时候,则转换为数组。
* @return mixed 对象或者数组
*/
if(!function_exists('json_decode')){
    function json_decode($json,$assoc){
        include_once(dirname(__FILE__).'/lib/json.php');
        $o = new Services_JSON();
        return $o->decode($json,$assoc);
    }
}


?>
