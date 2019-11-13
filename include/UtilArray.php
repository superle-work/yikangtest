<?php
/**
 * 数组处理
 * @name UtilArray
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class UtilArray{
	/**
	 * 计算字符串的长度（汉字按照两个字符计算）
	* @param   string $str 字符串
	* @return  int
	* @author jianfang
	*/
	function stringLength($str){
		$length = strlen(preg_replace('/[x00-x7F]/', '', $str));
		if ($length){
			return strlen($str) - $length + intval($length / 3) * 2;
		}
		else{
			return strlen($str);
		}
	}
	
	//将内容进行UNICODE编码
	function unicodeEncode($name){
		$name = iconv('UTF-8', 'UCS-2', $name);
		$len = strlen($name);
		$str = '';
		for ($i = 0; $i < $len - 1; $i = $i + 2){
			$c = $name[$i];
			$c2 = $name[$i + 1];
			if (ord($c) > 0){    // 两个字节的文字
				$str .= '\u'.base_convert(ord($c), 10, 16).base_convert(ord($c2), 10, 16);
			}
			else{
				$str .= $c2;
			}
		}
		return $str;
	}
	
	// 将UNICODE编码后的内容进行解码
	function unicodeDecode($name){
		// 转换编码，将Unicode编码转换成可以浏览的utf-8编码
		$pattern = '/([\w]+)|(\\\u([\w]{4}))/i';
		preg_match_all($pattern, $name, $matches);
		if (!empty($matches)){
			$name = '';
			for ($j = 0; $j < count($matches[0]); $j++){
				$str = $matches[0][$j];
				if (strpos($str, '\\u') === 0){
					$code = base_convert(substr($str, 2, 2), 16, 10);
					$code2 = base_convert(substr($str, 4), 16, 10);
					$c = chr($code).chr($code2);
					$c = iconv('UCS-2', 'UTF-8', $c);
					$name .= $c;
				}
				else{
					$name .= $str;
				}
			}
		}
		return $name;
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
	
	/**
	 * 数组转对象
	 * @param array $e
	 * @return object
	 */
	public static function arrayToObject($e){
	    if( gettype($e) != 'array' ) return;
	    foreach($e as $k=>$v){
	        if( gettype($v) == 'array' || getType($v)=='object' )
	            $e[$k] = (object)self::arrayToObject($v);
	    }
	    return (object)$e;
	}
	
	/**
	 * 对象转数组
	 * @param array $e
	 * @return array
	 */
	public static function objectToArray($e){
	    $e=(array)$e;
	    foreach($e as $k=>$v){
	        if( gettype($v) == 'resource' ) return;
	        if( gettype($v) == 'object' || gettype($v) == 'array' )
	            $e[$k] = (array)self::objectToArray($v);
	    }
	    return $e;
	}
	
}
/* End of file UtilArray.php */
/* Location: ./include/UtilArray.php */