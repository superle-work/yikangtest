<?php
/**
 * 微信其他不常用接口
 * @author jianfang
 * @copyright changekeji
 * @version 1.0
 * @since: 2015-5-7
 */
class OtherLess {
    /**
     * 获取微信服务器IP列表
     */
    public static function getWeChatIPList(){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$accessToken;
        return Curl::callWebServer($url, '', 'GET');
    }
    
    /**
     * 获取自动回复规则
     *
     * @return String 返回结果与字段说明请查看http://mp.weixin.qq.com/wiki/7/7b5789bb1262fb866d01b4b40b0efecb.html
     */
    public static function getRole($industryId1, $industryId2){
    	$queryUrl = 'https://api.weixin.qq.com/cgi-bin/get_current_autoreply_info?access_token='.AccessToken::getAccessToken();
    	$queryAction = 'POST';
    	$template = array();
    	$template['industry_id1'] = "$industryId1";
    	$template['industry_id2'] = "$industryId2";
    	$template = json_encode($template);
    	return Curl::callWebServer($queryUrl, $template, $queryAction);
    }
    
    /**
     * 语义理解
     * 单类别意图比较明确，识别的覆盖率比较大，所以如果只要使用特定某个类别，建议将category只设置为该类别。
     * @param $query 输入文本串，如“查一下明天从北京到上海的南航机票"
     * @param $category String 需要使用的服务类型，如“flight,hotel”，多个用“,”隔开，不能为空。详见《接口协议文档》
     * @param $latitude Float 纬度坐标，与经度同时传入；与城市二选一传入。详见《接口协议文档》
     * @param $longitude Float 经度坐标，与纬度同时传入；与城市二选一传入。详见《接口协议文档》
     * @param $region String 区域名称，在城市存在的情况下可省；与经纬度二选一传入。详见《接口协议文档》
     * @param $city 城市名称，如“北京”，与经纬度二选一传入
     * @param $openId
     * @return bool|mixed
     * 《接口协议文档》：http://mp.weixin.qq.com/wiki/images/1/1f/微信语义理解协议文档.zip
     */
    public static function semanticSemproxy($query, $category, $openId, $latitude='', $longitude='', $region='', $city=''){
    	$queryUrl = 'https://api.weixin.qq.com/semantic/semproxy/search?access_token='.AccessToken::getAccessToken();
    	$queryAction = 'POST';
    	$template = array();
    	$template['query'] = $query;
    	$template['category'] = $category;
    	$template['appid'] = WECHAT_APPID;
    	$template['uid'] = $openId;
    	if(!empty($latitude)) $template['latitude'] = $latitude;
    	if(!empty($longitude)) $template['longitude'] = $longitude;
    	if(!empty($region)) $template['region'] = $region;
    	if(!empty($city)) $template['city'] = $city;
    	$template = json_encode($template);
    	return Curl::callWebServer($queryUrl, $template, $queryAction, 0, 0);
    }
    
}