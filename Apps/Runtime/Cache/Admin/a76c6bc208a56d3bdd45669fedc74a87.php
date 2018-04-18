<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo ($CONF['mallTitle']); ?>后台管理中心</title>
      <link href="/pjhl/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
      <link href="/pjhl/Apps/Admin/View/css/AdminLTE.css" rel="stylesheet" type="text/css" />
      <!--[if lt IE 9]>
      <script src="/pjhl/Public/js/html5shiv.min.js"></script>
      <script src="/pjhl/Public/js/respond.min.js"></script>
      <![endif]-->
      <script src="/pjhl/Public/js/jquery.min.js"></script>
      <script src="/pjhl/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
      <script src="/pjhl/Public/js/common.js"></script>
      <script src="/pjhl/Public/plugins/plugins/plugins.js"></script>
   </head>
   <script>
   function editName(obj){
	   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
	   $.post("<?php echo U('Admin/WxUsers/editName');?>",{id:$(obj).attr('dataId'),userRemark:obj.value},function(data,textStatus){
			var json = WST.toJson(data);
			if(json.status=='1'){
				Plugins.setWaitTipsMsg({content:'操作成功',timeout:1000,callback:function(){
				}});
			}else{
				Plugins.closeWindow();
				Plugins.Tips({title:'信息提示',icon:'error',content:'操作失败!',timeout:1000});
			}
		});
   }
   var userTotal,num=0;
   function wxSynchro(){
	   Plugins.confirm({title:'信息提示',content:'您确定与微信用户管理同步吗?</br>(用户越多同步时间将越久)',okText:'确定',cancelText:'取消',okFun:function(){
		   Plugins.closeWindow();
		   Plugins.waitTips({title:'信息提示',content:'正在操作，请稍后...'});
		   $.post("<?php echo U('Admin/WxUsers/synchroWx');?>",'',function(data,textStatus){
					var json = WST.toJson(data);
					if(json.status=='1'){
						userTotal = json.data;
						Plugins.setWaitTipsMsg({content:json.msg,timeout:1000,callback:function(){
						   wxLoad();
						}});
					}else{
						Plugins.closeWindow();
						Plugins.Tips({title:'信息提示',icon:'error',content:json.msg,timeout:1000});
					}
				});
	       }});
   }
   function wxLoad(){
   		id = userTotal[num]['openId'];
   		$.post("<?php echo U('Admin/WxUsers/wxLoad');?>",{id:id},function(data,textStatus){
           			  var json = WST.toJson(data);
           			  if(json.status=='1'){
           				    if(num < userTotal.length-1){
           				    	num++
           				    	var msg = "当前正在同步第"+num+"个用户,进度"+num+"/"+userTotal.length;
           				    	Plugins.waitTips({title:'信息提示',content:msg});
        						wxLoad();
           				        return;
           				    }else{
               			    	num=0;
               					Plugins.setWaitTipsMsg({content:'同步完成',timeout:1000,callback:function(){
               						location.reload();
               					}});
           				    }
           			  }else{
  							Plugins.Tips({title:'信息提示',icon:'error',content:json.msg,timeout:1000});
           			  }
           		});
   }
   </script>
   <body class='wst-page'>
    <form method='post' action='<?php echo U("Admin/WxUsers/index");?>'>
       <div class='wst-tbar'>
       搜索关键词：<input type='text' id='userName' name='userName' class='form-control wst-ipt-15' value='<?php echo ($userName); ?>'/>
  	   <button type="submit" class="btn btn-primary glyphicon glyphicon-search">查询</button>
       </div>
       </form>
       <div class="wst-body">
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
               <th width='40' >会员编号</th>
                 <th width='50'>用户头像</th>
                 <th width='20'>用户昵称</th>
               <th width='100'>用户所在地</th>
               <th width='50'>预留手机号</th>
                 <th width='40'>金币</th>
                 <th width='40'>总金币</th>
               <th width='60'>用户关注时间</th>
                 <th width='60'>上级推荐人</th>
               <th width='60'>用户备注</th>
               <th width='120'>操作</th>
             </tr>
           </thead>
           <tbody>
            <?php if(is_array($Page['root'])): $i = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
               <td><?php echo ($vo["userId"]); ?></td>
                 <td><img style="width: 40px;" src="<?php echo ($vo['userPhoto']); ?>" /></td>
                 <td><?php echo ($vo['userName']); ?>&nbsp;</td>
               <td>
                   <?php echo ($vo['userAreas']); ?>
               </td>
               <td><?php echo ($vo['phone']); ?></td>
                 <td><?php echo ($vo['money']); ?></td>
                 <td><?php echo ($vo['totalMoney']); ?></td>
               <td><?php echo substr($vo['subscribeTime'],0,10);?>&nbsp;</td>
               <td><?php echo ($vo["parentName"]); ?></td>

               <td><input type='text' value='<?php echo ($vo['userRemark']); ?>' onchange='javascript:editName(this)' dataId="<?php echo ($vo["userId"]); ?>" class='form-control'/></td>
                 <td>
                     <a class="btn btn-default glyphicon " href="<?php echo U('Admin/WxUsers/toEdit',array('id'=>$vo['userId']));?>">编辑/查看</a>&nbsp;
                     <a class="btn btn-default glyphicon " href="<?php echo U('Admin/WxUsers/memberOrder',array('userId'=>$vo['userId']));?>">查看订单</a>
                 </td>
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
             <tr>
                <td colspan='14' align='center'><?php echo ($Page['pager']); ?></td>
             </tr>
           </tbody>
        </table>
       </div>
   </body>
</html>