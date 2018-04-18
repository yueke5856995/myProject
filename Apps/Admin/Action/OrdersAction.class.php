<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 订单控制器
 */
class OrdersAction extends BaseAction{
	/**
 * 分页查询
 */
    public function index(){
        $this->isLogin();
        $this->checkPrivelege('ddlb_00');
        $m = D('Admin/Orders');
        $page = $m->queryByPage();
        $pager = new \Think\Page($page['total'],$page['pageSize'],I());
        $page['pager'] = $pager->show();
        $this->assign('Page',$page);
        $this->assign('shopName',I('shopName'));
        $this->assign('orderNo',I('orderNo'));
        $this->assign('areaId1',I('areaId1',0));
        $this->assign('areaId2',I('areaId2',0));
        $this->assign('areaId3',I('areaId3',0));
        $this->assign('orderStatus',I('orderStatus',-9999));
        $this->display("/orders/list");
    }

    /**
	 * 退款分页查询
	 */
	public function queryRefundByPage(){
		$this->isLogin();
		$this->checkPrivelege('tk_00');
		//获取地区信息
		$m = D('Admin/Areas');
		$this->assign('areaList',$m->queryShowByList(0));
		$m = D('Admin/Orders');
    	$page = $m->queryRefundByPage();
    	$pager = new \Think\Page($page['total'],$page['pageSize'],I());
    	$pager->setConfig('header','件商品');
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
    	$this->assign('shopName',I('shopName'));
    	$this->assign('orderNo',I('orderNo'));
    	$this->assign('isRefund',I('isRefund',-1));
    	$this->assign('areaId1',I('areaId1',0));
    	$this->assign('areaId2',I('areaId2',0));
    	$this->assign('areaId3',I('areaId3',0));
        $this->display("/orders/list_refund");
	}
	/**
	 * 查看订单详情
	 */
	public function toView(){
		$this->isLogin();
		$this->checkPrivelege('ddlb_00');
		$m = D('Admin/Orders');
		if(I('id')>0){
			$object = $m->getDetail();
			$this->assign('object',$object);
		}
		$this->assign('referer',$_SERVER['HTTP_REFERER']);
		$this->display("/orders/view");
	}
    /**
	 * 查看订单详情
	 */
	public function toRefundView(){
		$this->isLogin();
		$this->checkPrivelege('tk_00');
		$m = D('Admin/Orders');
		if(I('id')>0){
			$object = $m->getDetail();
			$this->assign('object',$object);
		}
		$this->assign('referer',$_SERVER['HTTP_REFERER']);
		$this->display("/orders/view");
	}
	/**
	 * 跳到退款页面
	 */
	public function toRefund(){
		$this->isLogin();
		$this->checkPrivelege('tk_04');
		$m = D('Admin/Orders');
	    if(I('id')>0){
			$object = $m->get();

			$this->assign('object',$object);
		}
		$this->display("/orders/refund");
	}
	/**
	 * 退款
	 */
    public function refund(){
		$this->isLogin();
		$this->checkPrivelege('tk_04');
		$m = D('Admin/Orders');
		$rs = $m->refund();
		$this->ajaxReturn($rs);
	}

    /**
     * 修改备注
     */
    public function editName(){
        $this->isLogin();
        $this->checkPrivelege('ddlb_00');
        $m = D('Admin/Orders');
        $rs = array('status'=>-1);
        if(I('id',0)>0){
            $rs = $m->editName();
        }
        $this->ajaxReturn($rs);
    }

    /**
     * 添加图片(20180417自写)
     */
    public function addImg(){
        $this->isLogin();
        $data['orderId'] = I('id');
        $data['goodsThumbs'] = I('Thumbs');
        $data['goodsImg'] = I('Img');
        $rs = M('Order_gallerys')->add($data);
        $this->ajaxReturn($rs);
    }

    /**
     * 删除图片（20180417自写）
     */
    public function delImg(){
        $id = I('id');
        $rs = M('order_gallerys')->where('id='.$id)->setField('status',-1);
        $this->ajaxReturn($rs);
    }

    /**
     * 打印订单（20180417自写）
     */
    public function printOrders(){
        $morders = D('Orders');
        $rs = $morders->getPrintOrders();
        $this->assign("orders",$rs);
        $this->display("order_print");
    }

    /**
     * 修改商品状态（20180418自写）
     */
    public function changeOrdersStatus(){
        $this->isLogin();
        $this->checkPrivelege('splb_04');
        $m = D('Admin/Orders');
        $rs = $m->changeOrdersStatus();
        $this->ajaxReturn($rs);
    }

    /**
     * 返厂维修（20180418自写）
     */
    public function returnFactory(){
        $this->isLogin();
        $this->checkPrivelege('splb_04');
        $m = D('Admin/Orders');
        $rs = $m->returnFactory();
        $this->ajaxReturn($rs);
    }
    /**
     * 发送快递（20180418自写）
     */
    public function tohandle(){
        $this->isLogin();
        $this->checkPrivelege('splb_04');
        $m = D('Admin/Orders');
        if(I('id')>0){
            $object = $m->getDetail();
            $this->assign('object',$object);
        }
        $this->assign('referer',$_SERVER['HTTP_REFERER']);
        $this->display("/orders/handle");
    }

    /**
     * 发送快递
     */
    public function handle(){
        $this->isLogin();
        $this->checkPrivelege('splb_04');
        $data['orderId'] = I('orderId');
        $data['deliverType'] = I('deliverType');
        $data['deliverNo'] = I('deliverNo');
        $data['deliveryTime'] = I('deliveryTime');
        $data['deliverMoney'] = I('deliverMoney');
        $m = D('Admin/Orders');
        $m->save($data);
    }
};
?>