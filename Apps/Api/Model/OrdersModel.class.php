<?php
namespace Api\Model;
/**
 * Class PayModel
 * @package Api\Model
 * 订单方法
 */
class OrdersModel extends BaseModel{
    //小程序预约下单
    public function GO(){
        $userId = I('userId');
        $order_data['userId']=$userId;

        $user_address=M('user_address');
        //取件信息
        $userAddressId=I('addressId');
        $userAddressInfo=$user_address->field('latitude,longitude,addr,address,userName,userPhone')->where(array('addressId'=>$userAddressId))->find();
        $order_data['userAddressId']=$userAddressId;
        $order_data['userName']=$userAddressInfo['userName'];
        $order_data['userAddress']=$userAddressInfo['address'].' ' .$userAddressInfo['addr'];
        $order_data['userPhone']=$userAddressInfo['userPhone'];
        $order_data['userLatitude']=$userAddressInfo['latitude'];
        $order_data['userLongitude']=$userAddressInfo['longitude'];

        //获取最进店家id
        $order_data['shopId']=$this->get_near_shopid($userAddressInfo['latitude'],$userAddressInfo['longitude']);
        //获取商家信息
        $shopInfo= M('shops')->field('shopId,shopName,shopTel,shopAddress,shopImg')->find($order_data['shopId']);
        //其他参数
        $order_data['orderNo'] = 'd0'.date('Ymd').substr($userId,-1).randomkeys(4);
        $order_data['receiveTime']=I('receiveTime');
        $time=date('Y-m-d H:i:s');
        $order_data['createTime']=$time;
        $order_data['orderRemarks']=I('orderRemarks');
        $order_data['orderStatus'] = 0;
        //付款方式  0积分卡付款  1余额付款
        $order_data['payType']=0;

        $order=D('orders');
        $orderid=$order->add($order_data);
        if($orderid){
            //发送商家通知消息
            $data['msgType'] = 1;
            $data['sendUserId'] = 0;
            $data['receiveUserId'] = $order_data['shopId'];
            $data['msgContent'] = "预约下单,订单号：".$order_data['orderNo'];
            $data['createTime']=date('Y-m-d H:i:s');
            $messages=M('Messages');
            $rs=$messages->add($data);
            $order_data['orderId'] = $orderid;
            return $order_data;
        }else{
            return false;
        }
    }

    /**
     * 改变订单状态
     */
    protected function changeOrderStatus(){

    }

    /**
     * 订单详情
     */
    public function getOrderInfo(){
        $orderId=I('orderId');
        $order_info = $this->alias('o')->field('o.orderId,o.createTime,o.requireTime,o.orderRemarks,o.orderStatus,o.totalMoney,o.useCoupons,o.realTotalMoney,s.shopId,s.shopName,s.shopTel,s.shopAddress,s.latitude,s.longitude,o.userName,userAddress,o.userPhone,userLatitude,userLongitude')->join('__SHOPS__  s on s.shopId = o.shopId')->where(array('orderId'=>$orderId))->find();
        $order_info['Status'] = $order_info['orderStatus'];
        $order_info['orderStatus'] = get_orderStatus($order_info['orderStatus']);
        return $order_info;
    }

}