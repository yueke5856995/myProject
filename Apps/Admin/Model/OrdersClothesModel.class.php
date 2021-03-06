<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 订单服务类
 */
class OrdersClothesModel extends BaseModel {
	/**
	 * 获取订单详细信息
	 */
	 public function getDetail(){

	 	$id = (int)I('id',0);
		$sql = "select o.*,s.shopName from __PREFIX__orders o
	 	         left join __PREFIX__shops s on o.shopId=s.shopId 
	 	         where o.orderFlag=1 and o.orderId=".$id;
		$rs = $this->queryRow($sql);
		//获取用户详细地址
		$sql = 'select communityName,a1.areaName areaName1,a2.areaName areaName2,a3.areaName areaName3 from __PREFIX__communitys c 
		        left join __PREFIX__areas a1 on a1.areaId=c.areaId1 
		        left join __PREFIX__areas a2 on a2.areaId=c.areaId2
		        left join __PREFIX__areas a3 on a3.areaId=c.areaId3
		        where c.communityId='.$rs['communityId'];
		$cRs = $this->queryRow($sql);
		$rs['userAddress'] = $cRs['areaName1'].$cRs['areaName2'].$cRs['areaName3'].$cRs['communityName'].$rs['userAddress'];
		//获取日志信息

		$sql = "select lo.*,u.loginName,u.userType,s.shopName from __PREFIX__log_orders lo
		         left join __PREFIX__users u on lo.logUserId = u.userId
		         left join __PREFIX__shops s on u.userType!=0 and s.userId=u.userId
		         where orderId=".$id;
		$rs['log'] = $this->query($sql);
		//获取相关商品
		$sql = "select og.*,g.catName goodsName,g.catId goodsId from __PREFIX__order_goods og
			        left join __PREFIX__goods_cats g on og.goodsId=g.catId
			        where og.orderId = ".$id;
		$rs['goodslist'] = $this->query($sql);
		
		return $rs;
	 }
	 /**
	  * 获取订单信息
	  */
	 public function get(){
	 	return $this->where('isRefund=0 and payType=1 and isPay=1 and orderFlag=1 and orderStatus in (-1,-3,-5,-6,-9) and orderId='.(int)I('id'))->find();
	 }
	 /**
	  * 订单分页列表
	  */
     public function queryByPage(){

        $shopName = WSTAddslashes(I('shopName'));
     	$orderNo = WSTAddslashes(I('orderNo'));
     	$orderStatus = (int)I('orderStatus',1);
     	$userId = I('userId',0);
	 	$sql = "select o.orderId,o.orderNo,o.totalMoney,o.orderStatus,o.deliverMoney,o.createTime,o.getName,o.getAddress,o.getPhone,o.goodsId from __PREFIX__orders_clothes o
	 	         where o.orderFlag=1 ";
	 	if($shopName!='')$sql.=" and (s.shopName like '%".$shopName."%' or s.shopSn like '%".$shopName."%')";
         if($orderNo!='')$sql.=" and o.orderId like '%".$orderNo."%' or o.getName like '%".$orderNo."%' or o.getPhone like '%".$orderNo."%' or o.getAddress like '%".$orderNo."%'";
	 	if($orderStatus!=-9999 && $orderStatus!=-100)$sql.=" and o.orderStatus=".$orderStatus;
	 	if($userId > 0)$sql.=" and userId =".$userId;
	 	$sql.=" order by orderId desc";
		$page = $this->pageQuery($sql);

		//获取涉及的订单及商品
		if(count($page['root'])>0){
			foreach ($page['root'] as $key => $v){
			    $goodsId =json_decode($v['goodsId'],true);
			    foreach ($goodsId as $k){
			        $goodsIds[]=$k['goodsId'];
                }
                $rs = M('goods')->field('goodsId,goodsName,goodsThums')->where(array('goodsId'=>array('in',$goodsIds)))->select();
                $page['root'][$key]['goodslist']=$rs;
			}
		}
		return $page;
	 }





	 /**
	  * 获取退款列表
	  */
     public function queryRefundByPage(){

        $shopName = WSTAddslashes(I('shopName'));
     	$orderNo = WSTAddslashes(I('orderNo'));
     	$isRefund = (int)I('isRefund',-1);
     	$areaId1 = (int)I('areaId1',0);
     	$areaId2 = (int)I('areaId2',0);
     	$areaId3 = (int)I('areaId3',0);
	 	$sql = "select o.orderId,o.orderNo,o.totalMoney,o.orderStatus,o.isRefund,o.deliverMoney,o.payType,o.createTime,s.shopName,o.userName,o.orderFrom from __PREFIX__orders o
	 	         left join __PREFIX__shops s on o.shopId=s.shopId  where o.orderFlag=1 and o.orderStatus in (-1,-3,-4,-5,-6,-9) and payType=1 and isPay=1 ";
	 	if($areaId1>0)$sql.=" and s.areaId1=".$areaId1;
	 	if($areaId2>0)$sql.=" and s.areaId2=".$areaId2;
	 	if($areaId3>0)$sql.=" and s.areaId3=".$areaId3;
	 	if($isRefund>-1)$sql.=" and o.isRefund=".$isRefund;
	 	if($shopName!='')$sql.=" and (s.shopName like '%".$shopName."%' or s.shopSn like '%".$shopName."%')";
	 	if($orderNo!='')$sql.=" and o.orderNo like '%".$orderNo."%' ";
	 	$sql.=" order by orderId desc";  
		$page = $this->pageQuery($sql);
		//获取涉及的订单及商品
		if(count($page['root'])>0){
			$orderIds = array();
			foreach ($page['root'] as $key => $v){
				$orderIds[] = $v['orderId'];
			}
			$sql = "select og.orderId,og.goodsThums,og.goodsName,og.goodsId from __PREFIX__order_goods og
			        where og.orderId in(".implode(',',$orderIds).")";
		    $rs = $this->query($sql);
		    $goodslist = array();
		    foreach ($rs as $key => $v){
		    	$goodslist[$v['orderId']][] = $v;
		    }
		    foreach ($page['root'] as $key => $v){
		    	$page['root'][$key]['goodslist'] = $goodslist[$v['orderId']];
		    }
		}
		return $page;
	 }
	 
	 /**
	  * 退款
	  */
	 public function refund(){
	 	$rd = array('status'=>-1,'msg'=>"操作失败，请检查订单状态是否已发生改变");
	 	$refundStatus = (int)I("refundStatus");
	 	$order = $this->where('isRefund=0 and orderFlag=1 and orderStatus in (-1,-3,-5,-6,-9) and payType=1 and isPay=1 and orderId='.(int)I('id'))->find();
	 	if(!empty($order)){
	 		$orderId = $order["orderId"];
	 		M()->startTrans();
	 		try {
		 		if($refundStatus==1){//退款给用户，订单结束
		 			if($order["backMoney"]==0 && $order["realTotalMoney"]>0){
		 				$rd["msg"] = "操作失败，退款金额不能为0";
		 				return $rd;
		 			}
		 			if($order["backMoney"]>$order["realTotalMoney"]){
		 				$rd["msg"] = "操作失败，退款金额不能大于实支付金额";
		 				return $rd;
		 			}
		 			
		 			$sql = "UPDATE __PREFIX__orders set orderStatus = -4,isRefund=1 WHERE orderFlag=1 and orderId = $orderId ";
		 			$rs = $this->execute($sql);
		 			//加回库存
		 			if($rs>0){
		 				$oglist = M("order_goods")->where(array("orderId"=>$orderId))->field("goodsId,goodsNums,goodsAttrId")->select();
		 				foreach ($oglist as $key => $ogoods) {
		 					$goodsId = $ogoods["goodsId"];
		 					$goodsNums = $ogoods["goodsNums"];
		 					$goodsAttrId = $ogoods["goodsAttrId"];
		 					M("goods")->where(array("goodsId"=>$goodsId))->setInc("goodsStock",$goodsNums);
		 					if($goodsAttrId>0){
		 						M("goods_attributes")->where(array("id"=>$goodsAttrId))->setInc("attrStock",$goodsNums);
		 					}
		 				}
		 			
		 				//退款给用户
						if($order["backMoney"]>0 || $order["useScore"]>0){
			 				$data = array();
			 				$data["userMoney"] = array("exp", "userMoney+".$order["backMoney"]);
			 				$data["userScore"] = array("exp", "userScore+".$order["useScore"]);
			 				M("users")->where(array("userId"=>$order["userId"]))->save($data);
			 				$moneyRemark = "订单【".$order["orderNo"]."】退款";
			 				WSTMoneyLog(0,$order["userId"],$moneyRemark,6,$orderId,$order["backMoney"],0,0,1);
						}
						
		 				//余款退回给商家
		 				$balanceMoney = $order["realTotalMoney"] - $order["backMoney"];
		 				if($balanceMoney>0){
		 					$data = array();
		 					$spUserId = M("shops")->where(array("shopId"=>$order['shopId']))->getField("userId");
		 					$data["userMoney"] = array("exp", "userMoney+".$balanceMoney);
		 					M("users")->where(array("userId"=>$spUserId))->save($data);
		 					$moneyRemark = "订单【".$order["orderNo"]."】退款";
		 					WSTMoneyLog(0,$spUserId,$moneyRemark,6,$orderId,$balanceMoney,0,0,1);
		 				}
		 			
		 				if($order["useScore"]>0){//退回积分
		 					$data = array();
		 					$m = M('user_score');
		 					$data["userId"] = $order["userId"];
		 					$data["score"] = $order["useScore"];
		 					$data["dataSrc"] = 4;
		 					$data["dataId"] = $orderId;
		 					$data["dataRemarks"] = "取消订单返还";
		 					$data["scoreType"] = 1;
		 					$data["createTime"] = date('Y-m-d H:i:s');
		 					$m->add($data);
		 				}
		 				WSTSendMsg($order["userId"], "您的订单【".$order["orderNo"]."】已退款，请注意查收！");
		 				$content = "订单已退款【管理员裁定】";
		 				WSTOrderLog($orderId, $content, session('WST_STAFF.staffId'),1);
		 			
			 			$data = array();
			 			$data['isRefund'] = 1;
			 			$data['refundRemark'] = I('content');
			 			$this->where("orderId=".(int)I('id',0))->save($data);
			 			
			 			$rd['status']= 1;
			 			$rd['msg']= "操作成功";
		 			}
		 		}else if($refundStatus==2){//用户确认收货，结算给商家
		 			$data = array();
		 			$m = D('Common/Orders');
		 			$data["userId"] = $order["userId"];
		 			$data["orderId"] = $orderId;
		 			$data["optType"] = 1;
		 			$m->orderConfirm($data);
		 			
		 			$rd['status']= 1;
		 			$rd['msg']= "操作成功";
		 		}else if($refundStatus==3){//维持退款前订单状态，流程继续
		 			$data = array();
		 			$data["orderStatus"] = $order["refundSrcStatus"];
		 			$this->where(array("orderId"=>$orderId))->save($data);
		 			
		 			$content = "维持退款前订单状态，流程继续";
		 		    WSTOrderLog($orderId, $content, session('WST_STAFF.staffId'),1);
		 		    WSTSendMsg($order["userId"], "您的订单【".$order["orderNo"]."】管理员裁定，维持退款前订单状态，流程继续！");
		 		    
		 		    $spUserId = M("shops")->where(array("shopId"=>$order['shopId']))->getField("userId");
		 		    WSTSendMsg($spUserId, "店铺订单【".$order["orderNo"]."】管理员裁定，维持退款前订单状态，流程继续！");
		 		  	$rd['status']= 1;
			 		$rd['msg']= "操作成功";
		 		}
		 		
	 			M()->commit();
	 		} catch (\Exception $e) {
	 			M()->rollback();
	 		}
	 	}
	 	return $rd;
	 }
};
?>