<?php
namespace Api\Model;
/**
 * Class PayModel
 * @package Api\Model
 * 积分卡充值订单方法
 */
class OrdersuCardModel extends BaseModel{
    /**
     * 生成积分卡订单
     * @param $cardInfo
     * @param $userInfo
     */
    public function writeOrder($cardInfo,$userInfo,$money){
        $orderDate['orderNo'] = 'c0'.date('Ymd').substr($userInfo['userId'],-1).randomkeys(4);
        $orderDate['cardId'] = $cardInfo['ucardId'];
        $orderDate['totalMoney'] = $money;
        $orderDate['userId'] = $userInfo['userId'];
        $orderDate['userName'] = $userInfo['userName'];
        $orderDate['createTime'] = date('Y-m-d H:i:s');
        $orderDate['orderStatus'] = 0;
        $orderId = $this->add($orderDate);
        $orderDate['orderId'] = $orderId;
        return $orderDate;
    }
}