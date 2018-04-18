<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 登录日志控制器
 */
class LogRegsAction extends BaseAction{
   /**
	 * 查看
	 */
	public function toView(){
		$this->isLogin();
		$this->checkPrivelege('dlrz_00');
		$m = D('Admin/LogLogins');
		if(I('id')>0){
			$object = $m->get();
			$this->assign('object',$object);
		}
		$this->view->display('/loglogins/view');
	}
	/**
	 * 分页查询 
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('zcrz_00');
		$m = D('Admin/LogRegs');
    	$page = $m->queryByPage();
    	//echo $page;
    	$pager = new \Think\Page($page['total'],$page['pageSize'],I());
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
    	$this->assign('startDate',I('startDate',date('Y-m-d',strtotime('-30 days'))));
    	$this->assign('endDate',I('endDate',date('Y-m-d')));
    	$this->assign('key',I('key'));
        $this->display("/logRegs/list");
	}
};
?>