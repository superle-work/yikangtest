<?php
/**
 * 微信支付银行卡类型
 * @author jianfang
 */
class BankCard{

	/**
	 * 获取银行卡名称
	 * @param string $code 编号
	 * @return string
	 */
	public static function GetBankCard($code){
		if($code == 'CFT'){
			return '微信零钱';
		}
		$type = explode('_', $code);
		if($type['1'] == 'DEBIT'){//借记卡
			if($code == 'ICBC_DEBIT'){
				return '工商银行（借记卡）';
			}
			if($code == 'ABC_DEBIT'){
				return '农业银行（借记卡）';
			}
			if($code == 'PSBC_DEBIT'){
				return '邮政储蓄（借记卡）';
			}
			if($code == 'CCB_DEBIT'){
				return '建设银行（借记卡）';
			}
			if($code == 'CMB_DEBIT'){
				return '招商银行（借记卡）';
			}
			if($code == 'COMM_DEBIT'){
				return '交通银行（借记卡）';
			}
			if($code == 'SPDB_DEBIT'){
				return '浦发银行（借记卡）';
			}
			if($code == 'GDB_DEBIT'){
				return '广发银行（借记卡）';
			}
			if($code == 'CMBC_DEBIT'){
				return '民生银行（借记卡）';
			}
			if($code == 'CIB_DEBIT'){
				return '兴业银行（借记卡）';
			}
			if($code == 'PAB_DEBIT'){
				return '平安银行（借记卡）';
			}
			if($code == 'CEB_DEBIT'){
				return ' 光大银行（借记卡）';
			}
			if($code == 'CITIC_DEBIT'){
				return '中信银行（借记卡）';
			}
			if($code == 'BOSH_DEBIT'){
				return '上海银行（借记卡）';
			}
			if($code == 'CRB_DEBIT'){
				return '华润银行（借记卡）';
			}
			if($code == 'HZB_DEBIT'){
				return '杭州银行（借记卡）';
			}
			if($code == 'BSB_DEBIT'){
				return '包商银行（借记卡）';
			}
			if($code == 'CQB_DEBIT'){
				return '重庆银行（借记卡）';
			}
			if($code == 'SDEB_DEBIT'){
				return '顺德农商行 （借记卡）';
			}
			if($code == 'SZRCB_DEBIT'){
				return '深圳农商银行（借记卡）';
			}
			if($code == 'HRBB_DEBIT'){
				return '哈尔滨银行（借记卡）';
			}
			if($code == 'BOCD_DEBIT'){
				return '成都银行（借记卡）';
			}
			if($code == 'GDNYB_DEBIT'){
				return '南粤银行 （借记卡）';
			}
			if($code == 'JSB_DEBIT'){
				return '江苏银行（借记卡）';
			}
			if($code == 'NBCB_DEBIT'){
				return '宁波银行（借记卡）';
			}
			if($code == 'NJCB_DEBIT'){
				return '南京银行（借记卡）';
			}
			if($code == 'QDCCB_DEBIT'){
				return '青岛银行（借记卡）';
			}
			if($code == 'ZJTLCB_DEBIT'){
				return '浙江泰隆银行（借记卡）';
			}
			if($code == 'XAB_DEBIT'){
				return '西安银行（借记卡）';
			}
			if($code == 'CSRCB_DEBIT'){
				return '常熟农商银行 （借记卡）';
			}
			if($code == 'QLB_DEBIT'){
				return '齐鲁银行（借记卡）';
			}
			if($code == 'LJB_DEBIT'){
				return '龙江银行（借记卡）';
			}
			if($code == 'HXB_DEBIT'){
				return '华夏银行（借记卡）';
			}
			if($code == 'CS_DEBIT'){
				return '测试银行借记卡快捷支付 （借记卡）';
			}
		}else if($type['1'] == 'CREDIT'){//信用卡
			if($code == 'ICBC_CREDIT'){
				return '工商银行（信用卡）';
			}
			if($code == 'ABC_CREDIT'){
				return '农业银行 （信用卡）';
			}
			if($code == 'PSBC_CREDIT'){
				return '邮政储蓄 （信用卡）';
			}
			if($code == 'CCB_CREDIT'){
				return '建设银行 （信用卡）';
			}
			if($code == 'CMB_CREDIT'){
				return '招商银行（信用卡）';
			}
			if($code == 'BOC_CREDIT'){
				return '中国银行（信用卡）';
			}
			if($code == 'SPDB_CREDIT'){
				return '浦发银行 （信用卡）';
			}
			if($code == 'GDB_CREDIT'){
				return '广发银行（信用卡）';
			}
			if($code == 'CMBC_CREDIT'){
				return '民生银行（信用卡）';
			}
			if($code == 'PAB_CREDIT'){
				return '平安银行（信用卡）';
			}
			if($code == 'CEB_CREDIT'){
				return '光大银行（信用卡）';
			}
			if($code == 'CIB_CREDIT'){
				return '兴业银行（信用卡';
			}
			if($code == 'CITIC_CREDIT'){
				return '中信银行（信用卡）';
			}
			if($code == 'BOSH_DEBIT'){
				return  '深发银行（信用卡）';
			}
			if($code == 'BOSH_CREDIT'){
				return '上海银行 （信用卡）';
			}
			if($code == 'HZB_CREDIT'){
				return '杭州银行（信用卡）';
			}
			if($code == 'BSB_CREDIT'){
				return '包商银行 （信用卡';
			}
			if($code == 'GDNYB_CREDIT'){
				return '南粤银行 （信用卡）';
			}
			if($code == 'GZCB_CREDIT'){
				return '广州银行（信用卡）';
			}
			if($code == 'JSB_CREDIT'){
				return '江苏银行（信用卡））';
			}
			if($code == 'NBCB_CREDIT'){
				return '宁波银行（信用卡）';
			}
			if($code == 'AE_CREDIT'){
				return 'AE （信用卡）';
			}
			if($code == 'JCB_CREDIT'){
				return 'JCB （信用卡）';
			}
			if($code == 'MASTERCARD_CREDIT'){
				return 'MASTERCARD （信用卡）';
			}
			if($code == 'VISA_CREDIT'){
				return 'VISA （信用卡）';
			}
		}else{
			return $code;
		}
}

}