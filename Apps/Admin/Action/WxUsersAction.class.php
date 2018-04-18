<?php
 namespace Admin\Action;;
/**
 * 微信自定义列表控制器
 */
class WxUsersAction extends BaseAction{
	/**
	 * 列表页
	 */
	public function index(){
		$this->isLogin();
		$this->checkPrivelege('wyhgl_00');
		$m = D('Admin/WxUsers');
		$page = $m->queryByPage();
		$pager = new \Think\Page($page['total'],$page['pageSize'],I());
		$page['pager'] = $pager->show();
		$this->assign('userName',I('userName'));
		$this->assign('Page',$page);
		$this->display("/wxusers/list");
	}
    /**
     * 跳到新增/编辑页面
     */
    public function toEdit() {
        $this->isLogin ();
        $m = D ( 'Admin/WxUsers' );
        $object = array ();
        if (($userId = I ( 'id', 0 )) > 0) {
            $this->checkPrivelege ( 'wyhgl_02' );
            $object = $m->get ($userId);
            $address = $m->getaddress($userId);
            $cardList = $m->getCardList($userId);
            $this->assign ( array('object'=>$object,'address'=>$address,'cardList'=> $cardList));
            $this->display ( 'edit' );
        } else {
            $this->checkPrivelege ( 'wyhgl_02' );
            $object = $m->getModel ();
            $object ['userStatus'] = 1;
            $this->assign ( 'object', $object );
            $this->view->display ( '/users/add' );
        }
    }
    /**
     * 新增/修改操作
     */
    public function edit() {
        $this->isLogin ();
        $m = D ( 'Admin/WxUsers' );
        $rs = array ();
        if (I ( 'id', 0 ) > 0) {
            $this->checkPrivelege ( 'wyhgl_02' );
            $rs = $m->edit ();
        }
        $this->ajaxReturn ( $rs );
    }
	
	/**
	 * 修改备注
	 */
	public function editName(){
		$this->isLogin();
		$this->checkPrivelege('wyhgl_02');
		$m = D('Admin/WxUsers');
		$rs = array('status'=>-1);
		if(I('id',0)>0){
			$rs = $m->editName();
		}
		$this->ajaxReturn($rs);
	}

	/**
     * 获取历史订单
     */
	public function memberOrder(){
        $this->isLogin();
        $this->checkPrivelege('wyhgl_00');
        $m = D('Admin/Orders');
        $page = $m->queryMemberOrderByPage();
        $pager = new \Think\Page($page['total'],$page['pageSize'],I());
        $page['pager'] = $pager->show();
        $this->assign('Page',$page);
        $this->display("/orders/list_member");
    }


    /**
     * 获取推荐人列表
     */
    public function children(){
        $this->isLogin();
        $this->checkPrivelege('wyhgl_00');
        $m = D('Admin/WxUsers');
        $page = $m->children();
        $pager = new \Think\Page($page['total'],$page['pageSize'],I());
        $page['pager'] = $pager->show();
        $this->assign('Page',$page);
        $this->display("/wxusers/list");
    }
};
?>