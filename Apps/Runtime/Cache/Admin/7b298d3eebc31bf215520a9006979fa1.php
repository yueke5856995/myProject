<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="zh-cn">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <title><?php echo ($CONF['shopTitle']['fieldValue']); ?>后台管理中心</title>
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
   <body class='wst-page'>
       <div class="wst-body"> 
        <table class="table table-hover table-striped table-bordered wst-list">
           <thead>
             <tr>
                 <th>
                    序号
                 </th>
                 <th>
                     用户昵称
                 </th>
                 <th>
                    问题类型
                 </th>
                 <th>
                   描述
                 </th>
             </tr>
           </thead>
           <tbody>
           <?php if(empty($Page['root'])): ?><tr>
                   <td colspan='9' align='center'>暂无数据</td>
               </tr><?php endif; ?>
           <?php if(is_array($Page['root'])): $key = 0; $__LIST__ = $Page['root'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($key % 2 );++$key;?><tr>
                 <td>
                     <?php echo ($key); ?>
                 </td>
                 <td>
                     <a href="<?php echo U('WxUsers/toEdit',array('id'=>$vo['userId']));?>"><?php echo ($vo['userName']); ?></a>
                 </td>
                 <td>
                     <?php switch($vo['questionType']): case "1": ?>功能异常<?php break;?>
                         <?php case "2": ?>产品建议<?php break;?>
                         <?php case "3": ?>其他问题<?php break; endswitch;?>
                 </td>
                 <td><?php echo ($vo['content']); ?></td>
             </tr><?php endforeach; endif; else: echo "" ;endif; ?>
             <tr>
                <td colspan='4' align='center'><?php echo ($Page['pager']); ?></td>
             </tr>
           </tbody>
        </table>
       </div>
   </body>
</html>