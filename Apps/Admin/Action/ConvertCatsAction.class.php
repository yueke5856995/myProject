<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 商品分类控制器
 */
class ConvertCatsAction extends BaseAction{
	/**
	 * 跳到新增/编辑页面
	 */
	public function toEdit(){
		$this->isLogin();
	    $m = D('Admin/ConvertCats');
    	$object = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('dhfl_02');
    		$object = $m->get(I('id',0));
    	}else{
    		$this->checkPrivelege('dhfl_01');
    		if(I('parentId',0)>0){
    		   $object = $m->get(I('parentId',0));
    		   $object['parentId'] = $object['conv_id'];
    		   $object['catName'] = '';
    		   $object['catSort'] = 0;
    		   $object['conv_id'] = 0;
    		}else{
    		   $object = $m->getModel();
    		}
    	}
    	$this->assign('object',$object);
		$this->view->display('/convertcats/edit');
	}
	/**
	 * 新增/修改操作
	 */
	public function edit(){
		$this->isLogin();
		$m = D('Admin/ConvertCats');
    	$rs = array();
    	if(I('id',0)>0){
    		$this->checkPrivelege('dhfl_02');
    		$rs = $m->edit();
    	}else{
    		$this->checkPrivelege('dhfl_01');
    		$rs = $m->insert();
    	}
    	$this->ajaxReturn($rs);
	}
	/**
	 * 修改名称
	 */
	public function editName(){
		$this->isLogin();
		$m = D('Admin/ConvertCats');
    	$rs = array('status'=>-1);
    	if(I('id',0)>0){
    		$this->checkPrivelege('dhfl_02');
    		$rs = $m->editName();
    	}
    	$this->ajaxReturn($rs);
	}
	/**
	 * 删除操作
	 */
	public function del(){
		$this->isLogin();
		$this->checkPrivelege('dhfl_03');
		$m = D('Admin/ConvertCats');
    	$rs = $m->del();
    	$this->ajaxReturn($rs);
	}
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('dhfl_00');
		$m = D('Admin/ConvertCats');
    	$list = $m->getCatAndChild();
    	$this->assign('List',$list);
        $this->display("/convertcats/list");
	}
	/**
	 * 列表查询
	 */
    public function queryByList(){
    	$this->isLogin();
		$m = D('Admin/ConvertCats');
		$list = $m->queryByList(I('id'));
		$rs = array();
		$rs['status'] = 1;
		$rs['list'] = $list;
		$this->ajaxReturn($rs);
	}
    /**
	 * 显示商品是否显示/隐藏
	 */
	 public function editiIsShow(){
	 	$this->isLogin();
	 	$this->checkPrivelege('dhfl_02');
	 	$m = D('Admin/ConvertCats');
		$rs = $m->editiIsShow();
		$this->ajaxReturn($rs);
	 }
	 /**
	 * 是否推荐
	 */
	 public function editIsFloor()
	 {
	 	$this->isLogin();
	 	$m = D('Admin/ConvertCats');
		$rs = $m->editIsFloor();
		$this->ajaxReturn($rs);
	 }
};
?>