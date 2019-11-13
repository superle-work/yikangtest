<?php

if(!class_exists('base_model')) require 'include/base/model/base_model.php';

if(!class_exists('m_plugin_sms')) require 'model/plugin/table/m_plugin_sms.php';

if(!class_exists('m_plugin_sms_pay')) require 'model/plugin/table/m_plugin_sms_pay.php';

if(!class_exists('m_plugin_sms_template')) require 'model/plugin/table/m_plugin_sms_template.php';



/**

 * 提供短信管理服务

 * @name lib_sms.php

 * @package cws

 * @category model

 * @link http://www.chanekeji.com

 * @author linli

 * @version 2.0

 * @copyright CHANGE INC

 * @since 2016-08-05

 */

class lib_sms extends base_model{

    private $m_plugin_sms;

    private $m_plugin_sms_pay;

    private $m_plugin_sms_template;

    

    function __construct(){

        parent::__construct();

        $this->m_plugin_sms = new m_plugin_sms();

        $this->m_plugin_sms_pay = new m_plugin_sms_pay();

        $this->m_plugin_sms_template = new m_plugin_sms_template();

    }

    

	/**

	 * 发送短信

	 * @param string $to: 短信接收手机号码集合,用英文逗号分开,如 '13810001000,13810011001',最多一次发送100个。

	 * @param array $datas：内容数据，需定义成数组方式，如模板中有两个参数，定义方式为array（'3456','123'）。

	 * @param array $templateInfo: 查找模板的条件

	 */

	function sendTemplateSMS($to,$datas,$templateInfo,$phone){

	    try{

    		//读取短信配置

    		$smsResult = $this->m_plugin_sms->find(array('is_use'=>1,'plate'=>2));

    		if($smsResult['count'] <= 0){//短信条数不足

    		    $log = Log::getInstance();

    		    $log->log("服务商短信余额不足",'ERROR');

    		    return common::errorArray(1, '短信已用完', $smsResult);

    		}

			

    		if($smsResult['plate'] == 0){//容联通讯

    			if(!class_exists('SMS')) include "include/SMS.php";

	    		$sms = new SMS($smsResult['server_ip'],$smsResult['server_port'],$smsResult['server_soft_version']);

	    		$sms->setAccount($smsResult['sid'],$smsResult['token']);

	    		$sms->setAppId($smsResult['appid']);

				//发送模板短信

				$templateResult = $this->m_plugin_sms_template->find(array('is_use'=>1));//获取模板数据

	    		$result = $sms->sendTemplateSMS($to,$datas,$templateResult['template_id']);

				if($result == NULL || $result->statusCode != 0) {//发送失败

	    		    $errorData = $result ? $result->statusMsg : false;

	    		    $log = Log::getInstance();

	    		    $log->log('发送短信失败','ERROR');

	    			return common::errorArray(1, "短信发送失败", $errorData);

	    		}else{//发送成功

	    		    //加入记录

	    		    $templateData = $this->getTemplateData($datas);

	    		    $recordResult = $this->addRecord(array(

	    		        "type" => $templateResult['type'],

	    		        "send_phone" => $to,

	    		        "content" => strtr($templateResult['content'],$templateData)

	    		    ));

	    		    $this->minusSMS($smsResult['count']);//减去1条

	    	        return common::errorArray(0, "短信发送成功", $result->TemplateSMS);

	    		}

    		}elseif($smsResult['plate'] == 1){	//阿里云通讯

				//发送模板短信

				$templateResult = $this->m_plugin_sms_template->find(array('is_use'=>1,'plate'=>1,'type'=>$templateInfo['type']));//获取模板数据

				if(!class_exists(SMS)) include_once "include/aliyunSMS/api_sms/SMS.php";

				$accessKeyId = $smsResult['accessKeyId'];

				$accessKeySecret = $smsResult['accessKeySecret'];	

				$signName = $smsResult['signName'];

				$templateCode = $templateResult['templateCode']; 

				$phoneNumbers = $to; 

				$templateKeyValue = $templateResult['templateParam'];

				$templateKey = explode(',', $templateKeyValue); 

				$templateParam = array();

				for($i = 0; $i < count($templateKey); $i ++){

					for($j = 0; $j < count($datas); $j++){

						if($i == $j){

							$templateParam[$templateKey[$i]] = $datas[$j];

						}	

					}

				}

				$outId = $this->generateNum(rand(1000, 9999));

    			$sms = new SMS($accessKeyId,$accessKeySecret);

				$result = $sms->sendSms($signName, $templateCode, $phoneNumbers, $templateParam, $outId);

    			if($result->Code != 'OK') {//发送失败

	    		    $log = Log::getInstance();

	    		    $log->log('发送短信失败','ERROR');

	    			return common::errorArray(1, "短信发送失败", $result);

	    		}else{//发送成功

	    			$templateData = array();

	    			foreach($templateParam as $key=>$value){

	    				$templateData['${'.$key.'}'] = $value;

	    			}

					$row = array(

	    		        "type" => $templateResult['type'],

	    		        "send_phone" => $to,

	    		        "content" => strtr($templateResult['templateCount'],$templateData),

	    		        Code => $result->Code,

	    		        BizId => $result->BizId,

	    		        RequestId => $result->RequestId,

	    		        Message => $result->Message

	    		    );

	    		    //加入记录

	    		    $recordResult = $this->addRecord($row);

	    		    $this->minusSMS($smsResult['count']);//减去1条

	    	        return common::errorArray(0, "短信发送成功", $result);

	    		}

    		}if($smsResult['plate'] == 2){//腾讯云

    			//todo

    		}

    	}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}



	/**

	 * 烽火云发送短信

	 * @param $data:array("phone"=>"13003081550(手机号)","time"=>"45(有效时间)")

	 * @param $sendTime //时定时发送，输入格式YYYY-MM-DD HH:mm:ss的日期值

	 * @param $id 短信模板在数据库中主键id

	 */

	

	function sendFengHuoYunSMS($id,$data,$sendTime=''){

		//读取短信配置

		$smsResult = $this->m_plugin_sms->find(array('is_use'=>1,'plate'=>2));
		dump($smsResult);
		if($smsResult['count'] <= 0){//短信条数不足

		    $log = Log::getInstance();

		    $log->log("服务商短信余额不足",'ERROR');

		    return common::errorArray(1, '短信已用完', $smsResult);

		}

		

        $post_data = array();

		$number = '01234567890123456789';

		$data['code'] = substr(str_shuffle($number), 0,6);//获取验证码	

		if(!class_exists('lib_sms')) include "model/plugin/lib_sms.php";

	    $lib_sms = new lib_sms();

		$template = $lib_sms->findTemplate(array("plate"=>2,"id"=>$id));//获取短信模板

		//获取烽火云账号信息

		$sms = $lib_sms->findSMS(array("plate"=>2));

		$i = -1;

		foreach($data as $key => $value){

			$i = $i+1;

	        $newData['['.$i.']'] = $value;

	    }

		$sms['content'] = strtr($template['data']['content'],$newData);

		$signString = $sms['data']['account'].$sms['data']['password'].date("YmdHis",time());

		$sign = md5($signString);

		$post_data = array(

			"userid" => $sms['data']['userid'],

			"account" => $sms['data']['account'],

			"password" => $sms['data']['password'],

			"content" => strtr($template['data']['content'],$newData),

			"mobile" => $data['phone'],

			"sign" => $sign,

			"sendtime" => $sendTime,

		);

		

		$url = 'http://114.55.253.103:8888/sms.aspx?action=send';

        $result = Curl::sendWebRequest($url,'post',$post_data);

		$result = simplexml_load_string($result);

		if($result->returnstatus == 'Faild') {//发送失败

	    	$log = Log::getInstance();

	    	$log->log('发送短信失败','ERROR');

	    	return common::errorArray(1, "短信发送失败");

	    }else{//发送成功

	    	session_start($data['time']*1000);



	        $_SESSION['phone_register_number'] = $data['code'];

		    $recordResult = $this->addRecord(array(

		        "type" => 0,

		        "verify_result"=>0,

		        "send_phone" => $data['phone'],

		        "content" => strtr($template['data']['content'],$newData)

		    ));

		    $this->minusSMS($result->successCounts);//减去1条

	        return common::errorArray(0, "短信发送成功");

	    }

    }

	

	

	/**

	 * 生成随机数

	 */

	private function generateNum($code){

		$sub_str = 'SP';//商品

		$date = date ( 'YmdHis', time () );

		return $sub_str.$date.$code;

	}

	

	/**

	 * 查询sms表

	 * @param array $condition

	 * @return $result

	 */

	function findSMS($condition){

		try{

			$result = $this->m_plugin_sms->find($condition);

			if($result){

				return common::errorArray(0, "查询成功", $result);

			}else{

				return common::errorArray(1, "查询为空", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 减去1个短信条数

	 * @param int 总短信数

	 * @return $result

	 */

	function minusSMS($quantity){

		if($quantity > 0){

		    $result = $this->m_plugin_sms->decrField(null, 'count');

			if($result){

				return common::errorArray(0, "减去成功", true);

			}else{

				return common::errorArray(1, "减去失败", true);

			}

		}else{

			return common::errorArray(1, "短信余额不足", false);

		}

	}

	

	/**

	 * 短信配置列表

	 * @return $result

	 */

	function SMSList(){

		try{

			$result = $this->m_plugin_sms->findAll();

			if($result){

				return common::errorArray(0, "查询成功", $result);

			}else{

				return common::errorArray(1, "查询为空", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 修改短信配置

	 * @param array $condition

	 * @param array $SMS

	 * @return $result

	 */

	function updateSMS($condition,$SMS){

		try{

			$result = $this->m_plugin_sms->update($condition,$SMS);

			if($result){

				return common::errorArray(0, "更新成功", $result);

			}else{

				return common::errorArray(1, "更新失败", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 添加短信配置

	 * @param array $SMS

	 * @return $result

	 */

	function addSMS($SMS){

		try{

			$SMS['add_time'] = common::getTime();

			$result = $this->m_plugin_sms->create($SMS);

			if($result){

				return common::errorArray(0, "添加成功", $result);

			}else{

				return common::errorArray(1, "添加失败", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 删除短信配置

	 * @param array $condition

	 * @return $result

	 */

	function deleteSMS($condition){

		try{

			$result = $this->m_plugin_sms->delete($condition);

			if($result){

				return common::errorArray(0, "删除成功", $result);

			}else{

				return common::errorArray(1, "删除失败", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	//-------------------短信发送记录-----------------------

	

	/**

	 * 添加短信发送记录

	 * @param array $row

	 * @return $result

	 */

	function addRecord($row){

	    if(!class_exists('m_plugin_sms_record')) include 'model/plugin/table/m_plugin_sms_record.php';

		$m_sms_record = new m_plugin_sms_record();

		try{

			$row['add_time'] = common::getTime();

			$row['send_ip'] = common::getRealIp();

			$row['verify_result'] = 0;//默认未知验证结果

			$result = $m_sms_record->create($row);

			if($result){

				return common::errorArray(0, "删除成功", $result);

			}else{

				return common::errorArray(1, "删除失败", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 删除记录

	 * @param string $ids 1,2,3,4

	 * @return $result

	 */

	function deleteRecord($ids){

		if(!class_exists('m_plugin_sms_record')) include 'model/plugin/table/m_plugin_sms_record.php';

		$m_sms_record = new m_plugin_sms_record();

		try{

			$sql = "DELETE FROM m_plugin_sms_record WHERE id IN($ids)";

			$result = $m_sms_record->runSql($sql);

			if($result){

				return common::errorArray(0, "删除成功", $result);

			}else{

				return common::errorArray(1, "删除失败", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 更新记录

	 * @param array $condition

	 * @param array $row

	 * @return $result

	 */

	function updateRecord($condition,$row){

		if(!class_exists('m_plugin_sms_record')) include 'model/plugin/table/m_plugin_sms_record.php';

		$m_sms_record = new m_plugin_sms_record();

		try{

			$result = $m_sms_record->update($condition,$row);

			if($result){

				return common::errorArray(0, "更新成功", $result);

			}else{

				return common::errorArray(1, "更新失败", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 分页查询发送记录

	 * @param array $page

	 * @param array $conditionList

	 * @param string $sort

	 * @param array $orList

	 * @return $result

	 */

	function pagingRecord($page,$conditionList,$sort = '',$orList = null){

		if(!class_exists('m_plugin_sms_record')) include 'model/plugin/table/m_plugin_sms_record.php';

		$m_sms_record = new m_plugin_sms_record();

		$result = $m_sms_record->paging($page,$conditionList,$sort,$orList = null);

		return $result;

	}

	

	//----------------------充值记录-----------------------

	/**

	 * 添加短信充值记录

	 * @param array $row

	 * @return $result

	 */

	function addPay($row){

		try{

			$result = $this->m_plugin_sms->incrField(null, 'count',$row['count']);

			$row['add_time'] = common::getTime();

			$result = $this->m_plugin_sms_pay->create($row);

			if($result){

				return common::errorArray(0, "添加成功", $result);

			}else{

				return common::errorArray(1, "添加失败", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 删除充值记录

	 * @param string $ids 1,2,3,4

	 * @return $result

	 */

	function deletePay($ids){

		try{

			$sql = "DELETE FROM plugin_sms_pay WHERE id IN($ids)";

			$result = $this->m_plugin_sms_pay->runSql($sql);

			if($result){

				return common::errorArray(0, "删除成功", $result);

			}else{

				return common::errorArray(1, "删除失败", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 更新充值记录

	 * @param array $condition

	 * @param array $row

	 * @return $result

	 */

	function updatePay($condition,$row){

		try{

			$result = $this->m_plugin_sms_pay->update($condition,$row);

			if($result){

				return common::errorArray(0, "更新成功", $result);

			}else{

				return common::errorArray(1, "更新失败", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 分页查询充值记录

	 * @param array $page

	 * @param array $conditionList

	 * @param string $sort

	 * @param array $orList

	 * @return $result

	 */

	function pagingPay($page,$conditionList,$sort = '',$orList = null){

		$result = $this->m_plugin_sms_pay->paging($page,$conditionList,$sort = '',$orList = null);

		return $result;

	}

	

	//--------------------------短信模板配置--------------------------

	/**

	 * 添加短信模板

	 * @param array $row

	 * @return $result

	 */

	function addTemplate($row){

	    try{

	        $row['add_time'] = common::getTime();

	        $result = $this->m_plugin_sms_template->create($row);

	        if($result){

	            return common::errorArray(0, "添加成功", $result);

	        }else{

	            return common::errorArray(1, "添加失败", $result);

	        }

	    }catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 删除短信模板

	 * @param string $ids 1,2,3,4

	 * @return $result

	 */

	function deleteTemplate($ids){

	    try{

	        $sql = "DELETE FROM plugin_sms_template WHERE id IN($ids)";

	        $result = $this->m_plugin_sms_template->runSql($sql);

	        if($result){

	            return common::errorArray(0, "删除成功", $result);

	        }else{

	            return common::errorArray(1, "删除失败", $result);

	        }

	    }catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 更新短信模板

	 * @param array $condition

	 * @param array $row

	 * @return $result

	 */

	function updateTemplate($condition,$row){

	    try{

	        $row['add_time'] = common::getTime();

	        $result = $this->m_plugin_sms_template->update($condition,$row);

	        if($result){

	            return common::errorArray(0, "更新成功", $result);

	        }else{

	            return common::errorArray(1, "更新失败", $result);

	        }

	    }catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 查询单条短信模板

	 * @param array $condition

	 * @return $result

	 */

	function findTemplate($condition,$sort,$fields){

		try{

			$result = $this->m_plugin_sms_template->find($condition,$sort,$fields);

			if($result){

				return common::errorArray(0, "查询成功", $result);

			}else{

				return common::errorArray(1, "查询为空", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 查询所有短信模板

	 * @param array $condition

	 * @return $result

	 */

	function findAllTemplate($condition,$sort,$fields,$limit){

		try{

			$result = $this->m_plugin_sms_template->findAll($condition,$sort,$fields,$limit);

			if($result){

				return common::errorArray(0, "查询成功", $result);

			}else{

				return common::errorArray(1, "查询为空", $result);

			}

		}catch (Exception $ex){return common::errorArray(1, "数据库操作失败", $ex);}

	}

	

	/**

	 * 分页查询短信模板

	 * @param array $page

	 * @param array $conditionList

	 * @param string $sort

	 * @param array $orList

	 * @return $result

	 */

	function pagingTemplate($page,$conditionList,$sort = '',$orList = null){

	    $result = $this->m_plugin_sms_template->paging($page,$conditionList,$sort = '',$orList = null);

	    return $result;

	}

	



	/**

	 * 获取发送模板的data数组

	 * @param array $data

	 * @return array $newData

	 */

	private function getTemplateData($data){

	    $newData = array();

	    foreach($data as $key => $value){

	        $newData['['.$key.']'] = $value;

	    }

	    return $newData;

	}

}