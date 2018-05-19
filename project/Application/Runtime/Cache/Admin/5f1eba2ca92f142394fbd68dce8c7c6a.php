<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>物联网云平台</title>
    <!-- jquery -->
    <!-- bootstrap css -->
    <link rel="stylesheet" href="/xzy/project/Public/Admin/css/bootstrap.css" media="screen" />
    <link rel="stylesheet" href="/xzy/project/Public/Admin/css/bootstrap.min.css" media="screen" />
    <!--响应式特性 css-->
    <link href="/xzy/project/Public/Admin/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/xzy/project/Public/Home/js/theme/default/laydate.css">
    <!-- layui.css -->
    <link rel="stylesheet" href="/xzy/project/Public/Admin/layui/css/layui.css" />
    <link rel="stylesheet" href="/xzy/project/Public/Admin/css/ace.min.css">
    <link rel="stylesheet" href="/xzy/project/Public/Admin/css/style.css">
    <link href="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.45/css/bootstrap-datetimepicker.min.css" rel="stylesheet">
    <script type="text/javascript" src="/xzy/project/Public/Admin/js/jquery.min.js"></script>
    <script src="/xzy/project/Public/Admin/js/index/moment-with-locales.min.js"></script>
    <!-- <script src="/xzy/project/Public/Admin/js/index/bootstrap-datetimepicker.js"></script> -->
    <script src="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script> 
    <style type="text/css">
        .logo:link {
            color: #fff;
        }
        .logo:visited {
            color: #fff;
        }
        .logo:hover {
            color: #ddd;
            text-decoration: none;
        }
        .logo:active {
            color: #fff;
        }
        a:hover {
            color: rgb(25, 99, 170) !important;
        }
        div.pagel{
            margin-top: 20px;
            margin-right: 10px;
            float: right;
            color: #666;
        }

        div.pagel span.current , div.pagel a{
            border: 1px solid #dcdcdc;
            display: block;
            float: left;
            font-size: 12px;
            margin-right: 5px;
            padding: 3px 10px;
            text-decoration: none;
            border-radius: 3px;
        }
 
        div.pagel span.current{
            background: lightblue none repeat scroll 0 0 !important;
            border-color: lightblue;
            color: #ffffff !important;
            display: block;
            float: left;
            font-size: 12px;
            cursor: pointer;
        }

        div.pagel a.prev,div.pagel a.next{
            padding: 3px 4px;
        }
        /*.bootstrap-datetimepicker-widget.dropdown-menu {
            width: auto !important;
        }*/
        #date-start,
        #date-end {
            width: 90px !important; 
        }
        .collapse {
            height: auto !important;
        }
        .bootstrap-datetimepicker-widget table td span {
            display: inline-block !important;
        }
        input[type='text'] {
            padding: 14px 5px !important;
        }
        input[type="file"] {
            padding: 0 !important;
        }
       
    </style>
    
</head> 
<body class="bodyBg">
    <div class="row-fluid" id="header">
        <div class="headerLeft fl"><a class="logo" href="<?php echo U('Admin/Index/index');?>">物联网云平台</a></div>
        <div class="headerRight fr">
            <p class="hintTxt"></p> 
            <div>
                <span class="AccountName"><?php echo ($_SESSION['adminuser']['name']); ?></span>
                <span>欢迎您!</span>
            </div>
            <div class="button modifyPassword">
                <a href="<?php echo U('adminuser/password');?>" style="color: #9d9d9d;;">修改密码</a>
            </div>
            <div class="button bowOut">
                <a href="javascript:void(0)" url="<?php echo U('Login/logout');?>" style="color: #9d9d9d;;" id="_exit">退出</a>
            </div>
        </div>
    </div>
<style>
   input[type='password'] {
    padding: 14px 5px !important; 
</style>
<div class="content">
    <div class="navBox sidebar-collapse fl" id="nav">
    <ul class="nav nav-list">
        <!-- 遍历菜单   开始 -->
        <?php if(is_array($nav_data)): foreach($nav_data as $key=>$v): if(empty($v['_data'])): else: ?>
                <li>
                    <a href="javascript:;" class="dropdown-toggle">
                        <span class="menu-text">
                            <?php echo ($v['name']); ?>
                        </span>
                    </a>
                    <ul class="submenu">
                        <?php if(is_array($v['_data'])): foreach($v['_data'] as $key=>$n): ?><li>
                                <?php switch($n["id"]): default: ?><a class="item" href="<?php echo U($n['mca']);?>" ><i class="icon-double-angle-right"></i><?php echo ($n['name']); ?></a><?php endswitch;?>
                            </li><?php endforeach; endif; ?>
                    </ul>
                </li><?php endif; endforeach; endif; ?>
        <!-- 遍历菜单   结束 -->
    </ul>
</div>
    <div class="row-fluid fl" id="main">
        <div class="tableBox">
            <div class="titleBar">后台管理/<span>添加客户</span></div>
            <div class="formBox">
                <form class="" action="/xzy/project/index.php/Admin/Adminuser/add" method="post" id="_formTable">
                    <div class="control-group">
                        <span>账户名<sub style="color:red;margin-left: 5px;">*</sub></span>
                        <input type="text" name="name" placeholder="请输入登录账号...">
                        <div style="font-size: 12px;text-indent: 38%;margin-top:-10px;color: red">(账户名将不可修改，请确认后再提交)</div>
                    </div>
                    <div class="control-group">
                        <span>密码<sub style="color:red;margin-left: 5px;">*</sub></span>
                        <input type="password" name="password" placeholder="请输入登录密码...">
                    </div>
                    <div class="control-group">
                        <span>确认密码<sub style="color:red;margin-left: 5px;">*</sub></span>
                        <input type="password" name="repassword" placeholder="请再次输入密码...">
                    </div>
                    <div class="control-group">
                        <span>微信APPID<sub style="color:red;margin-left: 5px;">*</sub></span>
                        <input type="text" name="appid" placeholder="请输入微信APPID称...">
                    </div>
                    <div class="control-group">
                        <span>微信APPsecret<sub style="color:red;margin-left: 5px;">*</sub></span>
                        <input type="text" name="appsecret" placeholder="请输入微信APPsecret...">
                    </div>
                    <div class="control-group">
                        <span>微信商户号<sub style="color:red;margin-left: 5px;">*</sub></span>
                        <input type="text" name="shopnum" placeholder="请输入微信商户号...">
                    </div>
                    <div class="control-group">
                        <span>微信商户号密码<sub style="color:red;margin-left: 5px;">*</sub></span>
                        <input type="text" name="shoppwd" placeholder="请输入微信商户号密码...">
                    </div>
                    <div class="control-group">
                        <span>公司名称<sub style="color:red;margin-left: 5px;">*</sub></span>
                        <input type="text" name="company" placeholder="请输入公司名称...">
                    </div>
                    
                    
                    <!-- <div class="btn-groups">
                        <button class="subbtn btns btn-primary oddbtn" type="button">确认</button>
                    </div> -->
                    <div>
                        <input type="submit" name="">
                    </div>
                </form>
            </div>
        </div>
            <div class="row-fluid" id="footer">
        <div class="span8 offset2">
            <p>©2017 - 2018 点球电子 </p>
        </div>
    </div>
    <!-- bootstrap.js -->
    <script type="text/javascript" src="/xzy/project/Public/Admin/js/bootstrap.min.js"></script>
    <!-- layui.js -->
    <script type="text/javascript" src="/xzy/project/Public/Admin/layui/layui.js"></script>
    <!-- 城市三级联动.js -->
    <!-- <script type="text/javascript" src="/xzy/project/Public/Admin/js/area.js"></script> -->
    <!-- 左边导航栏引用 -->
    <script src="/xzy/project/Public/Admin/js/ace.min.js"></script>
    <script src="/xzy/project/Public/Admin/js/adminPublic.js"></script>
    <script type="text/javascript"> 
    	var ua = navigator.userAgent;
    	var isOpera = ua.indexOf("Opera") > -1;
    	if(ua.indexOf('compatible') > -1 && ua.indexOf('MSIE') > -1 && !isOpera){

    		alert('您的浏览体验不佳，请更换新的浏览器，如谷歌浏览器或火狐浏览器！')
    	}
        //提示
        function tip(tip,title,fn){
            layui.use('layer', function(){
                var layer = layui.layer;
                layer.confirm(tip, {icon: 3, title:title}, function(index){
                    fn&&fn()                
                });
            });
        }   
        $('#_exit').click(function(){
            tip('确定退出？','提示',function(){
                window.location.href = "<?php echo U('Login/logout');?>"
            })
            return false
        })
    </script>
	<script type="text/javascript">
		//设置nav高度
	    var windowh=$(window).height();
	    var navH=$("#nav").height(windowh-70);
	    $(function(){
	    	// 按回车键 搜索
	 		$('.form-search input').on('keyup', function(e){
	 			if(e.keyCode == '13'){
	 				$('.form-search').submit();
	 				// 搜索关键字保留
	 				setSearchWord();
	 			}
	 		})
	 		$('#date-start').css({width:'90px;'});
	 		$('#date-end').css({width:'90px;'});
		    /**************** 按时间搜索 -- 开始 ******************/
		    	var newdate = new Date(),
		    	year = newdate.getFullYear(),
		    	month = (newdate.getMonth()+'').length == 1 
		                  ? '0' + (newdate.getMonth()+1)
		                  : newdate.getMonth()-0+1,
		        date = (newdate.getDate()+'').length == 1 
		                  ? '0' + newdate.getDate()
		                  : newdate.getDate();
		    	var now = year +'-'+ month +'-'+ date;
		    	// console.log(year, month, date);
		    	// $("#date-start").val(now);
		    	 // 开始时间
		        var startdate = $("#date-start").datetimepicker({  
		            format: 'YYYY-MM-DD',  
		            locale: moment.locale('zh-cn'),
		        }); 
		        
		        //结束时间
				var enddate = $("#date-end").datetimepicker({
		            format: 'YYYY-MM-DD',  
		            locale: moment.locale('zh-cn')
		        }); 
		         //动态设置最小值  
			    startdate.on('dp.change', function (e) {  
			        enddate.data('DateTimePicker').minDate(e.date);
			    	// $('#date-start').val(time);

			    });  
			    //动态设置最大值  
			    enddate.on('dp.change', function (e) { 
			        startdate.data('DateTimePicker').maxDate(e.date);
			    	// $('#date-end').val(time);  
			        
			    });
		        // $("#date-start").datetimepicker('show');
		        // $("#date-end").datetimepicker('show');

		    /**************** 按时间搜索 -- 结束 ******************/


		    /********** 设置导航栏当前位置 高亮 选中 -- 开始 ***********/

			    var href = location.href, nowPos;   // nowPos：当前文件地址
			    if(href.indexOf('html') > -1){
			        nowPos = href.substring(href.lastIndexOf('/Admin/')+7, href.indexOf('html')+4);
			    }else{
					nowPos = href.substring(href.lastIndexOf('/Admin/')+7);
			    }
			  	// console.log('nowPos: ',nowPos);
			    // 导航下的所有连接
			    var alink = $(".item");
			    // console.log('alink: ',alink)
			    // 高亮 选择当前地址的导航
			    for(var i=0; i<alink.length; i++){
			    	alink[i].onclick = function(){
			    		sessionStorage.setItem('nav_now', this.getAttribute("href"));
			    		
			    	}
			    }
			    if(sessionStorage.getItem('nav_now')){
			    	var thisModule, thisMenu, thisSelect;		// 当前模块， 当前菜单
			    	var nowurl = sessionStorage.getItem('nav_now'),
			    		now = nowurl.substring(nowurl.lastIndexOf('/Admin/')+7, nowurl.indexOf('html')+4);
			    	console.log(now, nowurl);
			    	for(var i=0; i<alink.length; i++){
			    		if(now == 'Index/index.html'){
					    	return
					    	// 以下代码不在首页生效
					    }
			    		if(alink[i].getAttribute('href').indexOf(now) > -1){
			    			thisSelect = alink[i];
			    			thisMenu = alink[i].parentNode.parentNode;
			    			thisModule = alink[i].parentNode.parentNode.parentNode;

			    			thisSelect.style.color = '#1963aa';
			    			thisMenu.style.display = 'block';
			    			thisModule.setAttribute('class', 'open');

			    			// console.log('thisSelect: ', thisSelect);
			    			// console.log('thisMenu: ', thisMenu);
			    			// console.log('thisModule: ', thisModule);
			    		}
			    	}
			    }

		    /********** 设置导航栏当前位置 高亮 选中 -- 结束 ***********/

		});

	</script>
</body>
</html>
    </div>
</div>
<!-- <script src="/xzy/project/Public/Home/js/public.js"></script>  -->
<!-- <script>
$(function(){
    //验证
    $('.oddbtn').on('click',function(){
        var user = $('input[name=user]').val().trim();
        var name = $('input[name=name]').val().trim();
        var phone = $('input[name=phone]').val().trim();
        var csphone = $('input[name="csphone"]').val().trim();
        var email = $('input[name=email]').val().trim();
        var address = $('textarea[name="detail"]').val().trim();
        var idcard = $('input[name=idcard]').val().trim();

        if(!user.trim()){
            layuiHint('请输入登录账号');
            return

        }else if(/[`~!@#$^&*()=|{}':;',\[\].<>/?~！@#￥……&*（）——|{}【】\s‘；：”“'。，、？]/.test(user)){
            layuiHint('账户名不能输入特殊字符');
            return
        }

        if(!name){
            layuiHint('请输入昵称');
            return

        }else if(/[`~!@#$^&*()=|{}':;',\[\].<>/?~！@#￥……&*（）——|{}【】\s‘；：”“'。，、？]/.test(name)){
            layuiHint('用户昵称不能输入特殊字符');
            return
        }

        if(!phone){
            layuiHint('请输入手机号');
            return

        }else if(!/^1[3,4,5,6,7,8]\d{9}$/.test(phone)){
            layuiHint('请输入正确的手机号码');
            return
        }

        if(!csphone){
            layuiHint('请输入客服电话');
            return

        }else if(!/^(\(\d{3,4}\)|\d{3,4}-|\s)?\d{7,14}$/.test(csphone)){
            layuiHint('请输入正确的客服电话');
            return
        }

        if(!email){
            layuiHint('请输入邮箱');
            return

        }else if(!/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/.test(email.trim())){
            layuiHint('请输入正确的email');
            return
        }

        if($('#s_province').val()==""||$('#s_city').val()==""||$('#s_county').val()==""){
            layuiHint('请把地址填写完整');    
            return

        }else{
            $(".addressValue").val($('#s_province').val() + " " + $('#s_city').val() + " " + $('#s_county').val());
        }

        if(!address){
            layuiHint('请输入详细地址！');
            return

        }else if(!/^[\w\-\u4e00-\u9fa5]{2,255}$/u.test(address.replace(/^(\s)|(\s*)/g,''))){
            layuiHint('地址只能由中文、英文、数字组成！');
            return
        }

        if(!idcard){
            layuiHint('请输入身份证号码');
            return

        }else if(!/^\d{6}(18|19|20)?\d{2}(0[1-9]|1[012])(0[1-9]|[12]\d|3[01])\d{3}(\d|[xX])$/.test(idcard.trim())){
            layuiHint('请输入正确的身份证号码');
            return
        }

        $('#_formTable').submit();
    })
    //城市三级联动
    _init_area();

})
</script> -->