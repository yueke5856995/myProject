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
class OrdersModel extends BaseModel {
	/**
	 * 获取订单详细信息
	 */
	 public function getDetail(){

	 	$id = (int)I('id',0);
		$sql = "select o.*,s.shopName from __PREFIX__orders o
	 	         left join __PREFIX__shops s on o.shopId=s.shopId 
	 	         where o.orderFlag=1 and o.orderId=".$id;
		$rs = $this->queryRow($sql);
		//获取日志信息
		$sql = "select lo.*,u.loginName,u.staffName,s.shopName from __PREFIX__log_orders lo
		         left join __PREFIX__staffs u on lo.logUserId = u.staffId
		         left join __PREFIX__shops s on  u.shopId=s.shopId
		         where orderId=".$id;
		$rs['log'] = $this->query($sql);
		//获取订单图片
		$sql = "select * from __PREFIX__order_gallerys 
			        where orderId = ".$id." and status = 1";
		$rs['gallery'] = $this->query($sql);
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

     	$orderNo = WSTAddslashes(I('orderNo'));
     	$orderStatus = (int)I('orderStatus',0);
     	$userId = I('userId',0);
	 	$sql = "select o.orderId,o.orderNo,o.totalMoney,o.realTotalMoney,o.orderStatus,o.isFactory,o.deliverMoney,o.payType,o.isPay,o.createTime,s.shopId,s.shopName,o.userName,o.userId,o.userAddress,o.userPhone,o.adminRemarks,o.payType from __PREFIX__orders o
	 	         left join __PREFIX__shops s on o.shopId=s.shopId  where o.orderFlag=1 ";
	 	if($orderNo!='')$sql.=" and o.orderId like '%".$orderNo."%' or o.userName like '%".$orderNo."%' or o.userPhone like '%".$orderNo."%' or o.userAddress like '%".$orderNo."%'";
	 	if($orderStatus!=-9999 && $orderStatus!=-100)$sql.=" and o.orderStatus=".$orderStatus;
	 	if($orderStatus==-100)$sql.=" and o.orderStatus in(-6,-7)";
	 	if($userId > 0)$sql.=" and userId =".$userId;
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
     * 用户历史订单分页列表
     */
    public function queryMemberOrderByPage(){

        $userId = I('userId',0);
        $sql = "select o.orderId,o.orderNo,o.totalMoney,o.realTotalMoney,o.useCoupons,o.orderStatus,o.deliverMoney,o.payType,o.createTime,s.shopName,o.userName,o.orderFrom,o.userName,o.userAddress,o.userPhone from __PREFIX__orders o
	 	         left join __PREFIX__shops s on o.shopId=s.shopId  where o.orderFlag=1 ";

        if($userId > 0)$sql.=" and o.userId =".$userId;
        $sql.=" and orderStatus >=0";
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
	  * 获取退款列表
	  */
     public function queryRefundByPage(){

        $shopName = WSTAddslashes(I('shopName'));
     	$orderNo = WSTAddslashes(I('orderNo'));
     	$isRefund = (int)I('isRefund',-1);
     	$areaId1 = (int)I('areaId1',0);
     	$areaId2 = (int)I('areaId2',0);
     	$areaId3 = (int)I('areaId3',0);
	 	$sql = "select o.orderId,o.orderNo,o.totalMoney,o.orderStatus,o.isFactory,o.isRefund,o.deliverMoney,o.payType,o.createTime,s.shopName,o.userName,o.orderFrom from __PREFIX__orders o
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
     * 修改备注
     */
    public function editName(){
        $rd = array('status'=>-1);
        $id = (int)I("id",0);
        $data = array();
        $data["adminRemarks"] = I("adminRemarks");
        $rs = $this->where("orderId=".$id)->save($data);
        if(false !== $rs){
            $rd['status']= 1;
        }
        return $rd;
    }


    /**
     * 获取打印的订单
     */
    public function getPrintOrders(){
        //$shopId = session('WST_USER.shopId');
        $orderIds = self::formatIn(",",I("orderIds"));
        $list = array();
        $where = array("orderFlag"=>1,"orderId"=>array("in",$orderIds));
        $orders = $this->where($where)->select();
        for($i=0,$k=count($orders);$i<$k;$i++){
            $data = array();
            $order = $orders[$i];
            $orderId = $order["orderId"];
            $sql = "select og.orderId, og.goodsId ,g.goodsSn, og.goodsNums, og.goodsName , og.goodsPrice shopPrice,og.goodsThums,og.goodsAttrName,og.goodsAttrName 
					from __PREFIX__goods g , __PREFIX__order_goods og 
					WHERE g.goodsId = og.goodsId AND og.orderId = $orderId";
            $goods = $this->query($sql);

            $logs = M('log_orders')->where(array("orderId"=>$orderId))->select();

            $data["order"] = $order;
            $data["goodsList"] = $goods;
            $data["logs"] = $logs;
            $list[] = $data;
        }

        return $list;
    }

    /**
 * 修改商品状态
 */
    public function changeOrdersStatus(){
        $rd = array('status'=>-1);
        $ids = I('id',0);
        $ids = explode(',',$ids);
        foreach($ids as $k=>$id){
            M()->startTrans();
            try {
                $status = (int)I('status',0);
                switch ($status){
                    case 1:
                        $msg = '受理订单，上门取件';
                        break;
                    case 2:
                        $msg = "取件成功，进行维护";
                        break;
                    case 3:
                        $msg = '维护完成，上门取件';
                        break;
                    case 4:
                        $msg = "评价完成，订单结束";
                        break;
                    default:
                        $msg = "";
                        break;
                }
                $this->orderStatus = $status;
                $rs = $this->where('orderId='.$id)->save();
                if(false !== $rs){
                    $log_data = array(
                        'orderId' => $id,
                        'logUserId' => session('WST_STAFF.staffId'),
                        'logContent' => $msg,
                        'logType' => 1,
                        'logTime' => date('Y-m-d H:i:s'),
                    );
                    M('log_orders')->add($log_data);
                    $rd['status'] = 1;
                }
                M()->commit();
            } catch (\Exception $e) {
                M()->rollback();
            }
        }

        return $rd;
    }

    /**
     * 返厂维修
     */
    public function returnFactory(){
        $rd = array('status'=>-1);
        $ids = I('id',0);
        $ids = explode(',',$ids);
        foreach($ids as $k=>$id){
            M()->startTrans();
            try {
                $status = (int)I('status',0);
                switch ($status){
                    case 1:
                        $msg = '发往厂家维护';
                        break;
                    case 2:
                        $msg = "接受商品";
                        break;
                    case 3:
                        $msg = '维护完成，送回店铺';
                        break;
                    case 4:
                        $msg = "店铺接收，维护结束";
                        break;
                    default:
                        $msg = "";
                        break;
                }
                $this->isFactory = $status;
                $rs = $this->where('orderId='.$id)->save();
                if(false !== $rs){
                    $log_data = array(
                        'orderId' => $id,
                        'logUserId' => session('WST_STAFF.staffId'),
                        'logContent' => $msg,
                        'logType' => 2,
                        'logTime' => date('Y-m-d H:i:s'),
                    );
                    M('log_orders')->add($log_data);
                    $rd['status'] = 1;
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