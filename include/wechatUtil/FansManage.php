<?php
/**
 * 粉丝管理类
 * @author jianfang
 * @copyright changekeji
 * @version 1.0
 * @since: 2015-5-7
 */
class FansManage{

    //-----------------------------组--------------管-------------理----------------------

    /**
     * @descrpition 创建分组
     * @param $groupName 组名 UTF-8
     * @return JSON {"group": {"id": 107,"name": "test"}}
     */
    public static function createGroup($groupName){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/groups/create?access_token='.$accessToken;
        $data = '{"group":{"name":"'.$groupName.'"}}';
        return Curl::callWebServer($queryUrl, $data, 'POST');

    }

    /**
     * @descrpition 获取分组列表
     * @return JSON {"groups":[{"id": 0,"name": "未分组", "count": 72596}]}
     */
    public static function getGroupList(){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/groups/get?access_token='.$accessToken;
        $data = '';
        return Curl::callWebServer($queryUrl, $data, 'GET');
    }

    /**
     * @descrpition 查询用户所在分组
     * @param $openId 用户唯一OPENID
     * @return JSON {"groupid": 102}
     */
    public static function getGroupByOpenId($openId){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/groups/getid?access_token='.$accessToken;
        $data = '{"openid":"'.$openId.'"}';
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }
    
    /**
     * 通过groupid获取group信息
     * @param int $groupId
     * @param array $groupList
     * @return unknown|boolean
     */
    public static function getGroupById($groupId,$groupList){
    	foreach ($groupList as $group){
    		if($groupId == $group['id']){
    			return $group;
    		}
    	}
    	return false;
    }

    /**
     * @descrpition 修改分组名
     * @param $groupId 要修改的分组ID
     * @param $groupName 新分组名
     * @return JSON {"errcode": 0, "errmsg": "ok"}
     */
    public static function editGroupName($groupId, $groupName){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/groups/update?access_token='.$accessToken;
        $data = '{"group":{"id":'.$groupId.',"name":"'.$groupName.'"}}';
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }

    /**
     * @descrpition 移动用户分组
     * @param $openid 要移动的用户OpenId
     * @param $to_groupid 移动到新的组ID
     * @return JSON {"errcode": 0, "errmsg": "ok"}
     */
    public static function editUserGroup($openid, $to_groupid){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token='.$accessToken;
        $data = '{"openid":"'.$openid.'","to_groupid":'.$to_groupid.'}';
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }

    //-----------------------------用-------户-------管--------理----------------------

    /**
     * @descrpition 获取用户基本信息
     * @param $openId 用户唯一OpenId
     * @return JSON {
                    "subscribe": 1,    //用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息
                    "openid": "o6_bmjrPTlm6_2sgVt7hMZOPfL2M",
                    "nickname": "Band",
                    "sex": 1,          //用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
                    "language": "zh_CN",
                    "city": "广州",
                    "province": "广东",
                    "country": "中国",
                    "headimgurl":    "http://wx.qlogo.cn/mmopen/g3MonUZtNHkdmzicIlibx6iaFqAc56vxLSUfpb6n5WKSYVY0ChQKkiaJSgQ1dZuTOgvLLrhJbERQQ4eMsv84eavHiaiceqxibJxCfHe/0",
                    "subscribe_time": 1382694957
                    }
     */
    public static function getFansInfo($openId){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$accessToken.'&openid='.$openId;
        return Curl::callWebServer($queryUrl, '', 'GET');
    }
    
    /**
     * 批量获取用户信息 最多100个
     * @param array $openIdList
     * @return Ambigous <boolean, mixed>
{
   "user_info_list": [
       {
           "subscribe": 1, 
           "openid": "otvxTs4dckWG7imySrJd6jSi0CWE", 
           "nickname": "iWithery", 
           "sex": 1, 
           "language": "zh_CN", 
           "city": "Jieyang", 
           "province": "Guangdong", 
           "country": "China", 
           "headimgurl": "http://wx.qlogo.cn/mmopen/xbIQx1GRqdvyqkMMhEaGOX802l1CyqMJNgUzKP8MeAeHFicRDSnZH7FY4XB7p8XHXIf6uJA2SCunTPicGKezDC4saKISzRj3nz/0", 
           "subscribe_time": 1434093047, 
           "unionid": "oR5GjjgEhCMJFyzaVZdrxZ2zRRF4", 
           "remark": "", 
           "groupid": 0
       }, 
       {
           "subscribe": 0, 
           "openid": "otvxTs_JZ6SEiP0imdhpi50fuSZg", 
           "unionid": "oR5GjjjrbqBZbrnPwwmSxFukE41U", 
       }
   ]
}
     */
    public static function getFansInfoBatch($openIdList){
    	//获取ACCESS_TOKEN
    	$accessToken = AccessToken::getAccessToken();
    	$userList['user_list'] = array();
    	foreach ($openIdList as $openId){
    		array_push($userList['user_list'] , array('openid'=>$openId, "lang"=> "zh-CN"));
    	}
    	$userListJson = json_encode($userList);
    	$queryUrl = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token='.$accessToken;
    	return Curl::callWebServer($queryUrl, $userListJson, 'POST');
    }

    /**
     * @descrpition 获取关注者列表
     * @param $next_openid 第一个拉取的OPENID，不填默认从头开始拉取
     * @return JSON {"total":2,"count":2,"data":{"openid":["OPENID1","OPENID2"]},"next_openid":"NEXT_OPENID"}
     */
    public static function getFansList($next_openid=''){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        if(empty($next_openid)){
            $queryUrl = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$accessToken;
        }else{
            $queryUrl = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token='.$accessToken.'&next_openid='.$next_openid;
        }
        $result = Curl::fansRequest($queryUrl);
        if(!class_exists('UtilArray')) include 'include/UtilArray.php';
        $result = UtilArray::objectToArray($result);
        return $result;
    }
    
    /**
     * 获取所有的粉丝列表
     * @return array
     */
    public static function getAllFansList(){
    	$result = self::getFansList();
    	$endResult = $result;
    	$count = ceil($result['total'] / 10000);
    	$nextOpenId = $result['next_openid'];
    	for($i = 1;$i < $count;$i++){
    		$result = array();
    		$result = self::getFansList($nextOpenId);
    		$nextOpenId = $result['next_openid'];
    		$endResult['count'] = $endResult['count'] + $result['count'];
    		$endResult['data']['openid'] = array_merge($endResult['data']['openid'], $result['data']['openid']);
    	}
    	return $endResult;
    }
    

    /**
     * 设置备注名 开发者可以通过该接口对指定用户设置备注名，该接口暂时开放给微信认证的服务号。
     * @param $openId 用户的openId
     * @param $remark 新的昵称
     * @return array('errorcode'=>0, 'errmsg'=>'ok') 正常时是0
    }

     */
    public static function setRemark($openId, $remark){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'https://api.weixin.qq.com/cgi-bin/user/info/updateremark?access_token='.$accessToken;
        $data = json_encode(array('openid'=>$openId, 'remark'=>$remark));
        return Curl::callWebServer($queryUrl, $data, 'POST');
    }

    /**
     * @descrpition 获取网络状态
     * @return String network_type:wifi wifi网络。network_type:edge 非wifi,包含3G/2G。network_type:fail 网络断开连接
     */
    public static function getNetworkState(){
        echo "WeixinJSBridge.invoke('getNetworkType',{},
		function(e){
	    	WeixinJSBridge.log(e.err_msg);
	    });";
    }


}