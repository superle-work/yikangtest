<?php
/**
 * 微信付款
 * @author linli
 * @copyright changekeji
 * @version 1.0
 * @since: 2016-11-21
 */
if(!class_exists('WxPayApi')) require "pay/notify/lib/WxPay.Api.php";
class Payment{
    
    /**
     *企业支付参数
     * @param $options
     *        string $openId       用户openid
     *        int $money           金额，单位元,最少为1元
     *        string $desc         企业付款描述信息
     *        string $check_name   校验用户姓名选项，NO_CHECK：不校验真实姓名
                                                 FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账）
                                                 OPTION_CHECK：针对已实名认证的用户才校验真实姓名
     *        string $user_name    收款用户姓名，check_name设置为FORCE_CHECK或OPTION_CHECK，则必填用户真实姓名
     *        string $device_info  终端设备号
     * @return xml
     */
    public static function EnterprisePay($options){
        $xmlResult = self::getParam($options);
        if($xmlResult['errorCode'] == 1) return common::errorArray(1, $xmlResult['errorInfo'], $xmlResult['data']);
        $payResult = WxPayApi::transfers($xmlResult['data']);
        if($payResult['result_code'] == 'SUCCESS'){
            $result = common::errorArray(0, "提现成功", '');
        }else{
            $result = common::errorArray(1, $payResult['err_code_des'], $payResult['return_msg']);
        }
        return $result;
    }
    
    /**
     *企业支付参数
     * @param $options
     *        string $openId       用户openid
     *        int $money           金额，单位元,最少为1元
     *        string $desc         企业付款描述信息
     *        string $check_name   校验用户姓名选项，NO_CHECK：不校验真实姓名
                                                 FORCE_CHECK：强校验真实姓名（未实名认证的用户会校验失败，无法转账）
                                                 OPTION_CHECK：针对已实名认证的用户才校验真实姓名
     *        string $user_name    收款用户姓名，check_name设置为FORCE_CHECK或OPTION_CHECK，则必填用户真实姓名
     *        string $device_info  终端设备号
     * @return xml
     */
    public static function getParam($options){
//         $money = (int)($options['money'] * 100);
        $money = (int)($options['money']);
        if($money < 100) return common::errorArray(1, '最低提现必须大于1元', $options['money']);
        if($options['check_name'] == 'NO_CHECK'){//不检验真实姓名
            if($money > 200000) return common::errorArray(1, '每次最多提现2000元', $options['money']);
        }else{//检验真实姓名
            if(!user_name) return common::errorArray(1, '必须填写真实姓名', $options['user_name']);
            if($money > 2000000) return common::errorArray(1, '每次提现最多20000元', $options['money']); 
        }
        $input = new WxPayTransfers();
        $input->SetIp(common::getRealIp());
        if($options['device_info']){
            $input->SetDevice_info($options['device_info']);
        }
        $input->SetPartner_trade_no(self::generateNum());
        $input->SetOpen_id($options['openId']);
        $input->SetCheck_name($options['check_name']);
        $input->SetRe_user_name($options['user_name']);
        $input->SetAmount($money);
        $input->SetDesc($options['desc']);
        $input->SetAppid(WxPayConfig::APPID);//公众账号ID
        $input->SetMch_id(WxPayConfig::MCHID);//商户号
        $input->SetNonce_str(WxPayApi::getNonceStr());//随机字符串
        $input->SetSign();
        $xml = $input->ToXml();
        return common::errorArray(0, '生成xml成功', $xml);
    }
    
    /**
     * 生成退款定单号
     * @return string
     */
    private function generateNum(){
        if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
        $lib_config = new UtilConfig('fen_config');
        $config = $lib_config->findConfigKeyValue();
        $num = $config['data']['order_bit'] - 8;
        if($num <  2){
            $num = 2;
        }else if($num > 16){
            $num = 8;
        }
        $date = date ( 'Ymd', time () );
        switch ($num){
            case 2:
                $bit = 99;
                break;
            case 3:
                $bit = 999;
                break;
            case 4:
                $bit = 9999;
                break;
            case 5:
                $bit = 99999;
                break;
            case 6:
                $bit = 999999;
                break;
            case 7:
                $bit = 9999999;
                break;
            case 8:
                $bit = 99999999;
                break;
            default:
                $bit = 999999;
                $num = 6;
        }
        $serialNumber = sprintf("%0{$num}s", rand(1,$bit));
        return "TR" .  $serialNumber.$date;
    }
}