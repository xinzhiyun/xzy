<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <title>公版后台登录</title>
	<!-- jquery -->
    <script type="text/javascript" src="/xzy/project/Public/Admin/js/jquery-1.8.3.min.js"></script>
    <!-- bootstrap css -->
    <link rel="stylesheet" href="/xzy/project/Public/Admin/css/bootstrap.min.css" media="screen" />
	<script type="text/javascript" src="/xzy/project/Public/Admin/layui/layui.js"></script>
     <link rel="stylesheet" href="/xzy/project/Public/Admin/layui/css/layui.css" />
    <!--响应式特性 css-->
    <link href="/xzy/project/Public/Admin/css/bootstrap-responsive.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/xzy/project/Public/Admin/css/style.css" />
    <!-- bootstrap.js -->


	    <style>
		*{
			padding:0;
			margin:0;
			border:none;
		}
		body{
			display: flex;
			width:100%;
			/*height:100%;*/
			background: url(/xzy/project/Public/Admin/img/login.jpg) no-repeat;
			background-size: 100% 100%; 
			border:none;
			font-family:"微软雅黑";
		}
		/*.loginleft{
			width:500px;
			height:500px;
			float:left;
			margin-right:100px;
			margin-top:200px;
			position:relative;
			background: url(/xzy/project/Public/Admin/img/showBg.png) no-repeat 70px bottom;
		}*/
		a,button{ 
		outline:none;
		}
        .form-horizontal p{
			height:100px;
			line-height:100px;
			text-align:center;
            font-size: 57px;
            margin-bottom: 50px;
			letter-spacing:1px;
			color: #02417C;
        }
        .loginBox{
			height:800px;
            /*float:left;*/
            margin:0 auto;
			/*box-shadow: 40px 0 40px -40px #ccc,
						-40px 0 40px -40px #ccc,
						-40px 40px 40px -40px #ccc;*/
        }
        
        .verifyBox{
            width: 80px;
        }
        .loginbtn{
            width: 400px;
            height: 60px;
            line-height: 40px;
            display: block;
			color:#fff;
            margin: 80px auto;
            border:none;
            padding: 0;
			font-size:25px;
            border-radius: 12px;
			font-family:"微软雅黑";
			background: -moz-linear-gradient(top,  #61e5e1 0%, #2b75d4 100%);
			background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,#61e5e1), color-stop(100%,#2b75d4));
			background: -webkit-linear-gradient(top,  #61e5e1 0%,#2b75d4 100%);
			background: -o-linear-gradient(top,  #61e5e1 0%,#2b75d4 100%);
			background: -ms-linear-gradient(top,  #61e5e1 0%,#2b75d4 100%);
			background: linear-gradient(to bottom,  #61e5e1 0%,#2b75d4 100%);
			filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#61e5e1', endColorstr='#2b75d4',GradientType=0 );

        }
        #footer div p{
            position: absolute;
            width: 100%;
            bottom: 0;
            left: 0;
            text-align: center;
        }
		.control-group{
			width:400px;
			height:60px;
			line-height:60px;
			border:1px solid #eae6e6;
			border-radius:8px;
			margin:0 auto;
			background: #fff;
			 -webkit-box-shadow:0 0 8px 1px #eae6e6 inset;  
			-moz-box-shadow:0 0 8px 1px #eae6e6 inset;  
			box-shadow:0 0 8px 1px #eae6e6 inset;
		}
		.control-group:nth-child(4){
			background:rgba(0,0,0,0); 
		}
		.control-group label{
			width:0;
		}
		.control-group span{
			width:60px;
			height:50px;
			margin:5px 0 0 0;
			border-right:1px solid #ccc;
		}
		.control-group .span1{
			background:url(/xzy/project/Public/Admin/img/adminlogo.jpg) no-repeat center center;
			background-size:45%;
		}
		.control-group .span2{
			background:url(/xzy/project/Public/Admin/img/psdlogo.jpg) no-repeat center center;
			background-size:45%;
		}
		.control-group input{
			float:right;
			width:312px;
			height:50px;
			border:0;
			padding-left:20px;
			background:rgba(0,0,0,0);
			font-size:20px;
		}
		.control-group .inputBox{
			height:60px;
			width:150px;
			float:left;
			display:inline-black;
			border:1px solid #eae6e6;
			padding-left:25px;
			border-radius:8px;
			 -webkit-box-shadow:0 0 8px 1px #eae6e6 inset;  
			-moz-box-shadow:0 0 8px 1px #eae6e6 inset;  
			box-shadow:0 0 8px 1px #eae6e6 inset; 
			background: #fff;
			margin-top: 0; 
		}
		.control-group > .inputBox > .verifyBox{
			height:50px;
			width:150px;
			padding-left:20px;
			
		}
		.control-group img{
            width: 200px;
            height: 60px;
            margin-left: 18px;
			
        }
    </style>
</head>
<body>

    <div class="" id="login">
		<!-- <div class="loginleft">
			<img class="logo3" src="/xzy/project/Public/Admin/img/fullwaters.png"/>
			<img class="logo2" src="/xzy/project/Public/Admin/img/washlogo.png"/>
			<img class="logo1" src="/xzy/project/Public/Admin/img/addwater.png"/>
		</div> -->
        <div class="loginBox">
            <form class="form-horizontal" action="" method="post" >
                <p>净水物联网云平台</p>
                <div class="control-group">
                    <span class="span1"></span> <input type="text" name="name" id="inputName" placeholder="账号">
                </div>
                <div class="control-group">
                    <span class="span2"></span><input type="password" name="password" id="inputPassword" placeholder="密码">
                </div>
                <div class="control-group" style="box-shadow:none;border:0">
                        <span class="inputBox"><input type="text" name="code" class="verifyBox" placeholder="验证码"></span><img src="/xzy/project/index.php/Admin/Login/yzm" onclick="this.src='/xzy/project/index.php/Admin/Login/yzm?id=' + Math.random()">
                </div> 
                <div>  
                    <button type="submit" class="loginbtn">登&nbsp;&nbsp;录</button>
                </div>
            </form>
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
	<script>
	$(function(){
		//登录页面左边logo图标轮播淡入淡出效果
		var time;
		var index = 0;
		autoPlay();
		function autoPlay(){
			index=$(".loginleft img").index();
			time=setInterval(function(){
				index++;
				if(index>2){index=0};
				$(".loginleft img").eq(index).fadeIn().siblings().fadeOut();
				
			},1000);
			
		}
		$(".loginbtn").click(function(){
			if($("#inputName").val()==""){
				layui.use('layer', function(){
					var layer = layui.layer;
					layer.msg('请输入登录账号');
				});  
				return false;
			}else if($("#inputPassword").val()==""){
				layui.use('layer', function(){
					var layer = layui.layer;
					layer.msg('请输入登录密码');
				});  
				return false;
			}else if($(".verifyBox").val()==""){
				layui.use('layer', function(){
					var layer = layui.layer;
					layer.msg('请输入验证码');
				});  
				return false;
			}else{
				return true;
			}
		});
	});
	

 
 
	</script>