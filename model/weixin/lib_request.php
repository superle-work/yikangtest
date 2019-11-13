<?php
if(!class_exists('base_model')) require 'include/base/model/base_model.php';
if(!class_exists('lib_response')) require 'model/weixin/lib_response.php';
/**
 * 微信请求分发
 * @name lib_request.php
 * @package cws
 * @category model
 * @link http://www.chanekeji.com
 * @author jianfang
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-05
 */
class lib_request extends base_model{
    /**
     * @descrpition 分发请求
     * @param $request
     * @return array|string
     */
    public  function switchType(&$request){
        $data = array();
        switch ($request['msgtype']) {
            //事件
            case 'event':
                $request['event'] = strtolower($request['event']);
                switch ($request['event']) {
                    //关注
                    case 'subscribe':
                        //二维码关注
                        if(isset($request['eventkey']) && isset($request['ticket'])){
                            $data = $this->eventQrsceneSubscribe($request);
                        //普通关注
                        }else{
                            $data = $this->eventSubscribe($request);
                        }
                        break;
                    //扫描二维码
                    case 'scan':
                        $data = $this->eventScan($request);
                        break;
                    //地理位置
                    case 'location':
                        $data = $this->eventLocation($request);
                        break;
                    //自定义菜单 - 点击菜单拉取消息时的事件推送
                    case 'click':
                        $data = $this->eventClick($request);
                        break;
                    //自定义菜单 - 点击菜单跳转链接时的事件推送
                    case 'view':
                        $data = $this->eventView($request);
                        break;
                    //自定义菜单 - 扫码推事件的事件推送
                    case 'scancode_push':
                        $data = $this->eventScancodePush($request);
                        break;
                    //自定义菜单 - 扫码推事件且弹出“消息接收中”提示框的事件推送
                    case 'scancode_waitmsg':
                        $data = $this->eventScancodeWaitMsg($request);
                        break;
                    //自定义菜单 - 弹出系统拍照发图的事件推送
                    case 'pic_sysphoto':
                        $data = $this->eventPicSysPhoto($request);
                        break;
                    //自定义菜单 - 弹出拍照或者相册发图的事件推送
                    case 'pic_photo_or_album':
                        $data = $this->eventPicPhotoOrAlbum($request);
                        break;
                    //自定义菜单 - 弹出微信相册发图器的事件推送
                    case 'pic_weixin':
                        $data = $this->eventPicWeixin($request);
                        break;
                    //自定义菜单 - 弹出地理位置选择器的事件推送
                    case 'location_select':
                        $data = $this->eventLocationSelect($request);
                        break;
                    //取消关注
                    case 'unsubscribe':
                        $data = $this->eventUnsubscribe($request);
                        break;
                    //群发接口完成后推送的结果
                    case 'masssendjobfinish':
                        $data = $this->eventMassSendJobFinish($request);
                        break;
                    //模板消息完成后推送的结果
                    case 'templatesendjobfinish':
                        $data = $this->eventTemplateSendJobFinish($request);
                        break;
                    default:
                        return Msg::returnErrMsg(MsgConstant::ERROR_UNKNOW_TYPE, '收到了未知类型的消息', $request);
                        break;
                }
                break;
            //文本
            case 'text':
                $data = $this->text($request);
                break;
            //图像
            case 'image':
                $data = $this->image($request);
                break;
            //语音
            case 'voice':
                $data = $this->voice($request);
                break;
            //视频
            case 'video':
                $data = $this->video($request);
                break;
            //小视频
            case 'shortvideo':
                $data = $this->shortvideo($request);
                break;
            //位置
            case 'location':
                $data = $this->location($request);
                break;
            //链接
            case 'link':
                $data = $this->link($request);
                break;
            default:
                return ResponsePassive::text($request['fromusername'], $request['tousername'], '收到未知的消息，我不知道怎么处理');
                break;
        }
        return $data;
    }

    /**
     * @descrpition 文本 自定义回复
     * @param $request
     * @return array
     */
    private  function text(&$request){
        if(!class_exists('lib_response')) include 'model/weixin/lib_response.php';
        $lib_message = new lib_response();
        $useResult =  $lib_message->fileConfigObj->getFileConfigValue('is_use');
        if($useResult['data'] ){
    		//关键词自动回复
    		$ruleListResult = $lib_message->findAllRule(array('is_use'=>1,"is_match"=>1));
    		if($ruleListResult['errorCode'] == 0){
    			foreach ($ruleListResult['data'] as $rule){
    				$match = preg_match("/{$rule['keywords']}/", $request['content']);//正则匹配
    				if($match){
    					if($rule['type'] == 'text' && $rule['m_is_use'] == 1){
    						return ResponsePassive::text($request['fromusername'], $request['tousername'], $rule['content']);
    					}
    					if($rule['type'] == 'news' && $rule['m_is_use'] == 1){//图文
    						$newsResult = Media::getMedia($rule['media_id']);
    						$newsArray = $newsResult['news_item'];
							foreach ($newsArray as &$item){
					            $itemList[] = ResponsePassive::newsItem($item['title'], $item['digest'], $item['thumb_url'], $item['url']);
					        }
							return ResponsePassive::news($request['fromusername'], $request['tousername'], $itemList);
    					}
    					if($rule['type'] == 'my_news' && $rule['m_is_use'] == 1){//自定义图文
    					    $newsArray = json_decode($rule['content'],true);
    					    if(count($newsArray)){
    					        foreach ($newsArray as &$item){
    					            $item['picUrl'] = substr_replace($item['picUrl'], ROOT_URL, 0,1);//相对路径改为决定路径
    					            $itemList[] = ResponsePassive::newsItem($item['title'], $item['description'], $item['picUrl'], $item['url']);
    					        }
    					        return ResponsePassive::news($request['fromusername'], $request['tousername'], $itemList);
    					    }else{
    					        return ResponsePassive::text($request['fromusername'], $request['tousername'], "感谢您的回复！");
    					    }
    					}
    					if($rule['type'] == 'voice' && $rule['m_is_use'] == 1){
    						return ResponsePassive::voice($request['fromusername'], $request['tousername'], $rule['media_id']);
    					}
    					if($rule['type'] == 'video' && $rule['m_is_use'] == 1){
    						return ResponsePassive::video($request['fromusername'], $request['tousername'], $rule['media_id']);
    					}
    					if($rule['type'] == 'image' && $rule['m_is_use'] == 1){
    						return ResponsePassive::image($request['fromusername'], $request['tousername'], $rule['media_id']);
    					}
    				}
    			}
    		}
    		//统一回复消息
    		$messageResult = $lib_message->findMessage(array('use_scene'=>1,'is_use'=>1));
    		if($messageResult['errorCode'] == 0){
    			if($messageResult['data']['type'] == 'text'){
    				return ResponsePassive::text($request['fromusername'], $request['tousername'], $messageResult['data']['content']);
    			}
    			if($messageResult['data']['type'] == 'image'){
    				return ResponsePassive::image($request['fromusername'], $request['tousername'], $messageResult['data']['media_id']);
    			}
    			if($messageResult['data']['type'] == 'my_news'){
    			    $newsArray = json_decode($messageResult['data']['content'],true);
    			    if(count($newsArray)){
    			        foreach ($newsArray as &$item){
    			            $item['picUrl'] = substr_replace($item['picUrl'], ROOT_URL, 0,1);//相对路径改为决定路径
    			            $itemList[] = ResponsePassive::newsItem($item['title'], $item['description'], $item['picUrl'], $item['url']);
    			        }
    			        return ResponsePassive::news($request['fromusername'], $request['tousername'], $itemList);
    			    }else{
    			        return ResponsePassive::text($request['fromusername'], $request['tousername'], "感谢您的回复！");
    			    }
    			}
    			if($messageResult['data']['type'] == 'voice'){
    				return ResponsePassive::voice($request['fromusername'], $request['tousername'], $messageResult['data']['media_id']);
    			}
    			if($messageResult['data']['type'] == 'video'){
    				return ResponsePassive::video($request['fromusername'], $request['tousername'], $messageResult['data']['media_id']);
    			}
    		}
    	}
	    //都没有，转发给客服
	    return ResponsePassive::forwardToCustomService($request['fromusername'], $request['tousername']);
    }

    /**
     * @descrpition 图像
     * @param $request
     * @return array
     */
    private  function image(&$request){
      //转发给客服
        return ResponsePassive::forwardToCustomService($request['fromusername'], $request['tousername']);
    }

    /**
     * @descrpition 语音
     * @param $request
     * @return array
     */
    private  function voice(&$request){
       //转发给客服
        return ResponsePassive::forwardToCustomService($request['fromusername'], $request['tousername']);
    }
    
    /**
     * @descrpition 视频
     * @param $request
     * @return array
     */
    private  function video(&$request){
        //转发给客服
        return ResponsePassive::forwardToCustomService($request['fromusername'], $request['tousername']);
    }

    /**
     * @descrpition 视频
     * @param $request
     * @return array
     */
    private  function shortvideo(&$request){
        //转发给客服
        return ResponsePassive::forwardToCustomService($request['fromusername'], $request['tousername']);
    }

    /**
     * @descrpition 地理
     * @param $request
     * @return array
     */
    private  function location(&$request){
        //转发给客服
        return ResponsePassive::forwardToCustomService($request['fromusername'], $request['tousername']);
    }

    /**
     * @descrpition 链接
     * @param $request
     * @return array
     */
    private  function link(&$request){
     //转发给客服
        return ResponsePassive::forwardToCustomService($request['fromusername'], $request['tousername']);
    }

    /**
     * @descrpition 关注
     * @param $request
     * @return array
     */
    private  function eventSubscribe(&$request){
		//用户业务处理
    	$this->subscribeUserLib($request, 0);
    	if(!class_exists('lib_response')) include 'model/weixin/lib_response.php';
    	$lib_message = new lib_response();
    	$useResult =  $lib_message->fileConfigObj->getFileConfigValue('is_use');
    	if($useResult['data'] ){
    	    //启用智能回复
    		//回复用户
    		$messageResult = $lib_message->findMessage(array('use_scene'=>0,"is_use"=>1));
    		if($messageResult['errorCode'] == 0){
    			if($messageResult['data']['type'] == 'text'){
    				return ResponsePassive::text($request['fromusername'], $request['tousername'], $messageResult['data']['content']);
    			}
    			if($messageResult['data']['type'] == 'image'){
    				return ResponsePassive::image($request['fromusername'], $request['tousername'], $messageResult['data']['media_id']);
    			}
    			if($messageResult['data']['type'] == 'my_news'){
    			    $newsArray = json_decode($messageResult['data']['content'],true);
    			    if(count($newsArray)){
    			        foreach ($newsArray as &$item){
    			            $item['picUrl'] = substr_replace($item['picUrl'], ROOT_URL, 0,1);//相对路径改为决定路径
    			            $itemList[] = ResponsePassive::newsItem($item['title'], $item['description'], $item['picUrl'], $item['url']);
    			        }
    			        return ResponsePassive::news($request['fromusername'], $request['tousername'], $itemList);
    			    }else{
    			        return ResponsePassive::text($request['fromusername'], $request['tousername'], "感谢您的关注！");
    			    }
    			}
    			if($messageResult['data']['type'] == 'voice'){
    				return ResponsePassive::voice($request['fromusername'], $request['tousername'], $messageResult['data']['media_id']);
    			}
    			if($messageResult['data']['type'] == 'video'){
    				return ResponsePassive::video($request['fromusername'], $request['tousername'], $messageResult['data']['media_id']);
    			}
    		}
    	}
    }
    
    /**
     * 用户业务处理
     * @param array $request
     * @param int $subscribeType
     */
    private function subscribeUserLib(&$request,$subscribeType){
    	spAccess('c',  'openidList');//清空缓存 粉丝发送变化
    	//获取粉丝的基本信息
    	$openId = $request['fromusername'];
    	$rawFansInfo = FansManage::getFansInfo($openId);
    	//构造用户信息
    	$userInfo = array(
			"account" => $rawFansInfo['openid'],//account 默认等于openid
			"subscribe" => $rawFansInfo['subscribe'],
			"open_id" => $rawFansInfo['openid'],
			"nick_name" => $rawFansInfo['nickname'],
            "phone" => $rawFansInfo['phone'],
			"sex" => $rawFansInfo['sex'],
			"city" => $rawFansInfo['city'],
			"province" => $rawFansInfo['province'],
			"country" => $rawFansInfo['country'],
			"head_img_url" => $rawFansInfo['headimgurl'],
			"add_time" => date ( 'Y-m-d H:i:s', time () ),
			"subscribe_time" => date("Y-m-d H:i:s",$rawFansInfo['subscribe_time']),
			"remark" => $rawFansInfo['remark'],
			"group_id" => $rawFansInfo['groupid'],
			"subscribe_type" => $subscribeType,
    	);
    	//查看用户表中是否已有该用户opendId,即是否曾经关注过
    	if(!class_exists('lib_user')) include 'model/base/lib_user.php';
    	$lib_user = new lib_user();
    	$openIdResult = $lib_user->isOpenIdExist($rawFansInfo['openid']);
    	
    	if($openIdResult['errorCode'] == 0){//关注过
    		//关注次数加1
    		$preSubscribeTimes = $openIdResult['data']['subscribe_times'];
    		$userInfo['subscribe_times'] = $preSubscribeTimes + 1;
    		//更新该粉丝的信息
    		$lib_user->updateUser(array('open_id' => $rawFansInfo['openid']),$userInfo);
    	}else{//第一次关注
    		$userInfo['type'] = 0;
    	    //判断平台是否开启分销商功能
    	    if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
    	    $base_config = new UtilConfig('base_config');
    	    $palertIsFen = $base_config->getConfigValue('plat_is_fen');
    	    if($palertIsFen['data'] == 1){//开启分销商
    	        if(!class_exists('lib_distributor')) include 'model/fen/lib_distributor.php';
    	        $lib_distributor = new lib_distributor();
    	        $fen_config = new UtilConfig('fen_config');
    	        $distributorFee = $fen_config->getConfigValue('distribute_fee');
    	        //判断是否关注即成为分销商
    	        $distributor['level'] = $distributorFee['data'] == 0 ? 1:0;
    	        $userInfo['type'] = $distributorFee['data'] == 0 ? 1:0;
    	        if(isset($request['eventkey'])){//判断是否是通过推广而来的下线（扫码）
    	            $code_id = ltrim($request['eventkey'],'qrscene_');
					/*
    	            if(!class_exists('lib_code')) include 'model/fen/lib_code.php';
    	            $lib_code = new lib_code();
    	            $codeInfo = $lib_code->findCode(array('id' => $code_id));
					*/
    	            $distributor['parent_id'] = $code_id;
    	            $distributorInfo = $lib_distributor->findDistributor(array('id' =>$code_id));
    	            $distributor['grand_id'] = $distributorInfo['data']['parent_id'];
					
    	            //$distributor['grand_grand_id'] = $distributorInfo['data']['grand_id'];
    	            $distributor['name'] = $distributor['nick_name'] = $userInfo['nick_name'];
    	            $distributor['rank'] = $distributorInfo['data']['rank'] + 1;
					$distributor['head_img_url'] = $userInfo['head_img_url'];
                    $distributor['phone'] = $userInfo['phone'];
    	            $userInfo['type'] = 3;
    	        }
				else{  //自行关注
    	            $distributor['name'] = $distributor['nick_name'] = $userInfo['nick_name'];
					$distributor['head_img_url'] = $userInfo['head_img_url'];
                    $distributor['phone'] = $userInfo['phone'];
    	            $userInfo['type'] = 3;
				}
    	    }
    	    
    		$userInfo['subscribe_times'] = 1;
			$userInfo['add_time'] = date ( 'Y-m-d H:i:s', time () );
    		//添加到用户表中
    		$addUserResult = $lib_user->addUser($userInfo);
    		$distributor['user_id'] = $addUserResult['data'];
    		if($palertIsFen['data'] == 1){//开启分销商
    		    $lib_distributor->addDistributor($distributor);
    		}
    	}
    }

    /**
     * @descrpition 取消关注
     * @param $request
     * @return array
     */
    private  function eventUnsubscribe(&$request){
    	spAccess('c',  'openidList');//清空缓存 粉丝发送变化
    	//初始化用户信息
    	//查看用户表中是否已有该用户opendId,即是否曾经关注过
    	if(!class_exists('lib_user')) include 'model/base/lib_user.php';
    	$lib_user = new lib_user();
    	$lib_user->updateUser(
    			array('open_id' => $request['fromusername']),
    			array(
    						//'points' => 0,//积分清零
    						'subscribe' => 0,//设置未关注
    						//'account' => '',//清空账号
    						'password' => '',//清空密码
    						'email' => '',//清空email
    						'phone' => '',//清空手机号
    						'add_time' => ''//清空注册会员时间
    					)
    			);
    	
    	if(false){
    		if(!class_exists('lib_vote')) include 'model/activity/vote/lib_vote.php';
    		$lib_vote = new lib_vote();
    		$optionResult = $lib_vote->findVoteOption(array('content'=>$request['fromusername']));
    		$result = $lib_vote->deleteVoteOption(array('id'=>$optionResult['data']['id']));
    	}
    }

    /**
     * @descrpition 扫描二维码关注（未关注时）
     * @param $request
     * @return array
     */
    private  function eventQrsceneSubscribe(&$request){
    	//用户业务处理
    	$this->subscribeUserLib($request, 1);
    	if(!class_exists('lib_response')) include 'model/weixin/lib_response.php';
    	$lib_message = new lib_response();
    	$useResult =  $lib_message->fileConfigObj->getFileConfigValue('is_use');
    	if($useResult['data'] ){
    	    //开启回复  
    	    //回复用户
        	$messageResult = $lib_message->findMessage(array('use_scene'=>0,"is_use"=>1));
        	if($messageResult['errorCode'] == 0){
        		if($messageResult['data']['type'] == 'text'){
        			return ResponsePassive::text($request['fromusername'], $request['tousername'], $messageResult['data']['content']);
        		}
        		if($messageResult['data']['type'] == 'image'){
        			return ResponsePassive::image($request['fromusername'], $request['tousername'], $messageResult['data']['media_id']);
        		}
        		if($messageResult['data']['type'] == 'my_news'){
        		    $newsArray = json_decode($messageResult['data']['content'],true);
        		    if(count($newsArray)){
        		        foreach ($newsArray as &$item){
        		            $item['picUrl'] = substr_replace($item['picUrl'], ROOT_URL, 0,1);//相对路径改为决定路径
        		            $itemList[] = ResponsePassive::newsItem($item['title'], $item['description'], $item['picUrl'], $item['url']);
        		        }
        		        return ResponsePassive::news($request['fromusername'], $request['tousername'], $itemList);
        		    }else{
        		        return ResponsePassive::text($request['fromusername'], $request['tousername'], "感谢您的关注！");
        		    }
        		}
        		if($messageResult['data']['type'] == 'voice'){
        			return ResponsePassive::voice($request['fromusername'], $request['tousername'], $messageResult['data']['media_id']);
        		}
        		if($messageResult['data']['type'] == 'video'){
        			return ResponsePassive::video($request['fromusername'], $request['tousername'], $messageResult['data']['media_id']);
        		}
        	}
    	}
    }

    /**
     * @descrpition 扫描二维码（已关注时）
     * @param $request
     * @return array
     */
    private  function eventScan(&$request){
        $content = '您已经关注了哦～';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 上报地理位置
     * @param $request
     * @return array
     */
    private  function eventLocation(&$request){
     //转发给客服
        return ResponsePassive::forwardToCustomService($request['fromusername'], $request['tousername']);
    }

    /**
     * @descrpition 自定义菜单 - 点击菜单拉取消息时的事件推送
     * @param $request
     * @return array
     */
    private  function eventClick(&$request){
		//获取该分类的信息
        $eventKey = $request['eventkey'];
		if(!class_exists(lib_weixin_eventkey)) include_once 'model/weixin/lib_weixin_eventkey.php';
		$lib_weixin_eventkey = new lib_weixin_eventkey();
		$eventkeyResult = $lib_weixin_eventkey->findEventKey(array('eventkey'=>$eventKey));
		if($eventkeyResult['errorCode'] == 0){
			$type = $eventkeyResult['data']['type'];
			$eventkey_url = $eventkeyResult['data']['url'];
			$eventkey_content = $eventkeyResult['data']['content'];
			if($type == 0){//非纯文字
				$url =  ROOT_URL . "/{$eventkey_url}&open_id={$request['fromusername']}";
				$content = "<a href='{$url}'>{$eventkey_content}</a>";
			}else{//纯文字
				$content = $eventkey_content;
			}
		}else{
			$content = "/微笑";
		}
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /*
     * @descrpition 自定义菜单 - 点击菜单跳转链接时的事件推送
     * @param $request
     * @return array
     */
    private  function eventView(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到跳转链接事件，您设置的key是' . $eventKey;
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 扫码推事件的事件推送
     * @param $request
     * @return array
     */
    private  function eventScancodePush(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到扫码推事件的事件，您设置的key是' . $eventKey;
        $content .= '。扫描信息：'.$request['scancodeinfo'];
        $content .= '。扫描类型(一般是qrcode)：'.$request['scantype'];
        $content .= '。扫描结果(二维码对应的字符串信息)：'.$request['scanresult'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 扫码推事件且弹出“消息接收中”提示框的事件推送
     * @param $request
     * @return array
     */
    private  function eventScancodeWaitMsg(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到扫码推事件且弹出“消息接收中”提示框的事件，您设置的key是' . $eventKey;
        $content .= '。扫描信息：'.$request['scancodeinfo'];
        $content .= '。扫描类型(一般是qrcode)：'.$request['scantype'];
        $content .= '。扫描结果(二维码对应的字符串信息)：'.$request['scanresult'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 弹出系统拍照发图的事件推送
     * @param $request
     * @return array
     */
    private  function eventPicSysPhoto(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到弹出系统拍照发图的事件，您设置的key是' . $eventKey;
        $content .= '。发送的图片信息：'.$request['sendpicsinfo'];
        $content .= '。发送的图片数量：'.$request['count'];
        $content .= '。图片列表：'.$request['piclist'];
        $content .= '。图片的MD5值，开发者若需要，可用于验证接收到图片：'.$request['picmd5sum'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 弹出拍照或者相册发图的事件推送
     * @param $request
     * @return array
     */
    private  function eventPicPhotoOrAlbum(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到弹出拍照或者相册发图的事件，您设置的key是' . $eventKey;
        $content .= '。发送的图片信息：'.$request['sendpicsinfo'];
        $content .= '。发送的图片数量：'.$request['count'];
        $content .= '。图片列表：'.$request['piclist'];
        $content .= '。图片的MD5值，开发者若需要，可用于验证接收到图片：'.$request['picmd5sum'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 弹出微信相册发图器的事件推送
     * @param $request
     * @return array
     */
    private static function eventPicWeixin(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到弹出微信相册发图器的事件，您设置的key是' . $eventKey;
        $content .= '。发送的图片信息：'.$request['sendpicsinfo'];
        $content .= '。发送的图片数量：'.$request['count'];
        $content .= '。图片列表：'.$request['piclist'];
        $content .= '。图片的MD5值，开发者若需要，可用于验证接收到图片：'.$request['picmd5sum'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * @descrpition 自定义菜单 - 弹出地理位置选择器的事件推送
     * @param $request
     * @return array
     */
    private  function eventLocationSelect(&$request){
        //获取该分类的信息
        $eventKey = $request['eventkey'];
        $content = '收到点击跳转事件，您设置的key是' . $eventKey;
        $content .= '。发送的位置信息：'.$request['sendlocationinfo'];
        $content .= '。X坐标信息：'.$request['location_x'];
        $content .= '。Y坐标信息：'.$request['location_y'];
        $content .= '。精度(可理解为精度或者比例尺、越精细的话 scale越高)：'.$request['scale'];
        $content .= '。地理位置的字符串信息：'.$request['label'];
        $content .= '。朋友圈POI的名字，可能为空：'.$request['poiname'];
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * 群发接口完成后推送的结果
     *
     * 本消息有公众号群发助手的微信号“mphelper”推送的消息
     * @param $request
     */
    private  function eventMassSendJobFinish(&$request){
        //发送状态，为“send success”或“send fail”或“err(num)”。但send success时，也有可能因用户拒收公众号的消息、系统错误等原因造成少量用户接收失败。err(num)是审核失败的具体原因，可能的情况如下：err(10001), //涉嫌广告 err(20001), //涉嫌政治 err(20004), //涉嫌社会 err(20002), //涉嫌色情 err(20006), //涉嫌违法犯罪 err(20008), //涉嫌欺诈 err(20013), //涉嫌版权 err(22000), //涉嫌互推(互相宣传) err(21000), //涉嫌其他
        $status = $request['status'];
        //计划发送的总粉丝数。group_id下粉丝数；或者openid_list中的粉丝数
        $totalCount = $request['totalcount'];
        //过滤（过滤是指特定地区、性别的过滤、用户设置拒收的过滤，用户接收已超4条的过滤）后，准备发送的粉丝数，原则上，FilterCount = SentCount + ErrorCount
        $filterCount = $request['filtercount'];
        //发送成功的粉丝数
        $sentCount = $request['sentcount'];
        //发送失败的粉丝数
        $errorCount = $request['errorcount'];
        $content = '发送完成，状态是'.$status.'。计划发送总粉丝数为'.$totalCount.'。发送成功'.$sentCount.'人，发送失败'.$errorCount.'人。';
        return ResponsePassive::text($request['fromusername'], $request['tousername'], $content);
    }

    /**
     * 群发接口完成后推送的结果
     *
     * 本消息有公众号群发助手的微信号“mphelper”推送的消息
     * @param $request
     */
    private  function eventTemplateSendJobFinish(&$request){
        //发送状态，成功success，用户拒收failed:user block，其他原因发送失败failed: system failed
        $status = $request['status'];
        if($status == 'success'){
            //发送成功
        }else if($status == 'failed:user block'){
            //因为用户拒收而发送失败
        }else if($status == 'failed: system failed'){
            //其他原因发送失败
        }
        return true;
    }

}
