<?php
namespace Home\Model;
/**
 * ============================================================================
 * WSTMall开源商城
 * 官网地址:http://www.wstmall.net
 * 联系QQ:707563272
 * ============================================================================
 * 系统服务类
 */
class SystemModel extends BaseModel {
	protected $tableName = 'sys_configs'; 
    /**
     * 获取商城配置文件
     */
	public function loadConfigs(){  
		$configs = WSTDataFile('mall_config'); 
		if(!$configs){
			$sql = "select fieldCode,fieldValue from __PREFIX__sys_configs order by parentId asc,fieldSort asc";
			$rs = $this->query($sql);
			$configs = array();
			if(count($rs)>0){
				foreach ($rs as $key=>$v){
					if($v['fieldCode']=="hotSearchs"){
						$fieldValue = str_replace("，",",",$v['fieldValue']);
						$configs[$v['fieldCode']] = explode(",",$fieldValue);
					}else{
						$configs[$v['fieldCode']] = $v['fieldValue'];
					}
				}
			}
			//获取风格设置
			$rs = M('Styles')->where(array('isUse'=>1))->field('styleSys,stylePath,id')->select();
			if(!empty($rs)){
				foreach($rs as $key => $v){
					$configs['wst'.$v['styleSys'].'Style'] = $v['stylePath'];
		            $configs['wst'.$v['styleSys'].'StyleId'] = $v['id'];
				}
			}
			unset($rs);
			WSTDataFile('mall_config','',$configs);
		}
		return $configs;
	}
	/**
	 * 获取商品配置分类信息
	 */
    public function loadConfigsForParent(){
		$sql = "select * from __PREFIX__sys_configs where fieldType!='hidden' order by parentId asc,fieldSort asc";
		$rs = $this->query($sql);
		$configs = array();
		if(count($rs)>0){
			foreach ($rs as $key=>$v){
				if($v['fieldType']=='radio' || $v['fieldType']=='select'){
					$v['txt'] = explode('||',$v['valueRangeTxt']);
					$v['val'] = explode(',',$v['valueRange']);
				}
				$configs[$v['parentId']][] = $v;
			}
		}
		unset($rs);
		return $configs;
	}
}