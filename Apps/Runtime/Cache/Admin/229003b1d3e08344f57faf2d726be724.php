<?php if (!defined('THINK_PATH')) exit();?><meta charset="utf-8">

   <script>
   function edit(){
	   var params = {};
	   params.content = $.trim($('#content').val());
	   params.id = $('#id').val();
	   var winId = Plugins.waitTips({title:'信息提示',content:'正在提交数据，请稍后...'});
	   $.post("<?php echo U('Admin/CashDraws/handle');?>",params,function(data,textStatus){
			var json = WST.toJson(data);
			if(json.status=='1'){
				Plugins.setWaitTipsMsg({ content:'操作成功',id:winId,timeout:1000,callback:function(){
					 location.href="<?php echo U('Admin/CashDraws/index');?>";
				}});
			}else{
				Plugins.setWaitTipsMsg({ content:json.msg,id:winId,timeout:1000,callback:function(){
					location.href="<?php echo U('Admin/CashDraws/index');?>";
				}});
			}
		});
   }
   
   </script>

       <form name="myform" method="post" id="myform">
        <input type='hidden' id='id' value='<?php echo ($object["cashId"]); ?>'/>
        <table class="table table-hover table-striped table-bordered wst-form">
           <tr>
             <th width='120' align='right'>提现账号：</th>
             <td><?php if($object['accType'] == 1): ?>【支付宝】<?php else: ?>【银行卡】<?php endif; echo ($object['accNo']); ?></td>
           </tr>
           <?php if($object['accType'] == 3): ?><tr>
             <th align='right'>开户地址：</th>
             <td><?php echo ($object['areaName']); ?></td>
           </tr><?php endif; ?>
           <tr>
             <th align='right'>提现人：</th>
             <td><?php echo ($object['accUser']); ?></td>
           </tr>
           <tr>
             <th align='right'>提现金额：</th>
             <td><font color='red'>￥<?php echo ($object['money']); ?></font></td>
           </tr>
           <tr>
             <th align='right'>提现说明：<br/>(用户/商家可看)</th>
             <td>
             <textarea style='width:400px;height:100px' id='content' name='content'></textarea>
             </td>
           </tr> 
           <tr>
             <td colspan='2' style='padding-left:250px;'>
                 <button type="button" class="btn btn-success" onclick='javascript:edit()'>确&nbsp;定</button>
                 <button type="button" class="btn btn-primary" data-dismiss="modal">返&nbsp;回</button>
             </td>
           </tr>
        </table>
       </form>