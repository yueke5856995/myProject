<?php
namespace Api\Model;
/**
 * Class CardModel
 * @package Api\Model
 * 积分卡管理
 */
class CardModel extends BaseModel {
    /**
     * 获取积分卡分页列表（普通）
     */
    public function queryCommonByPage(){
        $sql = "select cardId,cardType,cardName,score,discount,cardThumb,cardDesc from yk_card where cardType = 1 
                andcardFlag = 1 order by cardSort asc ";
        $data = $this->queryPage($sql);
        return $data;
    }

    /**
     * 获取积分卡列表（活动）
     * 按活动时间
     */
    public function queryActiveByPage(){
        $time = date('Y-m-d H:i:s');
        $sql = "select cardId,cardType,cardName,score,discount,cardThumb,cardDesc from yk_card where cardType = 2 
               and cardFlag = 1 and endTime > ".$time."and startTime < ".$time." order by cardSort asc ";
        $data = $this->queryPage($sql);
        return $data;
    }

    /**
     * 获取用户积分卡列表
     */
    public function queryUserCard(){
        $userId = I('userId',0);
        $ucard = M('user_card');
        $data = $ucard->where("userId=".$userId)->select();
        return $data;
    }

    /**
     * 获取在售积分卡详情
     */
    public function getCardInfo(){
        $cardId = I('cardId',0);
        $data = $this ->where('cardFlag=1')->find($cardId);
        return $data;
    }

    /**
     * 获取用户积分卡详情
     */
    public function getUcardInfo(){
        $cardId = I('ucardId',0);
        $userId = I('userId',0);
        $ucard = M('user_card');
        $data =$ucard ->where('ucardFlag = 1 and userId ='.$userId)->find($cardId);
        return $data;
    }

    /**
     * 积分卡付款
     */
    public function cardPay($orderData,$ucardDate){
        $money = $orderData['totalMoney'];
        $score = $ucardDate['ucardScore'];
        $discount = $ucardDate['ucardDiscount'];
        $realTotalMoney = $money*$discount/100;

        if($score<$realTotalMoney){
            return_json(-3,'积分卡余额不足');
        }
        //减少积分
        $this->where('ucarId='.$ucardDate['ucardId'])->setDec('ucardScore',$realTotalMoney);
        //修改订单状态
        $orderDate['isPay'] = 1;
        $orderDate['ucardId'] = $ucardDate['ucardId'];
        $orderDate['dicount'] = $ucardDate['ucardDiscount'];
        $orderDate['realTotalMoney'] = $realTotalMoney;
        M('orders')->where('orderId='.$orderData['orderId'])->save($orderData);
        $time = date('Y-m-d H:i:s');
        //写入流水
        $log_money = D('Log_money');
        $logDate['dataSrc'] = 1;
        $logDate['targetId'] = 0;
        $logDate['dataId'] = $orderData['userId'];
        $logDate['moneyType'] = 1;
        $logDate['money'] = $orderData['realTotalMoney'];
        $logDate['createTime'] = $time;
        $log_money->add($logDate);
        unset($logDate);
        //写入积分流水
        $log_card = D('Log_card');
        $logDate['dataId'] = $orderData['userId'];
        $logDate['ucardId'] = $orderData['ucardId'];
        $logDate['moneyType'] = 2;
        $logDate['money'] = $orderData['totalMoney'];
        $logDate['createTime'] = $time;
        $log_card->add($logDate);
        unset($logDate);
        //写入订单操作记录
        $logDate['orderId'] =$orderData['orderId'];
        $logDate['logContent'] ="用户付款 总价:$money;折扣：$discount%;实付：$realTotalMoney";
        $logDate['logType'] =2;
        $logDate['logUserId'] =$orderData['userId'];
        $logDate['logTime'] =$time;
        $log_orders = D('Log_orders');
        $log_orders->add($logDate);
    }
}