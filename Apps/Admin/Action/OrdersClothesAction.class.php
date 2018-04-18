<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 售衣订单控制器
 */
class OrdersClothesAction extends BaseAction{
	/**
 * 分页查询
 */
    public function index(){
        $this->isLogin();
        $this->checkPrivelege('sylb_00');
        $m = D('Admin/OrdersClothes');
        $page = $m->queryByPage();
        $pager = new \Think\Page($page['total'],$page['pageSize'],I());
        $page['pager'] = $pager->show();
        $this->assign('Page',$page);
        $this->assign('shopName',I('shopName'));
        $this->assign('orderNo',I('orderNo'));
        $this->display("/ordersClothes/list");
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
};
?>