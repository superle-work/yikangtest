<?php
/**
 * 邮箱服务
 * @name UtilMail
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-01
 */
class UtilMail{
	private $host = "smtp.exmail.qq.com";
	private $from = "notify@changekeji.com";
	private $userName = "notify@changekeji.com";
	private $passWord = "Qjkj123";
	
	//业务类邮件
	
	/**
	 * 验证邮箱
	 * @param 目标邮箱 $to
	 * @param 主题 $subject
	 * @param 内容 $content=array("nick_name"=>用户名，"verifyUrl"=>密码,"customer"=>客户姓名);
	 */
	function verifyMail($to,$subject,$content){
		try {
			require 'PHPMailer/class.phpmailer.php';
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
			$mail->SMTPAuth = true; //开启认证
			$mail->Port = 25;
			$mail->Host = $this->host;
			$mail->Username = $this->userName;
			$mail->Password = $this->passWord;
			$mail->FromName =  $content["customer"];
			$mail->From = $this->from;
			$mail->AddAddress($to);
			$mail->Subject =$subject;
			$mail->Body = "<p style='font-size:20px; font-weight:100;' >亲爱的  <span>". $content["nick_name"] ."：</span> </p>"
					."<p>欢迎使用<span style='font-size:20px; font-weight:100;' > {$content["customer"]}邮箱验证 </span>功能。 请点击以下链接以完成邮箱验证，链接30分钟内有效：</p><p>"
							."<a href='".$content["verifyUrl"]."'>点我验证邮箱</a></p>"
									."<p>如果点击以上链接没有反应，请将该{$content["verifyUrl"]}网址复制并粘贴到新的浏览器窗口中。</p>"
											."<p>如果您并未发过此请求，则可能是因为其他用户在尝试验证时误输入了您的电子邮件地址而使您收到这封邮件，那么您可以放心的忽略此邮件，无需进一步采取任何操作。 </p>"
													."<P>此致</P>"
															."<P>{$content["customer"]}&nbsp;&nbsp;敬上 </P>"
																	."<p>".date("Y-m-d H:i:s")."</p>";
			$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
			$mail->WordWrap = 80; // 设置每行字符串的长度
			$mail->IsHTML(true);
			$mail->Send();
			return common::errorArray(0, "发送成功", true);
		} catch (phpmailerException $e) {
			return common::errorArray(1, "发送失败", $e);
		}
	}
	
	/**
	 * 发送密码修改邮件
	 * @param 目标邮箱 $to
	 * @param 主题 $subject
	 * @param 内容 $content=array("userName"=>用户名，"verifyUrl"=>验证地址,"customer"=>客户姓名);
	 */
	function changePassword($to,$subject,$content){
		try {
			require_once  'PHPMailer/class.phpmailer.php';
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
			$mail->SMTPAuth = true; //开启认证
			$mail->Port = 25;
			$mail->Host = $this->host;
			$mail->Username = $this->userName;
			$mail->Password = $this->passWord;
			$mail->FromName =  $content["customer"];
			$mail->From = $this->from;
			$mail->AddAddress($to);
			$mail->Subject =$subject;
			$mail->Body = "<p style='font-size:20px; font-weight:100;' >亲爱的  <span>". $content["nick_name"] ."：</span> </p>"
					."<p>欢迎使用<span style='font-size:20px; font-weight:100;' >{$content["customer"]} </span>找回密码功能。 请点击以下链接重置您的密码（链接30分钟内有效）：</p><p>"
					."<a href='{$content["verifyUrl"]}'>点我重置密码</a></p>"
					."<p>如果点击以上没有反应，请将该{$content["verifyUrl"]}网址复制并粘贴到浏览器窗口中。</p>"
					."<p style='font-style:italic'>如果您并未发过此请求，则可能是因为其他用户在尝试重设密码时误输入了您的电子邮件地址而使您收到这封邮件，那么您可以放心的忽略此邮件，无需进一步采取任何操作。 </p>"	
					."<P>此致 </P>"
					."<P>{$content["customer"]} &nbsp;&nbsp;敬上 </P>"
					."<p>".date("Y-m-d H:i:s")."</p>";
			$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
			$mail->WordWrap = 80; // 设置每行字符串的长度
			$mail->IsHTML(true);
			$mail->Send();
			return common::errorArray(0, "发送成功", true);
		} catch (phpmailerException $e) {
			return common::errorArray(1, "发送失败", $e);
		}
	}
	
	//通知类邮件
	
	/**
	 * 密码修改通知
	 * @param 目标邮箱 $to
	 * @param 主题 $subject
	 * @param 内容 $content=array("nick_name"=>"用户昵称","customer"=>客户名称);
	 */
	function passwordChangeNotify($to,$subject,$content){
		try {
			require_once  'PHPMailer/class.phpmailer.php';
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
			$mail->SMTPAuth = true; //开启认证
			$mail->Port = 25;
			$mail->Host = $this->host;
			$mail->Username = $this->userName;
			$mail->Password = $this->passWord;
			$mail->FromName =  $content["customer"];
			$mail->From = $this->from;
			$mail->AddAddress($to);
			$mail->Subject =$subject;
			$currentTime = date('Y-m-d H:i:s');
			$mail->Body = "<p style='font-size:20px; font-weight:100;' >亲爱的  <span>{$content['nick_name']}</span> </p>"
					."<p>您于{$currentTime}，成功修改密码！</p>"
											."<P>此致</P>"
													."<P>{$content['customer']}&nbsp;&nbsp;敬上</P>"
															."<p>".date("Y-m-d H:i:s")."</p>";
			$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
			$mail->WordWrap = 80; // 设置每行字符串的长度
			$mail->IsHTML(true);
			$mail->Send();
			return common::errorArray(0, "发送成功", true);
		} catch (phpmailerException $e) {
			return common::errorArray(1, "发送失败", $e);
		}
	}
	
	/**
	 * 订单支付通知
	 * @param 目标邮箱 $to
	 * @param 主题 $subject
	 * @param 内容 $content=array("nick_name"=>"用户昵称","add_time"=>添加时间，"order_num"=>"订单编号");
	 */
	function orderPayNotify($to,$subject,$content){
		try {
			require_once  'PHPMailer/class.phpmailer.php';
			$mail = new PHPMailer(true);
			$mail->IsSMTP();
			$mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
			$mail->SMTPAuth = true; //开启认证
			$mail->Port = 25;
			$mail->Host = $this->host;
			$mail->Username = $this->userName;
			$mail->Password = $this->passWord;
			$mail->FromName =  '千界科技邮件通知系统';
			$mail->From = $this->from;
			$mail->AddAddress($to);
			$mail->Subject =$subject;
			$mail->Body = "<p style='font-size:20px; font-weight:100;' >亲爱的  <span>管理员</span> </p>"
					."<p>欢迎使用<span style='font-size:20px; font-weight:100;' > 千界科技邮件通知系统 </span>订单支付通知功能。 请您及时查看以下信息，并做出处理：</p>"
									."<p><font  style='font-size:20px; font-weight:100;color:red'>您的用户:  ".$content['nick_name']."  在  ".$content['add_time']."  已经完成订单  （编号：".$content['order_num']."） 支付！</font></p>"
											."<p>请您及时<a href='".$content['url']."'>查看详细信息</a>，并在后台做出相应的处理。</p>"
													."<P>此致</P>"
															."<P>安徽千界信息科技有限公司&nbsp;&nbsp;敬上</P>"
																	."<p>".date("Y-m-d H:i:s")."</p>";
			$mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; //当邮件不支持html时备用显示，可以省略
			$mail->WordWrap = 80; // 设置每行字符串的长度
			$mail->IsHTML(true);
			$mail->Send();
			return common::errorArray(0, "发送成功", true);
		} catch (phpmailerException $e) {
			return common::errorArray(1, "发送失败", $e);
		}
	}
	
}
/* End of file UtilMail.php */
/* Location: ./include/UtilMail.php */