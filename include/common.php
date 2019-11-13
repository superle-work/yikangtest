<?php
/**
 * 公共方法
 * @name common
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class common {
	/**
	 * 生成GUID
	 */
	public static function guid() {		
		if (function_exists ( 'com_create_guid' )) {
			$guid= com_create_guid ();
		} else {
			mt_srand ( ( double ) microtime () * 10000 ); // optional for php 4.2.0 and up.
			$charid = strtoupper ( md5 ( uniqid ( rand (), true ) ) );
			$hyphen = chr ( 45 ); // "-"
			$uuid = chr ( 123 ) . 			// "{"
			substr ( $charid, 0, 8 ) . $hyphen . substr ( $charid, 8, 4 ) . $hyphen . substr ( $charid, 12, 4 ) . $hyphen . substr ( $charid, 16, 4 ) . $hyphen . substr ( $charid, 20, 12 ) . chr ( 125 ); // "}"
			$guid=$uuid;			
		}
		return substr($guid, 1,36);
	}
	
	/**
	 * 获取当前时间
	 * @return string time 2016-08-02 16:01:01
	 */
	public static function getTime(){
	    return date('Y-m-d H:i:s',strtotime('+8hour'));
	}
	
	/**
	 * 生成返回信息数组
	 * @param int $errorCode
	 * @param string $errorInfo
	 * @param array $data
	 */
	static function errorArray($errorCode, $errorInfo, $data){
		$result ["errorCode"] = $errorCode;
		$result ["errorInfo"] = $errorInfo;//提示信息
		$result ["data"] = $data;
		return $result;
	}

	/**
	 * 控制器权限验证
	 * @param array $session
	 * @param string $go
	 */
	public static function rightVerify($session,$go){
		if (!isset($session))
		{
			echo "<html><head><meta http-equiv='refresh' content='0;url=".$go."'></head><body></body><ml>";
			echo "<script type='text/javascript'>window.location.href('".$go."');</script>";
			exit;
		}
	}
	
	/**
	 * 索引编码
	 * @param string $str
	 * @return string
	 */
	public static function encodeIndex($str){
		$data = explode(" ",$str);
		$data = array_filter($data);
		$data = array_flip(array_flip($data));
		foreach ($data as $ss) {
			if(strlen($ss) > 1){
				$data_code .= str_replace("%", "", urlencode($ss));
			}
		}
		return trim($data_code);
		//SELECT * FROM fulltext_sample WHERE MATCH(copy) AGAINST('E696B9E581A5E4BDA0E5A5BD');
	}
	
	/**
	 * 获取ip地址
	 * @return stirng	
	 */
	public static function getRealIp(){
		$ip=false;
		if(!empty($_SERVER["HTTP_CLIENT_IP"])){
			$ip = $_SERVER["HTTP_CLIENT_IP"];
		}
		if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
			if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
			for ($i = 0; $i < count($ips); $i++) {
				if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
					$ip = $ips[$i];
					break;
				}
			}
		}
		return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
	}
	
	/**
	 * 获取ip所属信息
	 * @param string $ip
	 * @return mixed
	 */
	public static function getAreaByIp($ip){
		$taobaoUrl = "http://ip.taobao.com/service/getIpInfo.php?ip={$ip}";
		/*
		 * {"code":0,"data":{"country":"中国","country_id":"CN","area":"华东","area_id":"300000","region":"安徽省","region_id":"340000","city":"合肥市","city_id":"340100","county":"","county_id":"-1","isp":"电信","isp_id":"100017","ip":"114.97.7.229"}}
		 */
		$rawResult = file_get_contents($taobaoUrl);
		$result = json_decode($rawResult);
		$result = (array)$result;
		$result = (array)$result['data'];
		return $result;
	}
	
	
	
	/**
	 * 识别设备
	 * @return string
	 */
	public static function detectDevice(){
		$user_agent = $_SERVER['HTTP_USER_AGENT'];
		if(stristr($user_agent,'iPad')) {
			return 'iPad';
		}else if(stristr($user_agent,'Android')) {
			return 'Android';
		}else if(stristr($user_agent,'Linux')){
			return 'Linux';
		}else if(stristr($user_agent,'iPhone')){
			return 'iPhone';
		}else if(stristr($user_agent,'iPad')){
			return 'iPad';
		}else if(stristr($user_agent,'Windows Phone')){
			return 'WP';
		}else{
			return 'other';
		}
	}
	
	/**
	 *  @desc 根据两点间的经纬度计算距离
	 *  @param float $lat1  第一个点纬度值
	 *  @param float $lng1  第一个点经度值
	 *  @param float $lat2  第二个点纬度值
	 *  @param float $lng2  第二个点经度值
	 *  @return int 距离 单位米
	 */
	static function getDistance($lat1, $lng1, $lat2, $lng2){
		$earthRadius = 6367000; //地球圆周率
	    //第一个点
		$lat1 = ($lat1 * pi() ) / 180;
		$lng1 = ($lng1 * pi() ) / 180;
    	//第二个点
		$lat2 = ($lat2 * pi() ) / 180;
		$lng2 = ($lng2 * pi() ) / 180;
	    //计算距离
		$calcLongitude = $lng2 - $lng1;
		$calcLatitude = $lat2 - $lat1;
		$stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
		$stepTwo = 2 * asin(min(1, sqrt($stepOne)));
		$calculatedDistance = $earthRadius * $stepTwo;
		return round($calculatedDistance);
	}
	//-----------------------------------------计算时间差函数--------------------------------------------
	/**
	 * 两个日期之间相差的天数
	 * (针对1970年1月1日之后，求之前可以采用泰勒公式)
	 * @param string $begin_time
	 * @param string $end_time
	 * @param int $type 默认0返回时间戳 ,1返回 array , 2返回天数 ,3返回小时,4返回分钟
	 * @return array [day:1,hour:2,min:36,sec:40] or string $timediff
	 */
	static function timediff($begin_time, $end_time,$type = 0){
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
	    }elseif($type == 4){
	        $mins = round( $timediff / 60 , 3);
	        return $mins;
	    }
	}
}