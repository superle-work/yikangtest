<?php
/**
 * 提供日期处理工具服务
 * @name UtilDate
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
$utilDate = new UtilDate();
class UtilDate{
	
	/**
	 * 获取当前时间之前连续n天的列表
	 * @param int $count
	 * @param string $sort 排序
	 * @param int $isCludeToday 是否包括当天
	 * @return multitype:string
	 */
	function getSerialDate($count,$sort = 'asc',$isCludeToday = 1){
		$dateList = array();
		if($sort == 'asc'){
			for($i = 0 ;$i < $count ;$i++){
				$j = $count - $i - 1;
				if($isCludeToday){
					$dateList[$i] = date('Y-m-d', strtotime("-{$j} days"));//包括当天
				}else{//不包括当天
					$t = $j +1;
					$dateList[$i] = date('Y-m-d', strtotime("-{$t} days"));//包括当天
				}
			}
		}else{
			for($i = 0 ;$i < $count ;$i++){
				if($isCludeToday){
					$t = $i +1;
					$dateList[$i] = date('Y-m-d', strtotime("-{$t} days"));//包括当天
				}else{//不包括当天
					
				}
			}
		}
		return $dateList;
	}
	
	/**
	 * 用于将日期格式转换为星期格式 如：2013-1-30 11:11:11 转换为 1.30 周三 11:11
	 * @param 2013-1-30 11:11:11
	 * @return 1.30 周三 11:11
	 */
	function getFormatDate($time){
		//定义星期数组
		$arr=array("周日","周一","周二","周三","周四","周五","周六");
		$week = $arr[date("w",strtotime($time))];
  		//通过空格截取年月与时间"2013-1-30 11:11:11";
 		$date = explode(" ", $time);
  		//替换日期格式，将年月日按"-"分割
 		$dateArr = explode("-", $date[0]);
 		$today = $dateArr[1].'.'.$dateArr[2];
 		//替换11:11:11为11:11
 		$second = explode(":", $date[1]);
 		$formatDate = array(
 				'today' =>$today,
 				'week' =>$week,
 				'time' =>$second[0].":".$second[1],
 				);
		return $formatDate;
	}
	
	/**
	 * 将2013-1-2 10:00:00转换为2013年1月1日 10:00:00
	 * @author jianfang
	 */
	public function explanDate($dateTime){
		$time=explode(" ", $dateTime);
		$dateArr = explode("-", $time[0]);
		return $dateArr[0]."年".$dateArr[1]."月".$dateArr[2]."日"." ".$time[1];
	}
	
	/**
	 * 获取2个日期之间的所有日期
	 * @param string $startData 2015-08-20
	 * @param string $endData 2015-08-24
	 * @return array ["2015-08-20", "2015-08-21", "2015-08-22", "2015-08-23", "2015-08-24"]
	 */
	public function getDataList($startData,$endData){
		$endTime = strtotime($endData);
		$startTime = strtotime($startData);
		$day = ($endTime - $startTime) / (3600 * 24);
		$dateList = array();
		for($i = 0;$i <= $day;$i++){
			$dateList[] = date('Y-m-d',$startTime + $i * 3600*24);
		}
		return $dateList;
	}
	
	/**
	 * 获取日、星期、月、季日期区间
	 * @param string $type day lastWeek currentWeeek lastMonth currentMonth season
	 * @return array array('from'=>'','to'=>'')
	 */
	public static function getDateArea($type = "day"){
	    switch ($type){
	        case "day"://当天
	            $date['from'] = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d"),date("Y")));
	            $date['to'] = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d"),date("Y")));
	            break;
	        case "lastWeek"://上周
	            $date['from'] = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y")));
	            $date['to'] = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y")));
	            break;
	        case "currentWeek"://本周
	            $date['from'] = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y")));
	            $date['to'] = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y")));
	            break;
	        case "lastMonth"://上月
	            $date['from'] = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m")-1,1,date("Y")));
	            $date['to'] = date("Y-m-d H:i:s",mktime(23,59,59,date("m") ,0,date("Y")));
	            break;
	        case "currentMonth"://本月
	            $date['from'] = date("Y-m-d H:i:s",mktime(0, 0 , 0,date("m"),1,date("Y")));
	            $date['to'] = date("Y-m-d H:i:s",mktime(23,59,59,date("m"),date("t"),date("Y")));
	            break;
	        case "season"://季度
	            $getMonthDays = date("t",mktime(0, 0 , 0,date('n')+(date('n')-1)%3,1,date("Y")));//本季度未最后一月天数
	            $date['from'] = date('Y-m-d H:i:s', mktime(0, 0, 0,date('n')-(date('n')-1)%3,1,date('Y')));
	            $date['to'] = date('Y-m-d H:i:s', mktime(23,59,59,date('n')+(date('n')-1)%3,$getMonthDays,date('Y')));
	            break;
	        default:
	            $date['from'] = "2014-03-04 00:00:00";
	            $date['to'] = date("Y-m-d H:i:s",time());
	    }
	    return $date;
	}
	
	/**
	 * 两个日期之间相差的天数
	 * (针对1970年1月1日之后，求之前可以采用泰勒公式)
	 * @param string $begin_time
	 * @param string $end_time
	 * @param int $type 默认1返回 array,0 返回时间戳 , 2返回天数 ,3返回小时
	 * @return array [day:1,hour:2,min:36,sec:40] | string $timediff
	 */
	public static function timediff($begin_time, $end_time,$type = 1){
	    $begin_time = strtotime($begin_time);
	    $end_time = strtotime($end_time);
	    $starttime = $begin_time;
	    $endtime = $end_time;
	    $timediff = intval($endtime) - intval($starttime);
	    if($type == 0){
	        return $timediff;
	    }elseif($type == 1){
	        $days = intval( $timediff / 86400 );
	        $remain = $timediff % 86400;
	        $hours = intval( $remain / 3600 );
	        $remain = $remain % 3600;
	        $mins = intval( $remain / 60 );
	        $secs = $remain % 60;
	        $res = array( "day" => $days, "hour" => $hours, "min" => $mins, "sec" => $secs );
	        return $res;
	    }elseif($type == 2){
	        $time = round($timediff / 86400,2);
	        if($time >= 0){
	            return ceil($timediff / 86400);
	        }else{
	            return -1;
	        }
	    }elseif($type == 3){
	        $hours = intval( $timediff / 3600 );
	        return $hours;
	    }
	}
	
	
	
}
/* End of file DateUtil.php */
/* Location: ./include/DateUtil.php */