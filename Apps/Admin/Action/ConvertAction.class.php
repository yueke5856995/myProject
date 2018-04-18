<?php
 namespace Admin\Action;;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 商品控制器
 */
class ConvertAction extends BaseAction{

  
	/**
	 * 分页查询
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('dhcp_00');
		// //获取地区信息
		// $m = D('Admin/Areas');
		// $this->assign('areaList',$m->queryShowByList(0));
		// //获取商品分类信息
		// $m = D('Admin/GoodsCats');
		// $this->assign('goodsCatsList',$m->queryByList());
		$m = D('Admin/Convert');
    	$page = $m->queryByPage();
    	//echo $m->getLastsql();
    	$pager = new \Think\Page($page['total'],$page['pageSize'],I());// 实例化分页类 传入总记录数和每页显示的记录数
    	$page['pager'] = $pager->show();
    	$this->assign('Page',$page);
     	$this->assign('goodsName',I('goodsName'));

  //   	$this->assign('goodsCatId1',I('goodsCatId1',0));
  //   	$this->assign('goodsCatId2',I('goodsCatId2',0));
  //   	$this->assign('goodsCatId3',I('goodsCatId3',0));
     	$this->assign('isSale',I('isSale',-1));
        $this->display("/convert/list");
	}
		/**
	 * 新增/修改操作
	 */
	public function edit() {
		$this->isLogin ();
		$m = D ( 'Admin/Convert' );
		$rs = array ();
		if (I ( 'id', 0 ) > 0) {
			$this->checkPrivelege ( 'dhcp_04' );
			$rs = $m->edit ();
		} else {
			$this->checkPrivelege ( 'dhcp_04' );
			$rs = $m->insert ();
		}
		$this->ajaxReturn ( $rs );
	}

	/**
	 * 查看
     */
	public function toView(){
		$this->isLogin();
		// $this->checkPrivelege('dhcp_04');
		$m = D('Admin/convert');
		if(I('id')>0){
			$object = $m->get();
			$this->assign('object',$object);
		}else{
			die("商品不存在!");
		}
		$this->view->display('/convert/view');
	}
		/**
	 * 跳到新增/编辑商品
	 */
    public function toEdit(){
		$this->isLogin();
		$m = D('Admin/ConvertCats');

    	$this->assign('goodsCatsList',$m->queryByList());
		//$this->checkPrivelege('spsh_00');
		$m = D ( 'Admin/Convert' );
		$object = array ();
		if (I ( 'id', 0 ) > 0) {
			$this->checkPrivelege ( 'dhcp_04' );
			$object = $m->get ();
			$this->assign ( 'object', $object );
			$this->view->display("convert/edit");
		} else {
			$this->checkPrivelege ( 'dhcp_04' );
			$this->view->display("convert/edit");
		}
	
	}
   
    /**
	 * 删除操作
	 */
	public function del() {
		$this->isLogin ();
		$this->checkPrivelege ( 'hylb_03' );
		$m = D ( 'Admin/Convert' );
		$rs = $m->del ();
		$this->ajaxReturn ( $rs );
	}


    /**
	 * 修改商品状态
	 */
	public function changeGoodsStatus(){
		$this->isLogin();
		$this->checkPrivelege('splb_04');
		$m = D('Admin/convert');
		$rs = $m->changeGoodsStatus();
		$this->ajaxReturn($rs);
	}



    /**
	 * 批量设置上架
	 */
	public function changeRecomStatus(){
		$this->isLogin();
		$this->checkPrivelege('splb_04');
		$m = D('Admin/convert');
		$rs = $m->changeRecomStatus();
		$this->ajaxReturn($rs);
	}
	
};
?>