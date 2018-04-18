<?php
namespace Api\Model;
class CommissionModel extends BaseModel {
    /**
     * 提現接口 佣金為0不予提現
     */
    public function tixian(){
        $money=I('money');
        $userid=I('userId');
        $wx_user=M('wx_users');
        $userinfo=$wx_user->where(array('userId'=>$userid))->find();

        //提现金额不足
        if($userinfo['money']<$money){
            echo"佣金不足";
            return;
        }

        $tx_data['targetId']=$userid;
        $tx_data['accUser']=$userinfo['userName'];
        $tx_data['money']=$money;
        $tx_data['createTime']=date('Y-m-d H:i:s');
        $tx_data['cashSatus']=0;
        $tx_orderid=M('cash_draws')->add($tx_data);
        if(!$tx_orderid){return_json(-1,'失败');exit();}

        //減去佣金
        $wx_user->startTrans();
        $rs=$wx_user->where(array('userId'=>$userid))->setDec('money',$money);//可提现额度减平均数;

        if($rs){
            $wx_user->commit();
            //获取当前提现配置
            $config = $this->loadConfigs();
            if($config['tixian']==1){
                $this->payment($userinfo,$money,$tx_orderid);
            }
            return_json(1,'成功');
        }else{
            $wx_user->rollback();
            return_json(0,'失败');
        }
    }

    /**
     * @param $userinfo
     * @param $money
     * @param $tx_orderid
     * 企业红包
     */
    public function payment($userinfo,$money,$tx_orderid){
        $userid = $userinfo['userId'];

        //企业付款参数
        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers';
        $api_data['mch_appid']=C('APPID'); //appid config中配置
        $api_data['mchid']=C('MCH_ID'); //mch_id config中配置
        $api_data['nonce_str']=$this->createNoncestr(32); //创建32位随机码，用于验签
        $api_data['spbill_create_ip']=get_client_ip(); //获取真实ip

        $api_data['partner_trade_no']=$tx_orderid;
        $api_data['openid']=$userinfo['openId'];
        $api_data['check_name']='NO_CHECK';
        $api_data['amount']=$money*100;
        $api_data['desc'] = '提现';
        //一次签名
        $api_data['sign'] = $this->MakeSign($api_data);
        $xml = $this->ToXml($api_data);

        $tmpInfo=curl_post_ssl($url,$xml);
        //file_put_contents('tx.txt',$tmpInfo,FILE_APPEND);
        $arr = $this->FromXml($tmpInfo);
        //相关参数结束

        if($arr['result_code']=="SUCCESS"){
            //业务逻辑
            $cash_draws=M('cash_draws');
            $rs=$cash_draws->where(array('cashId'=>$tx_orderid))->setField('cashSatus',1);
            if($rs){
                //资金流水_提现 [status=4]
                $log_moneys=M('log_moneys');
                $data['targetId']=0;
                $data['dataId']=$userid;
                $data['dataSrc']=4;
                $data['moneyType']=2;
                $data['money']=$money;
                $data['createTime']=date('Y-m-d H:i:s');
                $log_moneys->add($data);
            }else{
                $rs=$cash_draws->where(array('cashId'=>$tx_orderid))->setField('cashRemarks','写入流水明细时出现错误');
            }
            //退款结束
        }else {
            $cash_draws=M('cash_draws');
            $rs=$cash_draws->where(array('cashId'=>$tx_orderid))->setField('cashRemarks',$arr['err_code'] . $arr['err_code_des']);
            exit();
        }
    }

}