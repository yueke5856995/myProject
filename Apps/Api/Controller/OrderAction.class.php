<?php
namespace Api\Action;
use Think\Exception;

class OrderAction extends BaseAction{

    /**
     * 最近订单列表
     */
    public function orderList(){
        $orders = D('Systemht/Orders');
        $data = $orders ->listByPage();
        echo json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    //订单删除
    public function orderDel(){
        $order=M('orders');
        $orderId=I('orderId');
        $rs=$order->where(array('orderId'=>$orderId))->setField('orderFlag',-1);
        echo $rs? 1:0;
    }

    //订单确认
    public function orderConf(){
        $order=M('orders');
        $orderId=I('orderId');
        $rs=$order->where(array('orderId'=>$orderId))->setField('orderStatus',4);
        echo $rs? 1:0;
    }


    //订单详情
    public function orderInfo(){
        $orders = D('Orders');
        $data = $orders ->getOrderInfo();
        return_json(1,'成功',$data);
    }

    /**
     * 预约取件
     * 预约商家上门取件
     */
    public function appointment(){
        //写入订单信息
        $orders = D('Orders');
        $data = $orders ->GO();
        return_json(1,'成功',$data);
    }

    /**
     * 订单付款
     */
    public function orderPay(){
        M()->startTrans();
        try{
            //获取订单信息
            $orders = D('Orders');
            $orderData = $orders ->getOrderInfo();
            if(empty($orderData)||$orderData['ispay']!=0){
                return_json(-1,'状态错误');
            }
            //获取卡片信息
            $card = D('Card');
            $ucardDate = $card->getUcardInfo();
            if(empty($ucardDate)){
                return_json(-2,'积分卡状态错误');
            }
            //进行卡片余额消减逻辑
            $card->orderPay($orderData,$ucardDate);
            //写入流水记录
            M()->commit();
            return_json(1,'操作成功');
        }catch (\Exception $e){
            M()->rollback();
            return_json(0,'操作失败');
        }
    }
}