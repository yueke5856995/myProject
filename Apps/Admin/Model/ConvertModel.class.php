<?php

namespace Admin\Model;

/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 会员服务类
 */
class ConvertModel extends BaseModel {
	/**
	 * 新增
	 */
	public function insert() {

		// 创建数据 
		$id = I ( "id", 0 );
		$data = array ();

		$data ["goodsSn"] = I ( "goodsSn" );
		$data ["goodsImage"] = I ( "goodsImage" );
		$data ["goodsName"] = I ( "goodsName" );
		$data ["goodsStock"] = I ( "goodsStock" );
		$data ["marketPrice"] = I ( "marketPrice" );
		$data ["convertFs"] = I ( "convertFs" );
		$data ["goodsDesc"] = I ( "goodsDesc" );

		$data ["isSale"] = I ( "isSale", 0 );
		$data ["goodsCatId1"] = ( int ) I ( "goodsCatId1", 0 );
		$data ["goodsCatId2"] = ( int ) I ( "goodsCatId2", 0 );
		$data ["goodsCatId3"] = ( int ) I ( "goodsCatId3", 0 );
		
		$data ["createTime"] = date ( 'Y-m-d H:i:s' );
		$rs = $this->add ( $data );
		if (false !== $rs) {
			$rd ['status'] = 1;
		}

		return $rd;
	}
	/**
	 * 修改
	 */
	public function edit() {
		$id = I ( "id", 0 );
		$data = array ();

		$data ["goodsSn"] = I ( "goodsSn" );
		$data ["goodsImage"] = I ( "goodsImage" );
		$data ["goodsName"] = I ( "goodsName" );
		$data ["goodsStock"] = I ( "goodsStock" );
		$data ["marketPrice"] = I ( "marketPrice" );
		$data ["convertFs"] = I ( "convertFs" );
		$data ["goodsDesc"] = I ( "goodsDesc" );

		$data ["isSale"] = I ( "isSale", 0 );
		$data ["goodsCatId1"] = ( int ) I ( "goodsCatId1", 0 );
		$data ["goodsCatId2"] = ( int ) I ( "goodsCatId2", 0 );
		$data ["goodsCatId3"] = ( int ) I ( "goodsCatId3", 0 );
		
		$data ["createTime"] = date ( 'Y-m-d H:i:s' );

		$rs = $this->where ( "convertId=" . $id )->save ( $data );
		if (false !== $rs) {
			$rd ['status'] = 1;
		}
		
		return $rd;
	}
	/**
	 * 获取指定对象
	 */
	public function get() {
		return $this->where ( "convertId=" . ( int ) I ( 'id' ) )->find ();
	}
	 /**
	  * 分页列表[获取已审核列表]
	  */
     public function queryByPage(){
  
     	$goodsName = WSTAddslashes(I('goodsName'));
     	// $goodsCatId1 = (int)I('goodsCatId1',0);
     	// $goodsCatId2 = (int)I('goodsCatId2',0);
     	// $goodsCatId3 = (int)I('goodsCatId3',0);
     	 $isSale = (int)I('isSale',-1);

	 	$sql = "select c.* from __PREFIX__convert c 
	 	      left join __PREFIX__convert_cats cc on c.goodsCatId3=cc.conv_id where status=1";
	 	// if($goodsCatId1>0)$sql.=" and g.goodsCatId1=".$goodsCatId1;
	 	// if($goodsCatId2>0)$sql.=" and g.goodsCatId2=".$goodsCatId2;
	 	// if($goodsCatId3>0)$sql.=" and g.goodsCatId3=".$goodsCatId3;
	 	if($isSale>=0)$sql.=" and c.isSale=".$isSale;
	 	if($goodsName!='')$sql.=" and (c.goodsName like '%".$goodsName."%' or c.goodsSn like '%".$goodsName."%')";
	 	$sql.="  order by convertId desc";   
		return $this->pageQuery($sql);
	 }
	/**
	 * 获取列表
	 */
	public function queryByList() {
		$sql = "select * from __PREFIX__users order by userId desc";
		$rs = $this->find ( $sql );
		return $rs;
	}
	
	/**
	 * 删除
	 */
	public function del() {
		$rd = array (
				'status' => - 1 
		);

		$id = ( int ) I ( 'id' );
		$m = M ( 'convert' );
		$rs = $m->where ( "convertId=" . $id )->save (array("status"=>-1));
		if (false !== $rs) {
			$rd ['status'] = 1;
		}

		return $rd;
	}

	
	/**
	 * 批量删除
	*/
	 public function changeGoodsStatus(){
	 	$rd = array('status'=>-1);
	 	$ids = I('id',0);
	 	$ids = explode(",",$ids);
	 	foreach($ids as $k=>$id){
			$rs = $this->where('convertId='.$id)->save(array('status'=>I("status")));
		}
		if(false !== $rs){
			$rd['status'] = 1;
		}
		return $rd;
	 }


	 	/**
	 * 批量删除
	*/
	 public function changeRecomStatus(){
	 	$rd = array('status'=>-1);
	 	$ids = I('id',0);
	 	$ids = explode(",",$ids);
	 	foreach($ids as $k=>$id){
			$rs = $this->where('convertId='.$id)->save(array('isSale'=>I("status")));
		}
		if(false !== $rs){
			$rd['status'] = 1;
		}
		return $rd;
	 }


};
?>