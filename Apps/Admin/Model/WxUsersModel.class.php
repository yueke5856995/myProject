<?php
 namespace Admin\Model;
 use Api\Action\VipAction;

 /**
 * 微信小程序会员列表服务类
 */
class WxUsersModel extends BaseModel {	  
	  /**
	   * 分页列表
	   */
	  public function queryByPage(){
	  	$sql = "select * from __PREFIX__wx_users where userFill=1 ";
	  	if(I('userName')!='')$sql.=" and userName or phone LIKE '%".WSTAddslashes(I('userName'))."%'";
	  	if(I('isVip')!='')$sql.="  and isVip =". I('isVip');
	  	$sql.="  order by subscribeTime desc";
	  	$rs = $this->pageQuery($sql);
	  	foreach ($rs['root'] as &$v){
	  	    $parentInfo = $this->find($v['parent_id']);
	  	    if($parentInfo){
	  	        $v['parent_id']=$parentInfo['userId'];
	  	        $v['parentName']=$parentInfo['userName'];
            }
        }
	  	return $rs;

	  }
	  
	  /**
	   * 获取指定对象
	   */
	  public function get($userId){
	  	return $this->where("userFill = 1 and userId=".$userId)->find();
	  }
    /**
     * 获取指定对象地址
     */
    public function getaddress($userId){
        return M('userAddress')->where("addressFlag = 1 and userId=".$userId)->order('isDefault desc')->select();
    }

    /**
     * 获取指定对象卡片列表
     */
    public function getCardList($userId){
        return M('user_card')->where("ucardFlag = 1 and userId=".$userId)->order('createTime desc')->select();

    }

	  
	  /**
	   * 修改备注
	   */
	  public function editName(){
	  	$rd = array('status'=>-1);
	  	$id = (int)I("id",0);
	  	$data = array();
	  	$data["userRemark"] = I("userRemark");
  		$rs = $this->where("userFill=1 and userId=".$id)->save($data);
  		if(false !== $rs){
  			$info = $this->get($id);
  			$wdata = array();
  			$wdata["openid"] = $info["openId"];
  			$wdata["remark"] = $info["userRemark"];
  			$wdata = json_encode($wdata,JSON_UNESCAPED_UNICODE);
  			$wx = WXAdmin();
  			$data = $wx->wxUpdateremark($wdata);
  			$rd['status']= 1;
  		}
	  	return $rd;
	  }

    /**
     * 修改
     */
    public function edit() {
        $rd = array (
            'status' => - 1
        );
        $id = ( int ) I ( 'id', 0 );
        // 修改数据
        $data = array ();
        if ($this->checkEmpty ( $data, true )) {
            $data ["userName"] = I ( "userName" );
            $data ["userSex"] = ( int ) I ( "userSex", 0 );
            $data ["money"] = I ( "money" );
            $data ["balance"] = I ( "balance" );
            $data ["phone"] = I ( "phone" );
            $data ["freeWash"] = I ( "freeWash" );
            $rs = $this->where ( "userId=" . $id )->save ( $data );
            if (false !== $rs) {
                $rd ['status'] = 1;
            }
        }
        return $rd;
    }

    public function children(){
        $userId = I('userId');
        $sql = "select * from __PREFIX__wx_users where userFill=1 and parent_id = $userId";
        $sql.="  order by subscribeTime desc";
        $rs = $this->pageQuery($sql);
        foreach ($rs['root'] as &$v){
            $parentInfo = $this->find($v['parent_id']);
            if($parentInfo){
                $v['parent_id']=$parentInfo['userId'];
                $v['parentName']=$parentInfo['userName'];
            }
        }
        return $rs;
    }
};
?>