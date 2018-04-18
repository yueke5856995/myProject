<?php
namespace Admin\Model;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 首页服务类
 */
class IndexModel extends BaseModel {
	/**
	 * 获取商品配置分类信息
	 */
    public function loadConfigsForParent(){
		$sql = "select * from ".$this->tablePrefix."sys_configs where fieldType!='hidden' order by parentId asc,fieldSort asc";
		$rs = $this->query($sql);
		$configs = array();
		if(count($rs)>0){
			foreach ($rs as $key=>$v){
				if($v['fieldType']=='radio' || $v['fieldType']=='select'){
					$v['txt'] = explode('||',$v['valueRangeTxt']);
					$v['val'] = explode(',',$v['valueRange']);
				}
				$configs[$v['parentId']][] = $v;
			}
		}
		unset($rs);
		return $configs;
	}
	/**
	 * 保存商城配置信息
	 */
	public function saveConfigsForCode(){
		$rd = array('status'=>-1);
		$sql = "select * from ".$this->tablePrefix."sys_configs where fieldType!='hidden' order by parentId asc,fieldSort asc";
		$rs = $this->query($sql);
		if(!empty($rs)){
			M()->startTrans();
			try {
				$m = M('sys_configs');
				foreach ($rs as $key => $v){
					$result = $m-> where('fieldCode="'.$v['fieldCode'].'"')->setField('fieldValue',I($v['fieldCode']));
					if(false === $result){
					    $rd['status']= -1;
					}
				}
				if((int)I("isDistribut")==0){
					$sql = "update ".$this->tablePrefix."shop_configs set isDistribut = 0";
					$this->execute($sql);
					$sql = "update ".$this->tablePrefix."goods set isDistribut = 0,commission=0";
					$this->execute($sql);
				}
				WSTDataFile("mall_config",'',null);
				M()->commit();
				$rd["status"] = 1;
			} catch (\Exception $e) {
				M()->rollback();
			}
		}
		return $rd;
	}
	/**
	 * 保存授权码
	 */
	public function saveLicense(){
		$rd = array('status'=>-1);
		$m = M('sys_configs');
	    $result = $m-> where('fieldCode="mallLicense"')->setField('fieldValue',I('license'));
		if(false !== $result){
			$rd['status']= 1;
			WSTDataFile("mall_config",'',null);
		}
		return $rd;
	}

    /**
     * 本日动态
     * @return [type] [description]
     */
    public function getTodayInfo(){
        $ret = array();
        //用户
        $weekDate = date('Y-m-d 00:00:00');//本日内
        $ret['newVipUser'] = M('vip_orders')->where('orderStatus=1 and createTime>"'.$weekDate.'"')->count();//新增vip用户
        $ret['sumVipUser'] = M('vip_orders')->where('orderStatus=1 and createTime>"'.$weekDate.'"')->sum('totalMoney');//vip订单总金额
        $ret['newUser'] = M('wx_users')->where('subscribeTime >"'.$weekDate.'"')->count();//新增用户
        //新增(洗衣)订单
        $ret['ordersNew'] = M('orders')->where('orderFlag=1 and orderStatus >0 and createTime>"'.$weekDate.'"')->count();
        $ret['sumOrdersNew'] = M('orders')->where('orderFlag=1 and orderStatus >0 and createTime>"'.$weekDate.'"')->sum('totalMoney');
        //新增(购衣)订单
        $ret['clothesOrdersNew'] = M('orders_clothes')->where('orderFlag=1 and orderStatus >0 and createTime>"'.$weekDate.'"')->count();
        $ret['sumClothesOrders'] = M('orders_clothes')->where('orderFlag=1 and orderStatus >0 and createTime>"'.$weekDate.'"')->sum('totalMoney');

        $ret['totalMoney']=$ret['sumVipUser']+$ret['sumOrdersNew']+$ret['sumClothesOrders'];

        return $ret;
    }

	/**
	 * 一月动态
	 * @return [type] [description]
	 */
	public function getMonthInfo(){
		$ret = array();
        //用户
        $monthDate = date('Y-m-01 00:00:00');//本月内
        $ret['newVipUser'] = M('vip_orders')->where('orderStatus=1 and createTime>"'.$monthDate.'"')->count();//新增vip用户
        $ret['sumVipUser'] = M('vip_orders')->where('orderStatus=1 and createTime>"'.$monthDate.'"')->sum('totalMoney');//vip订单总金额
        $ret['newUser'] = M('wx_users')->where(' subscribeTime>"'.$monthDate.'"')->count();//新增用户
        //新增(洗衣)订单
        $ret['ordersNew'] = M('orders')->where('orderFlag=1 and orderStatus >0 and createTime>"'.$monthDate.'"')->count();
        $ret['sumOrdersNew'] = M('orders')->where('orderFlag=1 and orderStatus >0 and createTime>"'.$monthDate.'"')->sum('totalMoney');
        //新增(购衣)订单
        $ret['clothesOrdersNew'] = M('orders_clothes')->where('orderFlag=1 and orderStatus >0 and createTime>"'.$monthDate.'"')->count();
        $ret['sumClothesOrders'] = M('orders_clothes')->where('orderFlag=1 and orderStatus >0 and createTime>"'.$monthDate.'"')->sum('totalMoney');
        $ret['totalMoney']=$ret['sumVipUser']+$ret['sumOrdersNew']+$ret['sumClothesOrders'];
		return $ret;
	}
    /**
     * 本年动态
     * @return [type] [description]
     */
    public function getYearInfo(){
        $ret = array();
        //用户
        $yearDate = date('Y');//本年内
        $ret['newVipUser'] = M('vip_orders')->where('orderStatus=1 and createTime>"'.$yearDate.'"')->count();//新增vip用户
        $ret['sumVipUser'] = M('vip_orders')->where('orderStatus=1 and createTime>"'.$yearDate.'"')->sum('totalMoney');//vip订单总金额
        $ret['newUser'] = M('wx_users')->where('subscribeTime>"'.$yearDate.'"')->count();//新增用户

        //新增(洗衣)订单
        $ret['ordersNew'] = M('orders')->where('orderFlag=1 and orderStatus >0 and createTime>"'.$yearDate.'"')->count();
        $ret['sumOrdersNew'] = M('orders')->where('orderFlag=1 and orderStatus >0 and createTime>"'.$yearDate.'"')->sum('totalMoney');
        //新增(购衣)订单
        $ret['clothesOrdersNew'] = M('orders_clothes')->where('orderFlag=1 and orderStatus >0 and createTime>"'.$yearDate.'"')->count();
        $ret['sumClothesOrders'] = M('orders_clothes')->where('orderFlag=1 and orderStatus >0 and createTime>"'.$yearDate.'"')->sum('totalMoney');
        $ret['totalMoney']=$ret['sumVipUser']+$ret['sumOrdersNew']+$ret['sumClothesOrders'];
        return $ret;
    }

	/**
	 * 统计信息
	 * @return array 统计信息的数组
	 */
	public function getSumInfo(){
		$ret = array();
        $toDayDate = date('Y-m-d 00:00:00');//本日内
		//新消息
        $ret['newNews']=M('callback')->where('createTime > "'.$toDayDate.'"')->count();
        //总vip会员
        $ret['userVipSum'] = M('wx_users')->where('isVip=1')->count();
        //总会员数
		$ret['userSum'] = M('wx_users')->count();
		//购衣商品
		$ret['clothesGoodsSum'] = M('goods')->where('goodsFlag=1')->count();
		//新购衣商品
        $ret['clothesGoodsNew'] = M('goods')->where('goodsFlag=1 and createTime>"'.$toDayDate.'"')->count();

		//洗衣商品
        $ret['goodsSum']=M('goods_cats')->where('catFlag=1')->count();
        //新洗衣商品
        $ret['goodsNew'] = M('goods_cats')->where('catFlag=1 and createTime >"'.$toDayDate.'"')->count();
		//购衣订单
        $ret['clothesOrdersSum'] = M('orders_clothes')->where('orderFlag=1 and orderStatus >=0')->count();
		//店铺
		$ret['shopSum'] = M('Shops')->where('shopStatus = 1 and shopFlag=1')->count();
		return $ret;
	}
}