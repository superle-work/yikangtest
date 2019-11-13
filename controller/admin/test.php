<?php
class test extends spController
{
	/**
	 * 构造函数
	 *
	 */
	function __construct() {
		parent::__construct ();
		error_reporting ( E_ALL & ~ E_NOTICE & ~ E_DEPRECATED & ~ E_STRICT & ~ E_WARNING );
	}
    
	function test1(){
	    chromephp::info(time());
	}
	
	function testSend() {
	    echo 'testSend';
	    if(!class_exists('TemplateMessage')) include 'include/wechatUtil/TemplateMessage.php';
	    $oid = 29;
	    $userCondition = array('account' => 'ooMjDs2IM4pLWMQigdtHhAyhLmQ0');
	    $res = TemplateMessage::RealSendTemplateMessage($oid, 3, 'store', $userCondition);
	    dump($res);
	}
	
    //----------------------rn demo -------------

	function rnlabelList(){
	    $labelList = array(
	        array('name' => '内蒙古特产','pic' => 'upload/image/store/label/0923101506147_thumb.jpg','id' => 1),
	        array('name' => '东北特产','pic' => 'upload/image/store/label/0923101424461_thumb.jpg','id' => 2),
	        array('name' => '俄罗斯特产','pic' => 'upload/image/store/label/092310162114_thumb.jpg','id' => 3),
	        array('name' => '北海道特产','pic' => 'upload/image/store/label/0923101601808_thumb.jpg','id' => 4),
	    );
	    echo json_encode($labelList);
	}

	function rnfetchData(){
			// $firstParam = $this->spArgs('firstParam');//错误
			$json = json_decode(file_get_contents('php://input'), true);//正确
			echo json_encode(array('firstParam' => $json));
	}

	function rnfetchDataOther(){
			$json = $this->spArgs('first');//错误
			// $json = json_decode(file_get_contents('php://input'), true);//正确
			echo json_encode(array('firstParam' => $json));
	}
	function rnfetchDataGet(){
			$json = $this->spArgs('first');//错误
			// $json = json_decode(file_get_contents('php://input'), true);//正确
			echo json_encode(array('firstParam' => $json));
	}

	function rnScrollDownData(){
		$row = $this->spArgs();
		$n = $row['pageNum']*$row['pageSize'];
		$rowsData = array();
		if($row['pageNum'] < 2 ){
			for($i = $n + 1 ;$i <= $n+$row['pageSize'];$i ++ ){
				array_unshift($rowsData,$i);
			}
		}
		echo json_encode($rowsData);
	}

	function rnScrollUpData(){
		$row = $this->spArgs();
		$rowsData = array();
		if(!$row['searchText']){
			$n = $row['pageNum']*$row['pageSize'];
			if($row['pageNum'] < 2 ){
				for($i = $n + 1 ;$i <= $n+$row['pageSize'];$i ++ ){
					array_push($rowsData,array('id' => $i,'status' => 0));
				}
			}
		}else if(1 <= $row['searchText'] && $row['searchText'] <= 40){
				$rowsData = array(array('id' => $row['searchText'],'status' => 0));
		}
		// $rowsData = array(2,3,5,6,7,8);
		echo json_encode($rowsData);
	}
	
	//支付宝签名接口
	function getSign(){
	    $orderInfo = array("body" => "这商品价值0.01元",
	                 "subject" => "商品价值",
	                 "out_trade_no" => "705011111291243923244322",
	                 "total_amount" => 0.01,
	                 "product_code" => "QUICK_MSECURITY_PAY"
	           );
	    $orderString = '{';
	    foreach($orderInfo as $key => $value){
	        $valueString = gettype($value) != 'string' ? $value : '"'.$value.'"';//判断类型
	        $orderString .= '"'.$key.'":'.$valueString.',';
	    }
	    $orderString = rtrim($orderString,',').'}';
	    chromephp::info($orderString);
	    
	    $alipayObj = array(
	        'app_id' => '2016030601188504',
	        'biz_content' => $orderString,
	        'charset' => 'utf-8',
	        'version' => '1.0',
	        'timestamp' => date('Y-m-d H:i:s',time()),//'2017-02-16 15:25:00',
	        'method' => 'alipay.trade.app.pay',
	        'notify_url' => 'http://www.baidu.com',
	        'sign_type' => 'RSA2'
	    );
	    ksort($alipayObj);
	    $params = http_build_query($alipayObj);
	    $data = urldecode($params);
	    // 签名 若获取到的是pem中的密钥，则不需要拼接
	    $signature = '';
	    $key_width = 64;
	    $privateKey =  'MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCMYHd+/HBMFZ20pzHVcMZQVvl+QP/lYptGtWuN5hxHh1jS+NaQrhucNfYsd7GP2MwnLnRNoq8E03eZ7n9QsGzp8z1GsFzcNN2lCkyt19JqSI9HoFZKOgIbc7KU9AtiCPiNz2H34H0wF2sV3bD3iBiN6wI7JwWVFP9aVww0+VkR05xyd3VSuvHvRHYBpJd7egO8I2RHUVUdlXvp/abIKu+AjhlMDxH9FJlYselHZ+QzGb1nZwqV16dktrp8OJ0sybUozTW9EtNkcORr6rdGGpd6/3AVuyDH9cbAwTSF1J2hg0v51gEmdL5hpb+pKBEtydr0DyXgqBIcmgJBDUNOCqu/AgMBAAECggEAeHRelYywH9l8lgPR80DuKwo21GuaKY9PhoEuZXjLU2WEhqQYGdYMwyJatxWOO8zggc6O5f9vB0keG+xXOznoe2fJcNrtE9gZSQFpgtLrL2BL8j5XTSIxpLTGzKINEnZ4Mdd+TWFIivfNX9n3fLA/waejJnb5h0+ZPj/rrZk5IN2ZdDUFWuYw5OyOBXYwv4F3lw7eLptTZAsKpOwNuxAOmA1/FVBzpzpf2enP12Mc7LD0vbb6PMHwzKZD9hgObFqpCAOw54fgnZNuEwGInlh648zkmfeqSSTxfVCpA4zyI76NR67M82UG+5wOQ/VjyxIbUNUx11gZRJUrGI5gKHi9YQKBgQDPr+cMmILyBt67zq6K7Zq2TpWIlAqcwFwF4izA1EpEEmKgEBduCllW+2IBEtfdxuQfd9Jm78H68eVXUkwuwlMddtzAIcd9XM4gYgeolBAMe2/ic9r9Ocfv64mnN8K7j2uIT43+44Ezau355BxGs13gXLtLJxnwd8VSi1iX92qCZQKBgQCtCCEb1M5Q0HbUkx8sTYxPX7jqsqHPgrlP8AEtJ11NdA0IN294I4pfUMIyYhumbQUxV/SsceWc8N1dt7d+1qfzZsmdj4cY5UsgqBab1XPgUwNzmULwjGE/1XGlbk8ct/k0elGHfJLg9jsIUs30UgHmlZPBd234uaaZ1TyrPAABUwKBgDD1PVkJxqx5FB5fnWOgmTzqj3NvuoSzD75PT89w+8TKNLeYs308MU1A2xx3ra1ZCkOkwlODp02Zoj+QZecL5f3nHCiqjdUugGS/1yBVLudSXdCbEP9qlIgpFPz3Nw9xwp6Sal2wka9mQI0MyeGcvL97/Ka9o+68vSY1NQA10cTFAoGADsZO7W1yYHwsaWJWmxXUEUL/KystneZvpF+9+fkOgnqSUk1Je1ytiA4BRwQRkFhmxRvZjI/9JzV58XKqfG4f0SzJsmZ7BqktjRBNPekwB8uO0+QWTyvtceHr3lBY+P7MjKqVI5iDgioESGWpqF8IQoQrJa3o+gAANcp7b7Oj3HkCgYA/cl2odt76oZBghDm1PMKgFQUS7C90QKv/x1TslyBKVOyc/qj+pFsKAnn9ACc1vj2jnjo2ETICLRw1rv0qghtz02lH2GbknJcNLzvZcislA+2dyrb6tRTbGaEtx8giXEymeqZeOViqvi3NQ6d87ODaES/0Fa3DciAShy0pCvIstg==';
	    $privateKey = "-----BEGIN RSA PRIVATE KEY-----\n" .wordwrap($privateKey, 64, "\n", true) ."\n-----END RSA PRIVATE KEY-----";
	    chromephp::info($privateKey);
	    $private_id = openssl_get_privatekey($privateKey);
	    if("RSA2" == $alipayObj['sign_type']){
	        chromephp::info($data);
	        openssl_sign($data, $signature, $private_id, OPENSSL_ALGO_SHA256 );
	    }else{
	        openssl_sign($data, $signature, $private_id, OPENSSL_ALGO_SHA1 );
	    }
	    openssl_free_key( $private_id );
	    $signature = base64_encode($signature);
	    $url = $params.'&sign='.urlencode( $signature );
	    echo json_encode(common::errorArray(0, '签名成功', $url));
	}
	
	/**
	 * 生成随机字符串
	 */
	function getNonceStr(){
	    $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	    return substr(str_shuffle($string),0,32);
	}
	
	/**
	 * 微信签名
	 * @param array $orderInfo
	 */
	function weiChatSign($orderInfo){
	    //重组数据
	    ksort($orderInfo);//排序
	    $params = urldecode(http_build_query($orderInfo)).'&key=af873dsadkkjld2ADHHSKAIENCSKDHYd';
	    $sign = strtoupper(md5($params));//签名
	    return $sign;
	    
	}
	
	function callWeb($orderInfo,$url,$useCert = false){
	    include 'include/UtilArray.php';
	    $orderXml = UtilArray::arrayToXml($orderInfo);
	    chromephp::info($orderXml);
	    $result = $this->postXmlCurl($orderXml,$url,$useCert);
	    return UtilArray::xmlToArray($result);
	}
	/**
	 * 下单
	 */
	function addOrder(){
	    //微信统一下单
	    $orderInfo = array(
	        'appid' => 'wxb3236346d36004e2',
	        'mch_id' => '1440337302',
	        'nonce_str' => $this->getNonceStr(),
	        'body' => '微信支付测试',
	        'out_trade_no' => 'QjCE890620170207',
	        'total_fee' => 2,
	        'spbill_create_ip' => '183.160.75.225',
	        'notify_url' => 'http://www.baidu.com',
	        'trade_type' => 'APP'
	        
	    );
	    $orderInfo['sign'] = $this->weiChatSign($orderInfo);
	    $result = $this->callWeb($orderInfo,'https://api.mch.weixin.qq.com/pay/unifiedorder');
	    chromephp::info($result);
	    //微信支付
	    $orderInfo = array(
	        'appid' => 'wxb3236346d36004e2',
	        'partnerid' => '1440337302',
	        'noncestr' => $this->getNonceStr(),
	        'prepayid' => $result['prepay_id'],
	        'timestamp' => (string) time(),
	        'package' => 'Sign=WXPay'  
	    );
	    $orderInfo['sign'] = $this->weiChatSign($orderInfo);
	    chromephp::info($orderInfo);
	    echo json_encode($orderInfo);
	}
	
	/**
	 * 微信付款
	 */
	function weiChatRefund(){
	    $refundOrder = array(
	        'appid' => 'wxb3236346d36004e2',
	        'mch_id' => '1440337302',
	        'nonce_str' => $this->getNonceStr(),
	        'transaction_id' => '4006012001201702180413179955',
//             'out_trade_no' => 'QjCE890520170207',
	        'out_refund_no' => 'QjTui878720170218',
	        'total_fee' => 2,
	        'refund_fee' => 1,
	        'op_user_id' => '1440337302'
	    );
	    
	    $refundOrder['sign'] = $this->weiChatSign($refundOrder);
	    $result = $this->callWeb($refundOrder,'https://api.mch.weixin.qq.com/secapi/pay/refund',true);
	    chromephp::info($result);
	    echo json_encode($result);
	}
	
	/**
	 * 发送xml请求
	 * @param unknown $xml
	 * @param unknown $url
	 * @param string $useCert
	 * @param number $second
	 */
	private function postXmlCurl($xml, $url, $useCert = false, $second = 30){
	    $ch = curl_init();
	    //设置超时
	    curl_setopt($ch, CURLOPT_TIMEOUT, $second);
	    curl_setopt($ch,CURLOPT_URL, $url);
	    curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
	    curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,2);
	    //设置header
	    curl_setopt($ch, CURLOPT_HEADER, FALSE);
	    //要求结果为字符串且输出到屏幕上
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	    chromephp::info($useCert);
	    if($useCert == true){
	        chromephp::info(111);
	        //设置证书
	        //使用证书：cert 与 key 分别属于两个.pem文件
	        curl_setopt($ch,CURLOPT_SSLCERTTYPE,'PEM');
	        curl_setopt($ch,CURLOPT_SSLCERT, getcwd().'/pay/appCert/apiclient_cert.pem');
	        curl_setopt($ch,CURLOPT_SSLKEYTYPE,'PEM');
	        curl_setopt($ch,CURLOPT_SSLKEY, getcwd().'/pay/appCert/apiclient_key.pem');
	    }
	    //post提交方式
	    curl_setopt($ch, CURLOPT_POST, TRUE);
	    curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
	    //运行curl
	    $data = curl_exec($ch);
	    //返回结果
	    if($data){
	        curl_close($ch);
	        return $data;
	    } else {
	        $error = curl_errno($ch);
	        curl_close($ch);
	        return false;
	    }
	  }
	
}

	
