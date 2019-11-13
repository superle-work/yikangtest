<?php
/**
 * 多媒体的上传与下载
 * @author jianfang
 * @copyright changekeji
 * @version 1.0
 * @since: 2015-5-7
 */
class Media{
    /**
     * 多媒体上传。上传图片、语音、视频等文件到微信服务器，上传后服务器会返回对应的media_id，公众号此后可根据该media_id来获取多媒体。
     * 上传的多媒体文件有格式和大小限制，如下：
     * 图片（image）: 1M，支持JPG格式
     * 语音（voice）：2M，播放长度不超过60s，支持AMR\MP3格式
     * 视频（video）：10MB，支持MP4格式
     * 缩略图（thumb）：64KB，支持JPG格式
     * 媒体文件在后台保存时间为3天，即3天后media_id失效。
     *
     * @param $filename，文件绝对路径
     * @param $type, 媒体文件类型，分别有图片（image）、语音（voice）、视频（video）和缩略图（thumb）
     * @return {"type":"TYPE","media_id":"MEDIA_ID","created_at":123456789}
     */
    public static function uploadTemp($filename, $type){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token='.$accessToken.'&type='.$type;
        $data = array();
        $data['media'] = '@'.$filename;
        return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
    }

    /**
     * 下载多媒体文件
     * @param $mediaId 多媒体ID
     * @return 头信息如下
     *
     * HTTP/1.1 200 OK
     * Connection: close
     * Content-Type: image/jpeg
     * Content-disposition: attachment; filename="MEDIA_ID.jpg"
     * Date: Sun, 06 Jan 2013 10:20:18 GMT
     * Cache-Control: no-cache, must-revalidate
     * Content-Length: 339721
     * curl -G "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=ACCESS_TOKEN&media_id=MEDIA_ID"
     */
    public static function downloadTemp($mediaId){
        //获取ACCESS_TOKEN
        $accessToken = AccessToken::getAccessToken();
        $queryUrl = 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$accessToken.'&media_id='.$mediaId;
        return Curl::callWebServer($queryUrl, '', 'GET', 0);
    }
    
    /**
     * 
1、新增的永久素材也可以在公众平台官网素材管理模块中看到
2、永久素材的数量是有上限的，请谨慎新增。图文消息素材和图片素材的上限为5000，其他类型为1000
3、素材的格式大小等要求与公众平台官网一致。具体是，图片大小不超过2M，支持bmp/png/jpeg/jpg/gif格式，语音大小不超过5M，长度不超过60秒，支持mp3/wma/wav/amr格式
4、调用该接口需https协议
     *
     * @param $filename，文件绝对路径
     * @param $newsJson 图文消息json数据
     *
{
  "articles": [{
       "title": TITLE,
       "thumb_media_id": THUMB_MEDIA_ID,
       "author": AUTHOR,
       "digest": DIGEST,
       "show_cover_pic": SHOW_COVER_PIC(0 / 1),
       "content": CONTENT,
       "content_source_url": CONTENT_SOURCE_URL
    },
    //若新增的是多图文素材，则此处应还有几段articles结构
 ]
}
     * @return { "media_id":MEDIA_ID}
     */
    public static function addNews($newsJson){
    	//获取ACCESS_TOKEN
    	$accessToken = AccessToken::getAccessToken();
    	$queryUrl = 'https://api.weixin.qq.com/cgi-bin/material/add_news?access_token='.$accessToken;
    	$data = $newsJson;
    	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
    }
    
    /**
     * 上传图文消息内的图片获取URL
	*本接口所上传的图片不占用公众号的素材库中图片数量的5000个的限制。图片仅支持jpg/png格式，大小必须在1MB以下。
     * @param media  form-data中媒体文件标识，有filename、filelength、content-type等信息
     * @return Ambigous <boolean, mixed>
 {
    "url":  "http://mmbiz.qpic.cn/mmbiz/gLO17UPS6FS2xsypf378iaNhWacZ1G1UplZYWEYfwvuU6Ont96b1roYs CNFwaRrSaKTPCUdBK9DgEHicsKwWCBRQ/0"
}
     */
    public static function addNewsImage(){
    	//获取ACCESS_TOKEN
    	$accessToken = AccessToken::getAccessToken();
    	$queryUrl = 'https://api.weixin.qq.com/cgi-bin/media/uploadimg?access_token='.$accessToken;
    	return Curl::callWebServer($queryUrl, '', 'POST', 1 , 0);
    }//ttps://api.weixin.qq.com/cgi-bin/material/add_material?access_token=ACCESS_TOKEN
    
    /**
     * 上传素材
     * @param string $type 图片（image）、语音（voice）、视频（video）和缩略图（thumb）
     * @param string $title
     * @param string $introduction
     * @param media  form-data中媒体文件标识，有filename、filelength、content-type等信息
     * 请注意：图片素材将进入公众平台官网素材管理模块中的默认分组。
     * @return Ambigous <boolean, mixed>
{
  "media_id":MEDIA_ID,
  "url":URL
}
     */
    public static function addMedia($type,$title = "",$introduction = ''){
    	//获取ACCESS_TOKEN
    	$accessToken = AccessToken::getAccessToken();
    	$queryUrl = 'https://api.weixin.qq.com/cgi-bin/material/add_material?access_token='.$accessToken;
    	if($type == "video"){//视频类型需要传入描述
    		$data = '{"type":"' . $type . '","description":{"title":"'.$title.'","introduction":"'.$introduction.'"}}';
    	}else{
    		$data = '{"type":"' . $type . '"}';
    	}
    	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
    }
    
    /**
     * 获取所有素材列表信息
      	1、获取永久素材的列表，也会包含公众号在公众平台官网素材管理模块中新建的图文消息、语音、视频等素材（但需要先通过获取素材列表来获知素材的media_id）
		2、临时素材无法通过本接口获取
		3、调用该接口需https协议
		*@param string  $type 图片（image）、视频（video）、语音 （voice）、图文（news）
		*@param int $offset 0表示从第一个素材 返回
    	*@param int $count 1到20之间
     * @return Ambigous <boolean, mixed>
     */
    public static function getMediaList($type,$offset,$count){
    	//获取ACCESS_TOKEN
    	$accessToken = AccessToken::getAccessToken();
    	$queryUrl = 'https://api.weixin.qq.com/cgi-bin/material/batchget_material?access_token='.$accessToken;
 		 $data = '{"type":"' . $type . '","offset":"' .$offset . '","count":"' .$count .'"}';
    	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
    }
    
    /**
     * 通过mediaId获取素材资源
     * 详细介绍参加
     * http://mp.weixin.qq.com/wiki/4/b3546879f07623cb30df9ca0e420a5d0.html
     * @return
     */
    public static function getMedia($mediaId){
    	//获取ACCESS_TOKEN
    	$accessToken = AccessToken::getAccessToken();
    	$queryUrl = 'https://api.weixin.qq.com/cgi-bin/material/get_material?access_token='.$accessToken;
    	$data = '{"media_id":"' . $mediaId . '"}';
    	return Curl::callWebServer($queryUrl,$data, 'POST', 1 , 0);
    }
    
    /**
	1.永久素材的总数，也会计算公众平台官网素材管理中的素材
	2.图片和图文消息素材（包括单图文和多图文）的总数上限为5000，其他素材的总数上限为1000
	3.调用该接口需https协议
	  "voice_count":COUNT,
	  "video_count":COUNT,
	  "image_count":COUNT,
	  "news_count":COUNT
     * @return 
     */
    public static function getMediaCount(){
    	//获取ACCESS_TOKEN
    	$accessToken = AccessToken::getAccessToken();
    	$queryUrl = 'https://api.weixin.qq.com/cgi-bin/material/get_materialcount?access_token='.$accessToken;
    	return Curl::callWebServer($queryUrl, '', 'POST', 1 , 0);
    }
    
	/**
	 * 删除素材
	 * @param string $mediaId
	 * @return Ambigous <boolean, mixed>
	 * {
     *	        "errcode":ERRCODE,//0成功
     * 		"errmsg":ERRMSG
	 *	}
	 */
    public static function deleteMedia($mediaId){
    	//获取ACCESS_TOKEN
    	$accessToken = AccessToken::getAccessToken();
    	$queryUrl = 'https://api.weixin.qq.com/cgi-bin/material/del_material?access_token='.$accessToken;
    	$data = '{"media_id":"' . $mediaId . '"}';
    	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
    }
    
    /**
     * 更新图文消息
     * @param json $jsonString
	 {
	  "media_id":MEDIA_ID,//要修改的图文消息的id
	  "index":INDEX,//要更新的文章在图文消息中的位置（多图文消息时，此字段才有意义），第一篇为0
	  "articles": {
	       "title": TITLE,//标题
	       "thumb_media_id": THUMB_MEDIA_ID,//图文消息的封面图片素材id（必须是永久mediaID）
	       "author": AUTHOR,//作者
	       "digest": DIGEST,//图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空
	       "show_cover_pic": SHOW_COVER_PIC(0 / 1),//是否显示封面，0为false，即不显示，1为true，即显示
	       "content": CONTENT,//图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS
	       "content_source_url": CONTENT_SOURCE_URL//图文消息的原文地址，即点击“阅读原文”后的URL
	    }
	}
     * @return Ambigous <boolean, mixed>
     * {
     *	        "errcode":ERRCODE,//0成功
     * 		"errmsg":ERRMSG
     *	}
     */
    public static function updateNews($jsonString){
    	//获取ACCESS_TOKEN
    	$accessToken = AccessToken::getAccessToken();
    	$queryUrl = 'https://api.weixin.qq.com/cgi-bin/material/update_news?access_token='.$accessToken;
    	$data = $jsonString;
    	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
    }
    
}