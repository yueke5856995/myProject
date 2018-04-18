<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="favicon.ico"/>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
		<meta http-equiv="Expires" content="0">
		<meta http-equiv="Pragma" content="no-cache">
		<meta http-equiv="Cache-control" content="no-cache">
		<meta http-equiv="Cache" content="no-cache">
        <title><?php echo ($CONF['mallTitle']); ?>后台管理中心</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <link href="/pjhl/Public/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="/pjhl/Apps/Admin/View/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Ionicons -->
        <link href="/pjhl/Apps/Admin/View/css/ionicons.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="/pjhl/Apps/Admin/View/css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <script src="/pjhl/Public/js/jquery.min.js"></script>
        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="/pjhl/Public/js/html5shiv.min.js"></script>
          <script src="/pjhl/Public/js/respond.min.js"></script>
        <![endif]-->
        
        <script src="/pjhl/Public/plugins/bootstrap/js/bootstrap.min.js"></script>
        <script src="/pjhl/Apps/Admin/View/js/jquery-ui.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="/pjhl/Apps/Admin/View/js/AdminLTE/app.js" type="text/javascript"></script>
        <script src="/pjhl/Public/js/common.js"></script>
        <script src="/pjhl/Public/plugins/plugins/plugins.js"></script>
    </head>
    <style>
        .click_color{
            color: #BF2724!important;
        }
    </style>
        <script>
	      $(function () {
	    	  $('#pageContent').height(WST.pageHeight()-98);
	    	  getTask();
	      });
	      $(window).resize(function() {
	    	  $('#pageContent').height(WST.pageHeight()-98);
	      });
	      function logout(){
	    	  Plugins.confirm({ title:'信息提示',content:'您确定要退出系统吗?',okText:'确定',cancelText:'取消',okFun:function(){
	   		     Plugins.closeWindow();
	   		     Plugins.waitTips({title:'信息提示',content:'正在退出系统...'});
	   		     $.post("<?php echo U('Admin/Index/logout');?>",{},function(data,textStatus){
	   		    	  location.reload();
	   		     });
	          }});
	      }
	      function getTask(){
	    	  $.post("<?php echo U('Admin/Index/getTask');?>",{},function(data,textStatus){
	  			    var json = WST.toJson(data);
	  			    if(json.status==1){
	  			    	if(json.goodsNum>0){
	  			    		$('#goodsTips').html(json.goodsNum).show();
	  			    	}else{
	  			    		$('#goodsTips').hide();
	  			    	}
	  			    	if(json.shopsNum>0){
	  			    		$('#shopsTips').html(json.shopsNum).show();
	  			    	}else{
	  			    		$('#shopsTips').hide();
	  			    	}
	  			    	setTimeout("getTask();",10000);
	  			    }
	    	  });
	      }
	      function cleanCache(){
	    	  Plugins.waitTips({title:'信息提示',content:'正在清除缓存，请稍后...'});
	    	  $.post("<?php echo U('Admin/Index/cleanAllCache');?>",{},function(data,textStatus){
	    		  var json = WST.toJson(data);
	    		  if(json.status==1)Plugins.setWaitTipsMsg({content:'缓存清除成功!',timeout:1000});
	    	  });
	      }
	      $(function () {
              $(".treeview-menu li a").click(function () {
                  $(".treeview-menu li a").removeClass("click_color")
                  $(this).addClass("click_color");

              })
          })
	    </script>
    <body class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="#" class="logo" style="padding-buttom:20px">
                <img src="/pjhl/Apps/Admin/View/img/admin_logo.png"/>
            </a>
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="javascript:cleanCache()">
                                <i class="glyphicon glyphicon glyphicon-refresh"></i>
                                <span>清除缓存</span>
                            </a>
                        </li>
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span><?php echo session('WST_STAFF.staffName');?>&nbsp;<i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <img src="/pjhl/<?php echo session('WST_STAFF.staffPhoto');?>" class="img-circle" alt="<?php echo session('WST_STAFF.roleName');?>" />
                                    <p>
                                        <?php echo session('WST_STAFF.staffName');?> - <?php echo session('WST_STAFF.roleName');?>
                                        <small>职员编号：<?php echo ($WST_STAFF["staffNo"]); ?></small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body" style='display:none'>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="<?php echo U('Admin/Staffs/toEditPass');?>" target='pageContent' class="btn btn-default btn-flat">修改密码</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="javascript:logout()" class="btn btn-default btn-flat">退出系统</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="/pjhl/<?php echo session('WST_STAFF.staffPhoto');?>" class="img-circle" alt="<?php echo session('WST_STAFF.staffName');?>" />
                        </div>
                        <div class="pull-left info">
                            <p>Hello, <?php echo session('WST_STAFF.staffName');?></p>
                        </div>
                    </div>
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="treeview">
                            <a href="#">
                                <span>概况</span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo U('Admin/index/tomain');?>" target='pageContent'>系统信息</a></li>
                                <li><a href="<?php echo U('Admin/callback/index');?>" target='pageContent'>用户反馈</a></li>
                            </ul>
                        </li>

                        <?php if(in_array('wxgl_00',$WST_STAFF['grant'])){ ?>
                        <li class="treeview">
                            <a href="#">
                                <span>会员管理</span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="<?php echo U('Admin/WxUsers/index');?>" target='pageContent' >用户管理</a></li>
                            </ul>
                        </li>
                        <?php } ?>

                        <li class="treeview">
                            <a href="#">
                                <span>皮具管理</span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if(in_array('spfl_00',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/GoodsCats/index');?>" target='pageContent'>皮具管理</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php if(in_array('ddgl_00',$WST_STAFF['grant'])){ ?>
                        <li class="treeview">
                            <a href="#">
                                <span>订单管理</span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if(in_array('ddlb_00',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Orders/index');?>" target='pageContent' >订单列表</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <?php if(in_array('bbtj_00',$WST_STAFF['grant'])){ ?>
                        <li class="treeview">
                            <a href="#">
                                <span>报表统计</span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if(in_array('dttj_05',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Reports/toNowReport');?>" target='pageContent' >实时统计</a></li>
                                <?php } ?>
                                <?php if(in_array('dttj_00',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Reports/toStatOrders');?>" target='pageContent' >销售订单统计</a></li>
                                <?php } ?>
                                <?php if(in_array('dttj_00',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Reports/toStatCloOrders');?>" target='pageContent' >售衣订单统计</a></li>
                                <?php } ?>
                                <?php if(in_array('dttj_01',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Reports/statTopSaleGoods');?>" target='pageContent' >商品销售统计</a></li>
                                <?php } ?>
                                 <?php if(in_array('dttj_02',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Reports/statShopSales');?>" target='pageContent' >店铺销售统计</a></li>
                                <?php } ?>
                                 <?php if(in_array('dttj_03',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Reports/statSales');?>" target='pageContent' >销售额统计</a></li>
                                <?php } ?>
                                 <?php if(in_array('dttj_04',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Reports/toStatNewUser');?>" target='pageContent' >新增会员统计</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <?php if(in_array('zjgl_00',$WST_STAFF['grant'])){ ?>
                        <li class="treeview">
                            <a href="#">
                                <span>资金管理</span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if(in_array('txgl_00',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/CashDraws/index');?>" target='pageContent' >提现管理</a></li>
                                <?php } ?>
                                <?php if(in_array('zjls_00',$WST_STAFF['grant'])){ ?>
					            <li><a href="<?php echo U('Admin/LogMoneys/index');?>" target='pageContent' >资金明细</a></li>
					            <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <?php if(in_array('dpgl_00',$WST_STAFF['grant'])){ ?>
                        <li class="treeview">
                            <a href="#">
                                <span>店铺管理</span>
                                <small id='shopsTips' style='display:none' class="badge pull-right bg-green">3</small>
                            </a>
                            <ul class="treeview-menu">
                                <?php if(in_array('dplb_01',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Shops/toEdit');?>" target='pageContent' >添加店铺</a></li>
                                <?php } ?>
                                <?php if(in_array('dplb_00',$WST_STAFF['grant'])){ ?>
					            <li><a href="<?php echo U('Admin/Shops/index');?>" target='pageContent' >店铺列表</a></li>
					            <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <?php if(in_array('wzgl_00',$WST_STAFF['grant'])){ ?>
                        <li class="treeview">
                            <a href="pages/mailbox.html">
                                <span> 文章管理</span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if(in_array('wzlb_00',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Articles/index');?>" target='pageContent' >文章列表</a></li>
                                <?php } ?>
                                <?php if(in_array('wzfl_00',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/ArticleCats/index');?>" target='pageContent' >文章分类</a></li>
                                <?php } ?>
                                <?php if(in_array('wzlb_01',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Articles/toEdit');?>" target='pageContent' >添加文章</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <?php if(in_array('xtgl_00',$WST_STAFF['grant'])){ ?>
                        <li class="treeview">
                            <a href="#">
                                <span>职员管理</span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if(in_array('jsgl_00',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Roles/index');?>" target='pageContent' >角色管理</a></li>
                                <?php } ?>
                                <?php if(in_array('zylb_00',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Staffs/index');?>" target='pageContent' >职员管理</a></li>
                                <?php } ?>
                                <?php if(in_array('dlrz_00',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/LogLogins/index');?>" target='pageContent' >登录日志</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                        <?php if(in_array('scsz_00',$WST_STAFF['grant'])){ ?>
                        <li class="treeview">
                            <a href="#">
                                <span>系统设置</span>
                            </a>
                            <ul class="treeview-menu">
                                <?php if(in_array('scxx_00',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Index/toMallConfig');?>" target='pageContent' >系统信息</a></li>
                                <?php } ?>
                                <?php if(in_array('gggl_00',$WST_STAFF['grant'])){ ?>
                                <li><a href="<?php echo U('Admin/Ads/index');?>" target='pageContent'>图片管理</a></li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php } ?>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        <small>后台管理中心</small>
                    </h1>
                </section>

                <!-- Main content -->
                <section class="content" style='margin:0px;padding:0px;'>
                    <iframe id='pageContent' name='pageContent' src="<?php echo U('Admin/Index/toMain');?>" width='100%' height='100%' frameborder="0"></iframe>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
    </body>
</html>