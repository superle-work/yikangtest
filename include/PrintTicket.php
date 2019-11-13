<?php
/**
 * 微信打印机服务
 * @name PrintTicket
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class PrintTicket{
	
	/**
	 * 打印服务
	 * @param array $mesageInfo 小票信息
	 * @param array $deviceInfo 设备信息
	 */
//	public function sendPrint($mesageInfo,$deviceInfo){
//		$client = new SoapClient("http://114.215.142.59:8080/kdtprint/services/printcenter?wsdl");
//		$client->soap_defencoding = 'UTF-8';
//		$client->decode_utf8 = false;
//		$client->xml_encoding = 'UTF-8';
//		$aryPara = array(
//				'deviceno'=> $deviceInfo['ticket_device_num'],
//				'devicekey'=> $deviceInfo['ticket_device_key'],
//				'printid'=> $mesageInfo['num'],
//				'message'=>$this->setOrderMessage($mesageInfo)
//			);
//		$result = $client->addorder($aryPara);
//		$stringList = explode("&", $result->String);
//		$codeList= explode("=", $stringList[0]);
//		$code= $codeList[1];
//		return $this->getCodeInfo($code);
//	}
	
	function sendPrint($mesageInfo,$deviceInfo,$times = 1){
		$url = "http://open.printcenter.cn:8080/addOrder";
		$selfMessage = array(
			'deviceNo'=>$deviceInfo['ticket_device_num'],  
			'printContent'=>$this->setOrderMessage($mesageInfo),
			'key'=>$deviceInfo['ticket_device_key'],
			'times'=>$times
		);
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencoded ",
				'method'  => 'POST',
				'content' => http_build_query($selfMessage),
			),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		
		return $result;
	}
	
	/**
	 * 打印机服务，新接口
	 * array $messageInfo 打印内容
	 * array $deviceInfo 打印机的编号(deviceNo) 和 密钥（key）
	 * int $times打印联数（同一订单，打印的次数，只对S2小票机有效，S1小票机和USB小票机使用打印机命令完成打印联数的设定）
	 */
	function sendNewPrint($mesageInfo,$deviceInfo,$times = 1){

		$url = "http://open.printcenter.cn:8080/addOrder";
		$selfMessage = array(
			'deviceNo'=>$deviceInfo['ticket_device_num'],  
			'printContent'=>$this->setOrderNewMessage($mesageInfo),
			'key'=>$deviceInfo['ticket_device_key'],
			'times'=>$times
		);
		$options = array(
			'http' => array(
				'header' => "Content-type: application/x-www-form-urlencoded ",
				'method'  => 'POST',
				'content' => http_build_query($selfMessage),
			),
		);
		$context  = stream_context_create($options);
		$result = file_get_contents($url, false, $context);
		chromephp::info($result);
		return $result;
	}


	/**
	 * 打印服务测试
	 * @param array $mesageInfo 小票信息
	 * @param array $deviceInfo 设备信息
	 */
	public function sendPrintTest($mesageInfo,$deviceInfo){
		$client = new SoapClient("http://114.215.142.59:8080/kdtprint/services/printcenter?wsdl");
		$client->soap_defencoding = 'UTF-8';
		$client->decode_utf8 = false;
		$client->xml_encoding = 'UTF-8';
		$aryPara = array(
				'deviceno'=> $deviceInfo['ticket_device_num'],
				'devicekey'=> $deviceInfo['ticket_device_key'],
				'printid'=> time(),
				'message'=>$mesageInfo
		);
		$result = $client->addorder($aryPara);
		$stringList = explode("&", $result->String);
		$codeList= explode("=", $stringList[0]);
		$code= $codeList[1];
		return $this->getCodeInfo($code);
	}
	
	/**
	 * 处理返回结果
	 * @param int $code
	 * @return array
	 */
	private function getCodeInfo($code){
		if($code == 1){//订单添加成功，正在打印中
			return common::errorArray(0, "正在打印", true);
		}else if($code == 2 ){//2：订单添加成功，但是打印机缺纸，无法打印；3：订单添加成功，但是打印机不在线；
			return common::errorArray(2, "打印机缺纸或未联网，恢复后可重新打印", false);
		}else if($code == 3){
			return common::errorArray(3, "订单添加成功，但是打印机不在线", false);
		}else if($code == 10){//内部服务器错误
			return common::errorArray(1, "内部服务器错误", false);
		}else if($code == 11){//参数不正确
			return common::errorArray(1, "/参数不正确 ", false);
		}else if($code == 12){//打印机未添加到服务器
			return common::errorArray(1, "打印机未添加到服务器", false);
		}else if($code == 13){//未添加为订单服务器
			return common::errorArray(1, "未添加为订单服务器", false);
		}else if($code == 14){//订单服务器和打印机不在同一个组
			return common::errorArray(1, "订单服务器和打印机不在同一个组", false);
		}else if($code == 15){//订单已经存在，不能再次打印
			return common::errorArray(1, "订单已经存在，不能再次打印", false);
		}else{
			return common::errorArray(1, "打印失败", false);
		}
	}
	
	/**
	 * 设置模板消息
	 * @param array $mesageInfo
	 * @return string
	 */
	private function setOrderMessage($mesageInfo){
		$title = $this->setTitle($mesageInfo['ticket_title']);
		$goodsList = $this->setGoodsList($mesageInfo['goodsList']);
		$qcode = $this->setQcode($mesageInfo['ticket_qcode']);
		$message = 
"^B2{$title}
********************************
订单编号:{$mesageInfo['order_num']}
下单时间:{$mesageInfo['time']}
推荐诊所:{$mesageInfo['clinic_name']}
支付方式:{$mesageInfo['pay_method']}
姓名:{$mesageInfo['name']}
性别:{$mesageInfo['sex']}
出生年月日:{$mesageInfo['idCard']}
--------------------------------
检测项目   单价   数量  采样容器
{$goodsList}
--------------------------------
合计:{$mesageInfo['total_price']}
********************************
{$mesageInfo['ticket_ad']}
服务热线:{$mesageInfo['ticket_phone']}";
		if($qcode){
			$message = $message . "
$qcode";
		}
		if($mesageInfo['ticket_is_double']){
			$message = $message . $this->printDouble($mesageInfo);
		}
		return $message;
	}
	
	private function printDouble($mesageInfo){
		$title = $this->setTitle('商户联');
		$goodsList = $this->setGoodsList($mesageInfo['goodsList']);
		$message =
"


^B2{$title}
********************************
订单编号:{$mesageInfo['order_num']}
下单时间:{$mesageInfo['time']}
支付方式:{$mesageInfo['pay_method']}
收货人:{$mesageInfo['accepter']}
收货地址:{$mesageInfo['address']}
手机:{$mesageInfo['phone']}
留言:{$mesageInfo['message']}
--------------------------------
商品名称            单价    数量
{$goodsList}
--------------------------------
总价:{$mesageInfo['total_price']}";
		return $message;
	}
	
	/**
	 * 设置模板消息(新版)
	 * @param array $mesageInfo
	 * @return string
	 */
	private function setOrderNewMessage($mesageInfo){
		$title = $this->setTitle($mesageInfo['ticket_title']);
		$goodsList = $this->setGoodsList($mesageInfo['goodsList']);
		$qcode = $this->setNewQcode($mesageInfo['ticket_qcode']);
		$message = 
"<C>{$title}</C>
********************************
订单编号:{$mesageInfo['order_num']}
下单时间:{$mesageInfo['time']}
采样诊所:{$mesageInfo['clinic_name']}
推荐诊所:{$mesageInfo['clinicID']}
支付方式:{$mesageInfo['pay_method']}
姓名:{$mesageInfo['name']}
性别:{$mesageInfo['sex']}
出生年月日:{$mesageInfo['idCard']}
--------------------------------
检测项目                  单价
{$goodsList}
--------------------------------
合计:{$mesageInfo['total_price']}
********************************
{$mesageInfo['ticket_ad']}
服务热线:{$mesageInfo['ticket_phone']}";
		
		if($qcode){
			$message = $message . "
$qcode"."</QR>";
		}
		if($mesageInfo['ticket_is_double']){
			$message = $message . $this->printNewDouble($mesageInfo);
		}
		return $message;
	}
	
	private function printNewDouble($mesageInfo){
		$title = $this->setTitle('商户联');
		$goodsList = $this->setGoodsList($mesageInfo['goodsList']);
		$message =
"


<C>{$title}</C>
********************************
订单编号:{$mesageInfo['order_num']}
下单时间:{$mesageInfo['time']}
支付方式:{$mesageInfo['pay_method']}
收货人:{$mesageInfo['accepter']}
收货地址:{$mesageInfo['address']}
手机:{$mesageInfo['phone']}
留言:{$mesageInfo['message']}
--------------------------------
商品名称              单价     数量   
{$goodsList}
--------------------------------
总价:{$mesageInfo['total_price']}";
		return $message;
	}
	

	
	
	
	/**
	 * 设置商品字符串
	 * @param array $goodsList
	 * @return string
	 */
	private function setGoodsList($goodsList){
		$perString = "";
		foreach ($goodsList as $k=>$goods){
			$perString .=($k+1).'、'.$this->setEmptyStringName($goods['goods_name']);
			$perString.=" " . $goods['price']."<BR>";
			$perString.="    "."采样容器:".$goods['sample_vessel']."<BR>";
		}
		$perString = rtrim($perString,"");
		return $perString;
	}
	
	/**
	 * 设置订单小票标题
	 * @param string $title
	 * @return string
	 */
	private function setTitle($title){
		$length = $this->stringLength($title);
		if($length > 32/2){//最多32个字符 标题放大2倍
			return "标题过长";
		}else{
			$offset = ceil((16 - $length) / 2);
			$nullString = "";
			for($i = 0;$i < $offset;$i++){
				$nullString .= " ";
			}
			$title = $nullString . $title;
		}
		return $title;
	}
	
	
	/**
	 * 设置商品数量和价格
	 * @param int $num
	 * @return string
	 */
	private function setEmptyStringNum($num){
		$length = $this->stringLength($num);
		if($length >= 6){
			return $num;
		}else{
			$offset = 6 - $length;
			$nullString = "";
			for($i = 0;$i < $offset;$i++){
				$nullString .= " ";
			}
			$num = $num . $nullString;
		}
		return $num;
	}
	
	/**
	 * 设置商品名
	 * @param string $name
	 * @return unknown|string
	 */
	private function setEmptyStringName($name){
		$length = $this->stringLength($name);
		if($length >= 20){
			return $name;
		}else{
			$offset = 20 - $length;
			$nullString = "";
			for($i = 0;$i < $offset;$i++){
				$nullString .= " ";
			}
			$name = $name . $nullString;
		}
		return  $name;
	}
	
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
	
	/**
	 * 设置二维码
	 * @param string $qcodeString
	 * @return string
	 */
	private function setQcode($qcodeString){
		if(!$qcodeString) return false;
//		$title = "
//--------------------------------
//      扫码核销!" . "";
		$qcodeString = "^Q+$qcodeString";
		return $qcodeString;
	}

	/**
	 * 设置二维码(新版)
	 * @param string $qcodeString
	 * @return string
	 */
	private function setNewQcode($qcodeString){
		if(!$qcodeString) return false;
		$title = "
--------------------------------
        扫一扫,关注我们!" . "
";
		$qcodeString = "<QR>$qcodeString";
		return $qcodeString;
	}
}
/* End of file PrintTicket.php */
/* Location: ./include/PrintTicket.php */