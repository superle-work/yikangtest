<?php
/**
 * 错误提示类
 * @author jianfang
 * @copyright changekeji
 * @version 1.0
 * @since: 2015-5-7
 */
class Msg {
	/**
	 * 返回错误信息 ...
	 * @param int $code 错误码
	 * @param string $errorMsg 错误信息
	 * @return Ambigous <multitype:unknown , multitype:, boolean>
	 */
	public static function returnErrMsg($code,  $errorMsg = null) {
		$returnMsg = array('error_code' => $code);
		if (!empty($errorMsg)) {
			$returnMsg['custom_msg'] = $errorMsg;
		}
        $returnMsg['custom_msg'] = '出错啦！'.$returnMsg['custom_msg'];
        exit($returnMsg['custom_msg']);
	}
}
?>
