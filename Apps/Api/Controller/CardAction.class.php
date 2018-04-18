<?php
namespace Api\Action;
use Think\Exception;

/**
 * Class CommissionAction
 * @package Api\Action
 * 会员卡管理器
 */
class CardAction extends CommonAction {
    /**
     * 在售卡片
     */
    public function cardList(){
        //按卡片销售期获取卡片
        $card = M('card');
        $data['common'] = $card -> queryCommonByPage();
        $data['active'] = $card -> queryActiveByPage();
        $data!=false ? return_json(1,'成功',$data) : return_json(0,'失败');
    }

    /**
     * 用户卡片
     */
    public function userCard(){
        //获取当前用户卡片列表
        $card = M('card');
        $data = $card -> queryUserCard();
        $data!=false ? return_json(1,'成功',$data) : return_json(0,'失败',$data);
    }

    /**
     * 购买卡片
     */
    public function cardBuy(){
        //获取卡片信息
        $card = D('Card');
        $cardInfo = $card -> getCardInfo();
        //获取用户信息
        $wx_user = D('Wx_user');
        $userInfo = $wx_user->getUserInfo();
        //写入订单记录
        $cardOrders = D('CardOrders');
        $orderDate = $cardOrders->writeOrder($cardInfo,$userInfo);

        if($orderDate['orderId']){
            //微信支付参数
            $notifyUrl = __CONTROLLER__.'/kNotify';
            $body = '购买积分卡';
            $orderNo =$orderDate['orderNo'];
            $money = $orderDate['totalMoney'];//注意，当存在优惠减免之类时，totalMoney必须大于0
            $openId = $userInfo['openId'];
            //发起微信支付
            $pay = M('pay');
            $pay -> WxMiniAPP($notifyUrl,$body,$orderNo,$money,$openId);
        }else{
            return_json(0,'失败');
        }
    }

    /**
     * 微信小程序回调
     */
    public function kNotify(){
        $xml = file_get_contents('php://input');
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        //用户http_build_query()将数据转成URL键值对形式
        $sign=$this->MakeSign($arr);
        if ( $sign === $arr['sign']) {
            $orders=D('Orders_card');
            $orderNo=$arr['out_trade_no'];
            $orderInfo = $orders->where(array('orderNo'=>$orderNo))->find();
            // 校验返回的订单金额是否与商户侧的订单金额一致。修改订单表中的支付状态。
            if($orderInfo['totalMoney']!=$arr['total_fee']*100||$orderInfo['orderStatus']!=0){
                $rs = true;
            }else{
                //进行业务逻辑
                M()->startTrans();
                try{
                    //更改订单状态
                    $saveData['orderStatus']=1;
                    $orders->where(array('orderNo'=>$orderNo))->save($saveData);
                    $time = date('Y-m-d H:i:s');
                    //增加积分卡
                    $user_card = D('user_card');
                    $ucardDate['userId'] = $orderInfo['userId'];
                    $ucardDate['ucardName'] = $orderInfo['cardName'];
                    $ucardDate['ucardScore'] = $orderInfo['cardScore'];
                    $ucardDate['ucardDiscount'] = $orderInfo['cardDiscount'];
                    $ucardDate['ucardType'] = $orderInfo['cardType'];
                    $ucardDate['createTime'] = $time;
                    $ucardId = $user_card ->add($ucardDate);
                    //写入流水
                    $log_money = D('Log_money');
                    $logDate['dataSrc'] = 2;
                    $logDate['targetId'] = 0;
                    $logDate['dataId'] = $orderInfo['userId'];

                    $logDate['moneyType'] = 1;
                    $logDate['money'] = $orderInfo['totalMoney'];
                    $logDate['createTime'] = $time;
                    $log_money->add($logDate);
                    unset($logDate);
                    //写入积分流水
                    $log_card = D('Log_card');
                    $logDate['dataId'] = $orderInfo['userId'];
                    $logDate['ucardId'] = $ucardId;
                    $logDate['moneyType'] = 1;
                    $logDate['money'] = $orderInfo['totalMoney'];
                    $logDate['createTime'] = $time;
                    $log_card->add($logDate);

                    M()->commit();
                    $rs = true;
                }catch (Exception $e){
                    M()->rollback();
                    $rs = false;
                }
            }
        }
        if($rs!==false){
            $return = ['return_code'=>'SUCCESS','return_msg'=>'OK'];

        }else{
            $return = ['return_code'=>'FAIL','return_msg'=>'OK'];
        }
        $xml = '<xml>';
        foreach($return as $k=>$v){
            $xml.='<'.$k.'><![CDATA['.$v.']]></'.$k.'>';
        }
        $xml.='</xml>';
        echo $xml;
    }

    /**
     * 卡片流水
     */
    public function cardLog(){
        //获取当前用户卡片列表
        $logcard = D('Systemht/LogCard');
        $data = $logcard->listByPage();
        $data!=false ? return_json(1,'成功',$data) : return_json(0,'失败',$data);
    }


    /**
     * 积分卡充值
     */
    public function cardRecharge(){
        //获取卡片信息
        $card = D('Card');
        $cardInfo = $card -> getUcardInfo();
        //获取用户信息
        $wx_user = D('Wx_user');
        $userInfo = $wx_user->getUserInfo();
        $money = I('money',0);
        //写入订单记录
        $cardOrders = D('CardOrders');
        $orderDate = $cardOrders->writeOrder($cardInfo,$userInfo,$money);

        if($orderDate['orderId']){
            //微信支付参数
            $notifyUrl = __CONTROLLER__.'/cNotify';
            $body = '充值积分卡';
            $orderNo =$orderDate['orderNo'];
            $money = $orderDate['totalMoney'];//注意，当存在优惠减免之类时，totalMoney必须大于0
            $openId = $userInfo['openId'];
            //发起微信支付
            $pay = M('pay');
            $pay -> WxMiniAPP($notifyUrl,$body,$orderNo,$money,$openId);
        }else{
            return_json(0,'失败');
        }
    }

    /**
     * 微信小程序回调
     */
    public function cNotify(){
        $xml = file_get_contents('php://input');
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        //用户http_build_query()将数据转成URL键值对形式
        $sign=$this->MakeSign($arr);
        if ( $sign === $arr['sign']) {
            $orders=D('Orders_ucard');
            $orderNo=$arr['out_trade_no'];
            $orderInfo = $orders->where(array('orderNo'=>$orderNo))->find();
            // 校验返回的订单金额是否与商户侧的订单金额一致。修改订单表中的支付状态。
            if($orderInfo['totalMoney']!=$arr['total_fee']*100||$orderInfo['orderStatus']!=0){
                $rs = true;
            }else{
                //进行业务逻辑
                M()->startTrans();
                try{
                    //更改订单状态
                    $saveData['orderStatus']=1;
                    $rs = $orders->where(array('orderNo'=>$orderNo))->save($saveData);
                    $time = date('Y-m-d H:i:s');
                    //增加积分卡积分
                    $user_card = D('user_card');
                    $user_card->where('ucardId='.$orderInfo['ucardId'])->setInc('ucardScore',$orderInfo['totalMoney']);
                    //写入流水
                    $log_money = D('Log_money');
                    $logDate['dataSrc'] = 3;
                    $logDate['targetId'] = 0;
                    $logDate['dataId'] = $orderInfo['userId'];
                    $logDate['moneyType'] = 1;
                    $logDate['money'] = $orderInfo['totalMoney'];
                    $logDate['createTime'] = $time;
                    $log_money->add($logDate);
                    unset($logDate);
                    //写入积分流水
                    $log_card = D('Log_card');
                    $logDate['dataId'] = $orderInfo['userId'];
                    $logDate['ucardId'] = $orderInfo['ucardId'];
                    $logDate['moneyType'] = 1;
                    $logDate['money'] = $orderInfo['totalMoney'];
                    $logDate['createTime'] = $time;
                    $log_card->add($logDate);

                    M()->commit();
                    $rs = true;
                }catch (Exception $e){
                    M()->rollback();
                    $rs = false;
                }
            }
        }
        if($rs!==false){
            $return = ['return_code'=>'SUCCESS','return_msg'=>'OK'];
        }else{
            $return = ['return_code'=>'FAIL','return_msg'=>'OK'];
        }
        $xml = '<xml>';
        foreach($return as $k=>$v){
            $xml.='<'.$k.'><![CDATA['.$v.']]></'.$k.'>';
        }
        $xml.='</xml>';
        echo $xml;
    }
}