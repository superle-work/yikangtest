<?php
/**
 * 模板消息接口
 * @author jianfang
 * @copyright changekeji
 * @version 1.0
 * @since: 2015-5-7
 */
/**
模板消息仅用于公众号向用户发送重要的服务通知，只能用于符合其要求的服务场景中，如信用卡刷卡通知，商品购买成功通知等。不支持广告等营销类消息以及其它所有可能对用户造成骚扰的消息。

关于使用规则，请注意：
1、所有服务号都可以在功能->添加功能插件处看到申请模板消息功能的入口，但只有认证后的服务号才可以申请模板消息的使用权限并获得该权限；
2、需要选择公众账号服务所处的2个行业，每月可更改1次所选行业；
3、在所选择行业的模板库中选用已有的模板进行调用；
4、每个账号可以同时使用15个模板。
5、当前每个模板的日调用上限为100000次。

关于接口文档，请注意：
1、模板消息调用时主要需要模板ID和模板中各参数的赋值内容；
2、模板中参数内容必须以".DATA"结尾，否则视为保留字；
3、模板保留符号"{{ }}"。
 */

class TemplateMessage{
    /**
     * 发送模板消息
     * @param integer $oid 订单编号
     * @param integer $type 订单状态：0待付款 1已付款 2已确认 3申请退款 4退款完成 5订单关闭 6订单完成
     * @param string $module 
     * @param array $describe 模板消息：订单详细消息， array('value'=>'黄先生，您预约的碧桂园3期，已预约成功，2月17日上午9点看房');
     */
    public static function RealSendTemplateMessage($oid, $type, $module, $userCondition, $reason='无原因', $configInfoResult){
        $tableName = $module.'_'.order;  // 拼出表名
        $specialTableName = array('donate' => 'donate_record');
        if(isset($specialTableName[$module])){
            $tableName = $specialTableName[$module];
        }
        // 根据模块的名字，拼接处模型的文件名和路径 1
        if(!class_exists('spModel')) include 'SpeedPHP/Core/spModel.php';
        $lib_order = new spModel();
        $resultOrder = $lib_order->findSql(" SELECT * FROM {$tableName} WHERE id = {$oid} ORDER BY id DESC ");
        if($module == 'duo'){
            //取出夺宝期号id
            $goodsListInfo = json_decode($resultOrder[0]['goods_list'], true);
            $issueId = $goodsListInfo[0]['issue_id'];
            $duoIssueInfo = $lib_order->findSql(" SELECT * FROM duo_issue WHERE id = {$issueId} ORDER BY id DESC ");
            $resultOrder = array_merge($resultOrder[0], $duoIssueInfo[0]);
        }
        if($reason) $resultOrder[0]['reason'] = $reason; 
        $orderResult['orderInfoResult'] = $module == 'duo' ?  $resultOrder : $resultOrder[0];
        $orderResult['moduleMessage'] = array("模块信息");
        $result = self::templateMessageNotify($type, $module, $orderResult, '',$userCondition);
        return $result;
    }
    
    /**
     * 设置所属行业
     * $industryId1 公众号模板消息所属行业编号 请打开连接查看行业编号 http://mp.weixin.qq.com/wiki/17/304c1885ea66dbedf7dc170d84999a9d.html#.E8.AE.BE.E7.BD.AE.E6.89.80.E5.B1.9E.E8.A1.8C.E4.B8.9A
     * $industryId2 公众号模板消息所属行业编号
     */
    public static function setIndustry($industryId1, $industryId2){
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/template/api_set_industry?access_token='.AccessToken::getAccessToken();
        $queryAction = 'POST';
        $template = array();
        $template['industry_id1'] = $industryId1;
        $template['industry_id2'] = $industryId2;
        $template = json_encode($template);
        return Curl::callWebServer($queryUrl, $template, $queryAction);
    }

    /**
     * 获得模板ID
     * $templateIdShort 模板库中模板的编号，有“TM**”和“OPENTMTM**”等形式
     *
     * @return array("errcode"=>0, "errmsg"=>"ok", "template_id":"Doclyl5uP7Aciu-qZ7mJNPtWkbkYnWBWVja26EGbNyk")  "errcode"是0则表示没有出错
     */
    public static function getTemplateId($templateIdShort){
        $result = self::setIndustry(1,4);
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/template/api_add_template?access_token='.AccessToken::getAccessToken();
        $queryAction = 'POST';
        $template = array();
        $template['template_id_short'] = $templateIdShort;
        $template = json_encode($template);
        $id = Curl::callWebServer($queryUrl, $template, $queryAction);
        return $id;
    }

    /**
     * 向用户推送模板消息
     * @param $data = array(
     *                  'first'=>array('value'=>'您好，您已成功消费。', 'color'=>'#0A0A0A'),
     *                  'keyword1'=>array('value'=>'巧克力', 'color'=>'#CCCCCC'),
     *                  'keyword2'=>array('value'=>'39.8元', 'color'=>'#CCCCCC'),
     *                  'keyword3'=>array('value'=>'2014年9月16日', 'color'=>'#CCCCCC'),
     *                  'keyword4'=>array('value'=>'欢迎再次购买。', 'color'=>'#173177')
     * );
     * @param $touser 接收方的OpenId。
     * @param $templateId 模板Id。在公众平台线上模板库中选用模板获得ID
     * @param $url URL
     * @return array("errcode"=>0, "errmsg"=>"ok", "msgid"=>200228332} "errcode"是0则表示没有出错
     *
     * 注意：推送后用户到底是否成功接受，微信会向公众号推送一个消息。
     */
    public static function sendTemplateMessage($data, $touser, $templateId, $url = ''){
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.AccessToken::getAccessToken();
        $queryAction = 'POST';
        $template = array();
        $template['touser'] = $touser;
        $template['template_id'] = $templateId;
        $template['url'] = $url;
        $template['data'] = $data;
        $template = json_encode($template);
        return Curl::callWebServer($queryUrl, $template, $queryAction);
    }
    
    /**
     * 向多用户推送模板消息
     * @param $data = array(
     *                  'first'=>array('value'=>'您好，您已成功消费。', 'color'=>'#0A0A0A'),
     *                  'keyword1'=>array('value'=>'巧克力', 'color'=>'#CCCCCC'),
     *                  'keyword2'=>array('value'=>'39.8元', 'color'=>'#CCCCCC'),
     *                  'keyword3'=>array('value'=>'2014年9月16日', 'color'=>'#CCCCCC'),
     *                  'keyword3'=>array('value'=>'欢迎再次购买。', 'color'=>'#173177')
     * );
     * @param $touser 接收方的OpenId 以逗号，拼接。
     * @param $templateId 模板Id。在公众平台线上模板库中选用模板获得ID
     * @param $url URL
     * @return array(array("errcode"=>0, "errmsg"=>"ok", "msgid"=>200228332},array("errcode"=>0, "errmsg"=>"ok", "msgid"=>200228332}) "errcode"是0则表示没有出错 多个返回数组组成
     *
     * 注意：推送后用户到底是否成功接受，微信会向公众号推送一个消息。
     */
    public static function sendMutileTemplateMessage($data, $tousers, $templateId, $url = ''){
    	$queryUrl = 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token='.AccessToken::getAccessToken();
    	$queryAction = 'POST';
    	$aTouser = explode(',', $tousers);
    	$return = array();
    	foreach($aTouser as $oneTouser){
    		$template = array();
    		$template['touser'] = $oneTouser;
    		$template['template_id'] = $templateId;
    		$template['url'] = $url;
    		$template['data'] = $data;
    		$template = json_encode($template);
    		$callback = Curl::callWebServer($queryUrl, $template, $queryAction);
    		$return[] = $callback;
    	}
    	return $return;
    }
    
    /**
     * 发送模板消息
     * @param 模板消息类型 $type 1：下单 2：货到付款下单 3：发货 4：退款申请 5：退款完成 6交易完成
     * @param string $module 商城模块 
     * @param array $orderResult 订单信息 和商城标题  openId, moduleMessage的数组
     * @param array $configInfoResult 配置信息 
     * @param array $userCondition 用户条件 
     */
    public static function templateMessageNotify($type,$module,$orderResult,$configInfoResult,$userCondition){
        $orderInfoResult = $orderResult['orderInfoResult'];
        $moduleMessage = $orderResult['moduleMessage'];
        //获取配置信息
        if(!$configInfoResult){
            if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
            if($module == 'balance'){//充值余额 : 获取制定模块的管理员的opendid
                $UtilConfig = new UtilConfig("{$module}_config");
            }else{//商城平台
                $UtilConfig = new UtilConfig("{$module}_config");
            }
            $configInfoResult = $UtilConfig->findConfigKeyValue();
        }
		if(!$configInfoResult['data']['order_template_notify']) return;//开启模板消息通知
        //公用的一些基本信息
        $user_url = $configInfoResult['data']['order_user_template_url'];
        $admin_url = $configInfoResult['data']['order_admin_template_url'];
        //获取发送模板的对象用户和和模板短id(模板库中的编号)
        $templateResult = self::getTemplate($type,$module,$orderInfoResult,$configInfoResult,$userCondition); 
        $touserList = explode(',', rtrim($templateResult['touser'], ','));
        $templateId = $templateResult['templateId'];
        $data = $templateResult['data'];
        //构建订单商品名称
        if(isset($data['messageTemplateToAdmin'])){  // 退货模板消息发送
            $dataToAdmin = $data['messageTemplateToAdmin'];
            $dataToUser = $data['messageTemplateToUser'];
            $touser = array_shift($touserList);
            //发送给用户
            $result = TemplateMessage::sendTemplateMessage($dataToUser, $touser, $templateId['user'], $user_url);
            //发送给管理员
            foreach($touserList as $touser){
                $result = TemplateMessage::sendTemplateMessage($dataToAdmin, $touser, $templateId['admin'], $admin_url);
            }
        }else{
            $i = 0;
            foreach($touserList as $touser){
                $url = $i == 0 ? $user_url : $admin_url;
                $result = TemplateMessage::sendTemplateMessage($data, $touser, $templateId, $url);
                $i ++;
            }
        }
        if($result) return common::errorArray(0, "模板消息发送成功", $result);
    }
    
    
    
    /**
     * 获取订单中的商品名称
     * @param string $module
     * @param array $orderInfoResult
     * @return string $goodsString
     */
    private function getGoodsName($module,$orderInfoResult){
        if($module == 'store' || $module == 'dining' || $module == 'duo' || $module == 'register'){//百货商城、订餐商城
            $goodsList = json_decode($orderInfoResult['goods_list'], true);
            foreach ($goodsList as $goods){
                $goodsString .= $goods['goods_name']."×".$goods['count'] . ",";
            }
        }elseif($module == 'trip' || $module == 'group' || $module == 'hotel' || $module == 'bargain' || $module == 'rob' || $module == 'photography'){//旅游商城、酒店、砍价、团购、抢购
            if($module == 'group' || $module == 'bargain' || $module == 'acti_bargain'){
                $goodsString = $orderInfoResult['goods_name'];
            }else if($module == 'trip'){
                $goodsString = $orderInfoResult['goods_list']['name'];
            }else if($module == 'hotel'){
                $goodsString = $orderInfoResult['hotel_name']."({$orderInfoResult['room_name']})";
            }else if($module == 'rob'){
                $result = json_decode($orderInfoResult['goods_info'], true);
                $goodsString = $result['name']."×".$result['goods_count'];
            }else if($module == 'donate'){
                $goodsString = $orderInfoResult['product_info']->name;
            }else if($module == 'coupon'){
                $goodsString = $orderInfoResult['coupon_name'];
            }else if($module == 'photography'){
                $result = json_decode($orderInfoResult['goods_list'], true);
                $goodsString = $result[0]['name'];
            }
        }
        $goodsName = empty($goodsString) ? $orderInfoResult['order_name'] : $goodsString;
        if(empty($goodsString)){
            $goodsString = '获取商品名失败';
        }
        return $goodsString;
    }
    
    /**
     * 根据不同的类型获取不同的模板信息
     * @param int $type 1：下单 2：货到付款下单 3：发货 4：退款申请 5：退款完成 6交易完成
     * @param string $module
     * @param array $orderInfoResult
     * @param array $configInfoResult
     * @param array $userCondition
     * @return array $template
     */
    private function getTemplate($type,$module,$orderInfoResult,$configInfoResult,$userCondition){
        date_default_timezone_set('Asia/Shanghai');
        $time = common::getTime();
        if(!class_exists(lib_user)) include 'model/base/lib_user.php';
        $lib_user = new lib_user();
        if(empty($orderInfoResult['address']) && empty($orderInfoResult['address_text'])){  // 收获地址
            $address = '虚拟商品';
        }else{
            $address = $orderInfoResult['address_text'] ? $orderInfoResult['address_text'] : $orderInfoResult['address']['call_name'].'--'.$orderInfoResult['address']['phone'].'--'.$orderInfoResult['address']['address'];
        }
        $goodsName = rtrim(self::getGoodsName($module,$orderInfoResult),',');   // 订单商品
        if($module == 'agent'){  // 订单金额
            $totalPrice = $orderInfoResult['real_money'];  
        }else{
            $totalPrice = $orderInfoResult['total_price'];
            $totalPrice = empty($totalPrice) ? '获取商品价格失败' : $totalPrice ;
        }
        if($userCondition){   // 发送对象
            $userResult = $lib_user->findUser($userCondition);
            $touser = $userResult['data']['open_id'].',';
        }
        $moduleInfo = array( 
            'coupon' => '(优惠券)', 'balance'=> '(余额充值)', 'dining' => '(订餐)', 'donate' => '(捐赠)', 'hotel' => '(酒店)',
            'register' => '(报名)', 'store' => '(商城)', 'photography' => '(微摄影)', 'duo' => '(夺宝奇兵)', 'bargain' => '(砍价)',
            'rob' => '(限时抢购)' , 'group' => '(团购)'
        );
        $suffix = $moduleInfo[$module];
        $touser .= $configInfoResult['data']['order_template_admin'];
        $orderNumber = isset($orderInfoResult['order_num']) ? $orderInfoResult['order_num'] : $orderInfoResult['id']; // 订单号
        switch($type){
            case 1://微信支付下单
            case 2:
                if($type == 1){
                    $remark = '(微信支付订单)';
                }else{
                    $remark = '(货到付款订单)';
                }
				$templateIdShort['admin'] = 'OPENTM406438518';//模板库中的编号
                $templateIdShort['user'] = 'OPENTM406438518';//模板库中的编号
                $messageTemplateToAdmin = array('尊敬的管理员，您有一条新订单。', $goodsName, $time, $totalPrice, $address,"备注信息：用户新订单支付成功，请及时处理！".$suffix);
                $messageTemplateToUser = array('恭喜您，支付成功', $goodsName, $time, $totalPrice, $address,"备注信息：我们已收到你的订单，正在处理中，谢谢选购！".$suffix);
                $data = array('messageTemplateToUser'=>$messageTemplateToUser, 'messageTemplateToAdmin'=>$messageTemplateToAdmin);
                // 处理募捐与夺宝的心订单
                if($module == 'duo'){
                    $templateIdShort = 'OPENTM206854010'; //模板库中的编号
                    $data = array('尊敬的会员，您成功参与夺宝活动成功', $goodsName , $orderInfoResult['lottery_time'], '详情请登录官网查看'.$suffix);
                }
                break;
            case 3://发货
            	$templateIdShort['admin'] = 'OPENTM202243318';//模板库中的编号
                $templateIdShort['user'] =  'OPENTM202243318';//模板库中的编号
                $expressCompany = isset($orderInfoResult['express_company']) ? $orderInfoResult['express_company'] : '服务或虚拟商品无需快递';
                $expressNumber = isset($orderInfoResult['express_number']) ? $orderInfoResult['express_number'] : '服务或虚拟商品无快递单号';
                $messageTemplateToAdmin = array( '恭喜您，发货成功！', $goodsName, $expressCompany,$expressNumber, $address,"备注信息：快去处理其它订单吧".$suffix);
                $messageTemplateToUser = array( '您的货物已发货，我们正在火速送到您的手上！', $goodsName, $expressCompany,$expressNumber, $address,"备注信息：请您耐心等待".$suffix);
                $data = array('messageTemplateToUser'=>$messageTemplateToUser, 'messageTemplateToAdmin'=>$messageTemplateToAdmin);
                break;
            case 4://退款申请 
                $templateIdShort['admin'] = 'OPENTM407277862';//模板库中的编号
                $templateIdShort['user'] = 'OPENTM207867468';//模板库中的编号
                $orderNumber = isset($orderInfoResult['order_num']) ? $orderInfoResult['order_num'] : $orderInfoResult['id'];
                $orderPrice = !empty($totalPrice) ? $totalPrice : $orderInfoResult['points'];
                $messageTemplateToAdmin = array('您有一位用户下单方申请退款，等待商家处理', $orderPrice, $goodsName ,$orderNumber,  $orderInfoResult['reason'],'请及时联系用户，避免不必要的纠纷'.$suffix);
                $messageTemplateToUser = array('您已申请退款，等待商家确认信息', $orderPrice, $goodsName ,$orderNumber,  $orderInfoResult['reason'],'退款申请已提交成功，请等待商家处理'.$suffix);
                $data = array('messageTemplateToUser'=>$messageTemplateToUser, 'messageTemplateToAdmin'=>$messageTemplateToAdmin);
                break;
            case 5://退款完成 
                $templateIdShort = 'OPENTM406199362';//模板库中的编号
                $payInfo = array(0=>'未选择', 1=>'微信支付', 2=>'支付宝支付', 3 => '其他付款方式');
                $payMethod = $payInfo[$orderInfoResult['pay_method']];
                $data = array('退款处理已经完成，请等待银行处理', $goodsName, $orderNumber, $totalPrice, $payMethod, $time, '如有疑问请联系，aiShop@126.com'.$suffix);
                break;
            case 6://交易完成
                if($module == 'donate'){
                    $projectInfo = json_decode($orderInfoResult['project_info'], true);
                    $projectName = !empty($projectInfo['name']) ? $projectInfo['name'] : '捐赠项目信息获取失败' ;
                    $templateIdShort = 'OPENTM406010569'; //模板库中的编号
                    $data = array('捐款成功，感谢您对慈善事业的关心与支持', $projectName, $totalPrice, $time ,'让爱心涓涓流淌，温暖世界的每个角落--'.$suffix);
                }else if($module == 'duo'){
                    
                }else{
                	$templateIdShort['admin'] = 'OPENTM207287582';//模板库中的编号
                	$templateIdShort['user'] = 'OPENTM207287582';//模板库中的编号
                    $messageTemplateToAdmin = array('订单交易完成',$totalPrice, $goodsName, $orderNumber, '备注信息：快去处理其它订单吧'.$suffix );
                    $messageTemplateToUser = array('订单交易完成，感谢您的支持',$totalPrice, $goodsName, $orderNumber, '备注信息：订单交易完成，感谢您的支持与信任！'.$suffix );
                    $data = array('messageTemplateToUser'=>$messageTemplateToUser, 'messageTemplateToAdmin'=>$messageTemplateToAdmin);
				}
                break;
            default:
                $templateIdShort = '';//模板库中的编号
                $touser = '';
                break;
        }
        if(isset($data['messageTemplateToAdmin'])){ // 微模板信心添加木块标记如 来自募捐的　：……（募捐）
            foreach ($data as $value){
                $result = array_pop($value);
                $templateDescribe = $result.$suffix;
                array_push($value, $templateDescribe);
            }
            $data['messageTemplateToUser'] = self::getTemplateData($data['messageTemplateToUser']);
            $data['messageTemplateToAdmin'] = self::getTemplateData($data['messageTemplateToAdmin']);
        }else{ 
            $data = self::getTemplateData($data);
        }
        if(is_array($templateIdShort)){ //退货申请模板id不同
            $templateId['admin'] = self::getSetTemplateId($type, $module, $templateIdShort['admin']);
            $templateId['user'] = self::getSetTemplateId($type, $module, $templateIdShort['user']);
        }else{
            $templateId = self::getSetTemplateId($type, $module, $templateIdShort);
        }
        return array('touser' => $touser,'templateId' => $templateId,'data' => $data);
    }

    
    /**
     * 根据类型和模块获取templateId
     * @param int $type 1：下单 2：货到付款下单 3：发货 4：退款申请 5：退款完成 6交易完成
     * @param string $templateIdShort
     * @param string $module
     */
    private function getSetTemplateId($type,$module,$templateIdShort){
        $rawTemplateIdList = file_get_contents('include/wechatUtil/data/template_id.json');
        $templateIdList = json_decode($rawTemplateIdList,true);
        if($type == 1 && ($module == 'balance' || $module == 'coupon' || $module == 'duo' || $module == 'donate' || $module == 'register')){//特殊的模块
            if(!isset($templateIdList[$type.$module])){ //不存在该templateId
                $templateId = self::setTemplateId($type, $module, $templateIdList, $templateIdShort);
            }else{ //存在该templateId
                $templateId = $templateIdList[$type.$module];
            }
        }else if($type == 6 && $module == 'donate'){
            if(isset($templateIdList[$type.$module])){ //存在该templateId
                $templateId = $templateIdList[$type.$module];
            }else{//存在该templateId
                $templateId = self::setTemplateId($type, $module, $templateIdList, $templateIdShort);
            }
           
        }elseif(!$templateIdList[$type]){//不存在该templateId
            $templateId = self::setTemplateId($type, $module, $templateIdList, $templateIdShort);
        }else{//存在templateId
            $templateId = $templateIdList[$type];
        }
        return $templateId;   
    }
    
    /**
     * 设置templateId
     * @param int $type
     * @param string $module
     * @param array $templateIdList
     * @param array $templateIdShort
     */
    private function setTemplateId($type,$module,$templateIdList,$templateIdShort){
        $result = self::getTemplateId($templateIdShort);
        if($result['errcode'] == 0){
            $templateId = $result['template_id'];
            if($type == 1 && ($module == 'duo' || $module == 'donate')){
                $templateIdList[$type.$module] = $templateId;
            }else if($type == 6 && ($module == 'donate')){
                $templateIdList[$type.$module] = $templateId;
            }else{
                $templateIdList[$type] = $templateId;
            }
            $f = fopen('include/wechatUtil/data/template_id.json', 'w+');
            fwrite($f, json_encode($templateIdList));
            fclose($f);
        }else{
        	$templateIdList['errcode'] = $result['errcode'];
        	$templateIdList['errmsg'] = $result['errmsg'];
        	$f = fopen('include/wechatUtil/data/template_error_info.json', 'w+');
            fwrite($f, json_encode($templateIdList));
            fclose($f);
        }
        
        return $templateId;
    }
    
    /**
     * 获取模板信息data
     * @param array $data
     */
    private function getTemplateData($data){
        $values['first']['value'] = reset($data);
        $values['first']['color'] = "#32CD32";
        $values['remark']['value'] = end($data);
        $values['remark']['color'] = "#FF5C00";
        array_pop($data);
        array_shift($data);
        foreach($data as $key=>$value){
            $values['keyword'.($key+1)]['value'] = $value;
			$values['keyword'.($key+1)]['color'] = "#173177";
        }       
        return $values;
    }
}