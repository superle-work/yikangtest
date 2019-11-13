<?php
/**
 * 提供订单退款操作管理服务
 * @name UtilRefund
 * @package cws
 * @category include
 * @link http://www.chanekeji.com
 * @author linli
 * @version 2.0
 * @copyright CHANGE INC
 * @since 2016-08-29
 */
if(!class_exists('WxPayApi')) require "pay/notify/lib/WxPay.Api.php";
class UtilRefund{
    
    //-------------------------订单退款--------------------------
    /**
     * 微信退款
     * @param string $table 模块配置表
     * @param string $transaction_id 微信支付交易id
     * @param float $total_fee 订单金额
     * @param float $refund_fee 退款金额
     * @param string $out_trade_no 微信订单号
     * @return array $result
     */
    public function refundFee($table,$transaction_id,$total_fee,$refund_fee,$out_trade_no = null){
        if($transaction_id){
            $refund_no = $this->generateNum($table);
            $input = new WxPayRefund();
            $input->SetTransaction_id($transaction_id);//微信订单号
            //$input->SetOut_trade_no($out_trade_no);//订单号  与微信订单号只需要填写一个
            $input->SetTotal_fee($total_fee * 100);
            $input->SetRefund_fee($refund_fee * 100);
            $input->SetOut_refund_no($refund_no);//设置退款单号
            $input->SetOp_user_id(WxPayConfig::MCHID);
            $refundResult = WxPayApi::refund($input);
            if($refundResult['return_code'] != 'SUCCESS'){
                return common::errorArray(1, '退款失败', $refundResult['return_msg']);
            }else{
                return common::errorArray(0, '退款成功', $refundResult);
            }
        }else{
            return common::errorArray(1, '需退款订单未成功支付', $transaction_id);
        }
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
        $money = (int)($options['money'] * 100);
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
    private function generateNum($table){
        if(!class_exists('UtilConfig')) include 'include/UtilConfig.php';
        $lib_config = new UtilConfig($table);
        $config = $lib_config->findConfigKeyValue('order');
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
        return "RF" .  $serialNumber.$date;
    }
}
