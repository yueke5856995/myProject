<?php
 namespace Admin\Action;;

 /**
  * Class CallbackAction
  * @package Admin\Action
  */
class CallbackAction extends BaseAction{

    public function index(){
        $callback = M('callback');
        $sql = "select c.*,u.userName from __PREFIX__callback c,__PREFIX__wx_users u where c.userId = u.userId order by createTime desc";
        $page = $callback->pageQuery($sql);
        $pager = new \Think\Page($page['total'],$page['pageSize'],I());
        $page['pager'] = $pager->show();
        $this->assign('Page',$page);
        $this->display("/callback/list");
    }
};
?>