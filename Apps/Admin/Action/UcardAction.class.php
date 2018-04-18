<?php
 namespace Admin\Action;
/**
 * 用户积分卡控制器
 */
class UcardAction extends BaseAction{

    /**
     * 积分卡流水
     */
    public function UcardWash(){
        $ucard = D('Ucard');
        $page = $ucard ->UcardWash();
        $this->assign('page',$page);
        $this->display('/ucard/ucard_wash');
    }

    /**
     * 封禁卡片
     */
    public function changeUcardStatus(){
        $rd = array('status'=>-1);
        if(I('id',0)==0)return $rd;
        $status = (int)I('status',1);
        //获取子集
        $id = (int)I('id');
        $rs = M('user_card')->where("ucardId = $id")->setField('ucardStatus',$status);
        if($rs !== false)
            $rd['status']=1;
        $this->ajaxReturn($rd);
    }

};
?>