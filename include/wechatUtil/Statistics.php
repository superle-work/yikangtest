<?php
/**
 * 微信数据统计
 * @author jianfang
 * @copyright changekeji
 * @version 1.0
 * @since: 2015-8-24
 */
class Statistics{
	public static $testToken = "XrezCzhtwLOrLGt2x5FnhAnes9yP6ka2Olm5lGppO6_OdOJ5oUVfSVuAWr4Coy6TNm9dVDOld-ivMFfsZ8V9GFr7S041tnCNR-TlzUx-Ijk";
	/**
	 用户分析接口
	 接口侧的公众号数据的数据库中仅存储了2014年12月1日之后的数据，将查询不到在此之前的日期，即使有查到，也是不可信的脏数据
	 cumulate_user	总用户量
    ref_date	数据的日期
	user_source	用户的渠道，数值代表的含义如下：
		0 其他（包括带参数二维码） 
		3 扫二维码
		17 名片分享 
		35 搜号码（即微信添加朋友页的搜索）
		39 查询微信公众帐号 
		43 图文页右上角菜单
	new_user	新增的用户数量
	cancel_user	取消关注的用户数量，new_user减去cancel_user即为净增用户数量
	 */
	
	 /**
	 * 获取用户增减数据
	 * @param string $from 开始日期 2015-01-01 最大时间跨度7天
	 * @param string $to 结束日期 2015-01-08 最大时间跨度7天
	 * @return Ambigous <boolean, mixed>
	 array list
			 "ref_date": "2014-12-07", 
            "user_source": 0, 用户的渠道
            "new_user": 0, 新增的用户数量
            "cancel_user": 0 取消关注的用户数量
	 */
	 public static function getUserSummary($from,$to){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	//$accessToken = Statistics::$testToken;
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getusersummary?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$from.'","end_date":"'.$to.'"}';
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 获取累计用户数据
	  * @param string $from 开始日期 2015-01-01 最大时间跨度7天
	  * @param string $to  结束日期 2015-01-08 最大时间跨度7天
	  * @return Ambigous <boolean, mixed>
	        "ref_date": "2014-12-07", 
            "cumulate_user": 1217056 总用户量
	  */
	 public static function getUserCumulate($from,$to){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	//$accessToken = Statistics::$testToken;
	 		 	 
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getusercumulate?access_token={$accessToken}";
		 $data = '{"begin_date":"'.$from.'","end_date":"'.$to.'"}';
	 	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 图文分析接口
	    ref_date	数据的日期，需在begin_date和end_date之间
		ref_hour	数据的小时，包括从0到2300，分别代表的是[0,100)到[2300,2400)，即每日的第1小时和最后1小时
		stat_date	统计的日期，在getarticletotal接口中，ref_date指的是文章群发出日期， 而stat_date是数据统计日期
		msgid	请注意：这里的msgid实际上是由msgid（图文消息id，这也就是群发接口调用后返回的msg_data_id）和index（消息次序索引）组成， 例如12003_3， 其中12003是msgid，即一次群发的消息的id； 3为index，假设该次群发的图文消息共5个文章（因为可能为多图文），3表示5个中的第3个
		title	图文消息的标题
		int_page_read_user	图文页（点击群发图文卡片进入的页面）的阅读人数
		int_page_read_count	图文页的阅读次数
		ori_page_read_user	原文页（点击图文页“阅读原文”进入的页面）的阅读人数，无原文页时此处数据为0
		ori_page_read_count	原文页的阅读次数
		share_scene	分享的场景
									1代表好友转发 2代表朋友圈 3代表腾讯微博 5代表其他
		
		share_user	分享的人数
		share_count	分享的次数
		add_to_fav_user	收藏的人数
		add_to_fav_count	收藏的次数
		target_user	送达人数，一般约等于总粉丝数（需排除黑名单或其他异常情况下无法收到消息的粉丝）
		user_source	在获取图文阅读分时数据时才有该字段，代表用户从哪里进入来阅读该图文。0:会话;1.好友;2.朋友圈;3.腾讯微博;4.历史消息页;5.其他
	  */
	 /**
	  * 获取图文群发每日数据 获取的是某天所有被阅读过的文章（仅包括群发的文章）在当天的阅读次数等数据
	  * @param string $date 时间跨度为1
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getArticleSummary($date){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 		 	
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getarticlesummary?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$date.'","end_date":"'.$date.'"}';
	 	
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 获取图文群发总数据  该接口可以获取到自群发消息后7日的数据
	  * 获取的是，某天群发的文章，从群发日起到接口调用日（但最多统计发表日后7天数据），每天的到当天的总等数据。
	  * 例如某篇文章是12月1日发出的，发出后在1日、2日、3日的阅读次数分别为1万，则getarticletotal获取到的数据为，
	  * 距发出到12月1日24时的总阅读量为1万，距发出到12月2日24时的总阅读量为2万，距发出到12月1日24时的总阅读量为3万。
	  * @param string $date 时间跨度为1
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getArticleTotal($date){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 		 	 
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getarticletotal?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$date.'","end_date":"'.$date.'"}';
	 	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 获取图文统计分时数据
	  * @param string $date 时间跨度为1
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getUserReadHour($date){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 		 	 
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getuserreadhour?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$date.'","end_date":"'.$date.'"}';
	 	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 获取图文分享转发分时数据
	  * @param string $date 时间跨度为1
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getUserShareHour($date){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 		 	 
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getusersharehour?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$date.'","end_date":"'.$date.'"}';
	 	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 获取图文统计数据
	 * @param string $from 开始日期 2015-01-01 最大时间跨度3天
	 * @param string $to 结束日期 2015-01-03 最大时间跨度3天
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getUserRead($from,$to){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 		 	 
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getuserread?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$from.'","end_date":"'.$to.'"}';
	 	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 获取图文分享转发数据
	  * @param string $from 开始日期 2015-01-01 最大时间跨度7天
	  * @param string $to 结束日期 2015-01-07 最大时间跨度7天
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getUserShare($from,$to){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 		
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getusershare?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$from.'","end_date":"'.$to.'"}';
	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 消息分析接口
	    ref_date	数据的日期，需在begin_date和end_date之间
		ref_hour	数据的小时，包括从000到2300，分别代表的是[000,100)到[2300,2400)，即每日的第1小时和最后1小时
		msg_type	消息类型，代表含义如下：
							1代表文字 2代表图片 3代表语音 4代表视频 6代表第三方应用消息（链接消息）
		msg_user	上行发送了（向公众号发送了）消息的用户数
		msg_count	上行发送了消息的消息总数
		count_interval	当日发送消息量分布的区间，0代表 “0”，1代表“1-5”，2代表“6-10”，3代表“10次以上”
		int_page_read_count	图文页的阅读次数
		ori_page_read_user	原文页（点击图文页“阅读原文”进入的页面）的阅读人数，无原文页时此处数据为0
	  */
	 
	 /**
	  * 获取消息发送概况数据 
	  * @param string $from 开始日期 2015-01-01 最大时间跨度7天
	  * @param string $to 结束日期 2015-01-07 最大时间跨度7天
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getUpStreamMsg($from,$to){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getupstreammsg?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$from.'","end_date":"'.$to.'"}';
	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 获取消息分送分时数据
	  * @param string $date 时间跨度为1
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getUpStreamMsgHour($date){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 		
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getupstreammsghour?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$date.'","end_date":"'.$date.'"}';
	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 获取消息发送周数据
	  * @param string $from 开始日期 2015-01-01 最大时间跨度30天
	  * @param string $to 结束日期 2015-01-30 最大时间跨度30天
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getUpStreamMsgWeek($from,$to){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getupstreammsgweek?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$from.'","end_date":"'.$to.'"}';
	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 获取消息发送月数据
	  * @param string $from 开始日期 2015-01-01 最大时间跨度30天
	  * @param string $to 结束日期 2015-01-30 最大时间跨度30天
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getUpStreamMsgMonth($from,$to){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getupstreammsgmonth?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$from.'","end_date":"'.$to.'"}';
	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 获取消息发送分布数据
	  * @param string $from 开始日期 2015-01-01 最大时间跨度15天
	  * @param string $to 结束日期 2015-01-15 最大时间跨度15天
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getUpStreamMsgDist($from,$to){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getupstreammsgdist?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$from.'","end_date":"'.$to.'"}';
	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  * 获取消息发送分布周数据
	  * @param string $from 开始日期 2015-01-01 最大时间跨度30天
	  * @param string $to 结束日期 2015-01-30 最大时间跨度30天
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getUpStreamMsgDistWeek($from,$to){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getupstreammsgdistweek?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$from.'","end_date":"'.$to.'"}';
	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
	 /**
	  *获取消息发送分布月数据
	  * @param string $from 开始日期 2015-01-01 最大时间跨度30天
	  * @param string $to 结束日期 2015-01-30最大时间跨度30天
	  * @return Ambigous <boolean, mixed>
	  */
	 public static function getUpStreamMsgDistMonth($from,$to){
	 	//获取ACCESS_TOKEN
	 	$accessToken = AccessToken::getAccessToken();
	 	$accessToken = Statistics::$testToken;
	 
	 	$queryUrl = "https://api.weixin.qq.com/datacube/getupstreammsgdistmonth?access_token={$accessToken}";
	 	$data = '{"begin_date":"'.$from.'","end_date":"'.$to.'"}';
	 
	 	return Curl::callWebServer($queryUrl, $data, 'POST', 1 , 0);
	 }
	 
}