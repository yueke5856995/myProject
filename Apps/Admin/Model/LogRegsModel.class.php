<?php
 namespace Admin\Model;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 登陆日志服务类
 */
class LogRegsModel extends BaseModel {
     /**
	  * 新增
	  */
	 public function insert(){
	 	$rd = array('status'=>-1);
	 	$id = (int)I("id",0);
		$data = array();
		$data["loginId"] = (int)I("loginId");
		$data["staffId"] = (int)I("staffId");
		$data["loginTime"] = date('Y-m-d H:i:s');
		$data["loginIp"] = get_client_ip();;
		foreach ($data as $key=>$v){
			if(trim($v)==''){
				$rd['status'] = -2;
				return $rs;
			} 
		}
		$m = M('log_staff_logins');
		$rs = $m->add($data);
		if($rs){
			$rd['status']= 1;
		}
		return $rd;
	 } 
	 /**
	  * 获取指定对象
	  */
     public function get(){
	 	$m = M('log_staff_logins');
		return $m->where("loginId=".(int)I('id'))->find();
	 }
	 /**
	  * 分页列表
	  */
     public function queryByPage(){
        $m = M('users');
        $key = WSTAddslashes(I('key'));
	 	$sql = "select loginName,createTime from __PREFIX__users where createTime between'".I('startDate',date('Y-m-d',strtotime('-30 days')))." 00:00:00' and '".I('endDate',date('Y-m-d'))." 23:59:59'";
	 	if($key!='')$sql.=" and (loginName like '%".$key."%')";
	 	$sql.=" order by createTime desc";
		return $m->pageQuery($sql);
		// return $m->getlastsql();
	 }
	 /**
	  * 获取列表
	  */
	  public function queryByList(){
	     $m = M('users');
	     $sql = "select * from __PREFIX__users order by createTime desc";
		 return $m->find($sql);
	  }
};
?>