<?php
/**
 * 提供工具服务
 * @name 工具管理类
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class utils{
	/**
	 * 计算字符串的长度（汉字按照两个字符计算）
	* @param   string $str 字符串
	* @return  int
	* @author jianfang
	*/
	function strLen($str){
		$length = strlen(preg_replace('/[x00-x7F]/', '', $str));
		if ($length){
			return strlen($str) - $length + intval($length / 3) * 2;
		}
		else{
			return strlen($str);
		}
	}
	
	/**
	 * 数组转XML
	 * @param array $arr
	 * @return string
	 */
	function arrayToXml($arr){
		$xml = "<xml>";
		foreach ($arr as $key => $val){
			if(is_numeric($val)){
				$xml .= "<" . $key . ">" . $val . "</" . $key . ">";
			}else{
				$xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
			}
		}
		$xml .= "</xml>";
		return $xml;
	}
	
	/**
	 * xml转数组
	 * @param  $xml
	 * @return mixed
	 */
	function xmlToArray($xml){
		$array= json_decode(json_encode(simplexml_load_string($xml,NULL,LIBXML_NOCDATA)),true);
		return $array;
	}
}
/* End of file utils.php */
/* Location: ./include/utils.php */