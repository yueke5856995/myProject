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

   function kd(a) {

       console.log(a.flag)
       if(!a.flag){
           console.log('1');
           a.flag = true;
           console.log($(a).siblings('table').find('.kd'));
           $(a).siblings('table').find('.kd').show();
       }else {
           console.log('2');
           a.flag = false;
           $(a).siblings('table').find('.kd').hide();
       }
   }
   
   </script>
<style>
    .kd{
        display: none;
    }
</style>

       <form name="myform" method="post" id="myform">
        <input type='hidden' id='id' value='<?php echo ($object["cashId"]); ?>'/>
        <table class="table table-hover table-striped table-bordered wst-form">
           <tr>
             <th width='120' align='right'>订单编号：</th>
             <td><?php echo ($object['orderNo']); ?></td>
           </tr>
            <tr>
                <th width='120' align='right'>订单金额：</th>
                <td>
                    <?php if($vo['isPay'] == 0): echo ($object['orderNo']); ?>
                        <?php else: ?>
                        <input type='text' value="<?php echo ($vo['totalMoney']); ?>" class='form-control' id="totalMoney"/><?php endif; ?>
                </td>
            </tr>
            <a class="btn btn-primary glyphicon" onclick="kd(this)">发送快递</a>&nbsp;
           <tr class="kd">
             <th align='right'>快递名称：</th>
             <td><input type='text' value="<?php echo ($vo['deliverType']); ?>" class='form-control' id="deliverType"/></td>
           </tr>
           <tr class="kd">
             <th align='right'>快递单号：</th>
             <td><font color='red'><input type='text' value="<?php echo ($vo['deliverNo']); ?>" class='form-control' id="deliverNo"/></font></td>
           </tr>
           <tr class="kd">
             <th align='right'>快递金额</th>
             <td>
                 <input type='text' value="<?php echo ($vo['deliverMoney']); ?>" class='form-control' id="deliverMoney"/>
             </td>
           </tr>
            <tr class="kd">
                <th align='right'>送出时间</th>
                <td>
                    <input type='text' value="<?php echo ($vo['deliveryTime']); ?>" class='form-control' id="deliveryTime"/>
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