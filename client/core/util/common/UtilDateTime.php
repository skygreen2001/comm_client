<?php
/**
 +---------------------------------<br/>
 * 功能:处理文件目录相关的事宜方法。<br/>
 +---------------------------------
 * @category betterlife
 * @package util.common
 * @author skygreen
 */
class UtilDateTime extends Util {    
    /**
     * 标准日期时间格式：年-月-日 时:分:秒
     */
    const TIMEFORMAT_YMDHIS="Y-m-d H:i:s";
    /**
     * 标准日期时间格式：年-月-日 时:分:秒
     */
    const TIMEFORMAT_YMD="Y-m-d";
    /**
     * 设置当前为中国时区的时间。
     */
    public static function ChinaTime(){
       date_default_timezone_set('Asia/Shanghai');
    }
    
    /**
     +----------------------------------------------------------<br/>
     * 获取现在的时间显示<br/>
     * 格式：年-月-日 小时:分钟:秒<br/> 
     +----------------------------------------------------------<br/>
     */
    public static function now() {
        date_default_timezone_set('Asia/Shanghai');
        return date(self::TIMEFORMAT_YMDHIS);
    }

    /**
     * 将timestamp转换成DataTime时间格式。
     * @param int $timestamp 时间戳
     * @return string 日期时间格式年-月-日 时:分:秒
     */
    public static function timestampToDateTime($timestamp,$format=self::TIMEFORMAT_YMDHIS){
        return date($format, $timestamp);
    }
    
    /**
     * 将日期时间格式年-月-日 时:分:秒转成时间戳
     * @param string $str 日期时间格式年-月-日 时:分:秒
     * @return 时间戳
     */
    public static function dateToTimestamp($str=''){        
        @list($date, $time) = explode(' ', $str); 
        list($year, $month, $day) = explode('-', $date); 
        if(empty($time)){
            $timestamp = mktime(0, 0, 0, $month, $day, $year); 
        }else{
            list($hour, $minute, $second) = explode(':', $time); 
            $timestamp = mktime($hour, $minute, $second, $month, $day, $year);
        }
        return $timestamp;
    }    
}
?>
