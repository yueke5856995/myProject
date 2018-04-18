<?php
namespace Api\Model;
/**
 * Class PayModel
 * @package Api\Model
 * 积分卡订单方法
 */
class OrdersCardModel extends BaseModel{
    /**
     * 生成积分卡订单
     * @param $cardInfo
     * @param $userInfo
     */
    public function writeOrder($cardInfo,$userInfo){
        $orderDate['orderNo'] = 'k0'.date('Ymd').substr($userInfo['userId'],-1).randomkeys(4);
        $orderDate['cardId'] = $cardInfo['cardId'];
        $orderDate['cardSorce'] = $cardInfo['cardSorce'];
        $orderDate['cardDiscount'] = $cardInfo['cardDiscount'];
        $orderDate['cardType'] = $cardInfo['cardType'];
        $orderDate['totalMoney'] = $cardInfo['cardMoney'];
        $orderDate['userId'] = $userInfo['userId'];
        $orderDate['userName'] = $userInfo['userName'];
        $orderDate['createTime'] = date('Y-m-d H:i:s');
        $orderDate['orderStatus'] = 0;
        $orderId =$this->add($orderDate);
        $orderDate['orderId'] = $orderId;
        return $orderDate;
    }
}