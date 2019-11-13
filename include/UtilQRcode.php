<?php
/**
 * 提供二维码服务
 * @name QRcode.php
 * @package fenxiao
 * @category include
 * @link http://www.changekeji.com
 * @author leon
 * @version 1.0
 * @since 2016-08-24
 */
class UtilQRcode{
	
	/**
	 * 二维码文件流
	 * @param string $code
	 * @param int $size
	 */
	function getQRcode($code,$size = 10){
		if(!class_exists('phpqrcode')) include 'include/phpqrcode/phpqrcode.php';
		$errorCorrectionLevel = 'L';//容错级别
		$matrixPointSize = $size;//生成图片大小
		//生成二维码图片
		QRcode::png($code, false, $errorCorrectionLevel, $matrixPointSize, 2);
	}
	
	/**
	 * 获取带参数的二维码
	 * @param int $param 需要传入的参数 只支持1--100000
	 * @param int $type 1 临时 2 永久
	 * @param $expireSeconds Int 过期时间，只在类型为临时二维码时有效。最大为1800，单位秒
	 * @return Array()
	 */
	function getParamQRcode( $param, $type = 1, $expireSeconds = '1800'){
	    if(!class_exists('Popularize')) include 'include/wechatUtil/Popularize.php';
	    $return = Popularize::createTicket($type, $expireSeconds, $param);
	    $ticket = urlencode($return['ticket']);
	    $url = 'https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=' . $ticket;
// 	    $url = Popularize::long2short($get);//压缩长url
	    return $url;
	}
	
	
	
}
