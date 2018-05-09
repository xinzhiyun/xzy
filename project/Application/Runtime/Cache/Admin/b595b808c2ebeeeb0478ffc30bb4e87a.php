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
<link rel="stylesheet" href="http://cdn.static.runoob.com/libs/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="/xzy/project/Public/Admin/css/index/index.css">
<!-- content part -->
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
	<!-- <p class="welcomePage">欢迎来到后台首页</p> -->
	<!-- 内容顶部tab -->
	<div id="hTop">
		<div>
			<span class="h1" id="allincome">0</span>元
			<p>当月总收入</p>
			<a href="<?php echo U('Admin/Users/flow');?>" class="headlink">更多</a>
		</div>
		<div>
			<span class="h1" id="orderNum">0</span>单
			<p>订单数量</p>
			<a href="<?php echo U('Admin/Orders/index');?>" class="headlink">更多</a>
		</div>
		<div>
			<span class="h1" id="repaire">0</span>条
			<p>客户报修</p>
			<a href="<?php echo U('Admin//Feeds/repairlist');?>" class="headlink">更多</a>
		</div>
		<div>
			<span class="h1" id="feeds">0</span>条
			<p>客户建议</p>
			<a href="<?php echo U('Admin/Feeds/feedslist');?>" class="headlink">更多</a>
		</div>
	</div>
	<div id="hBottom">
		<!-- 折线图 -->
		<div id="bottomLeft">
			<!-- 收入 -->
			<div id="income"></div>
			<!-- 新增设备 -->
			<div id="newMoreDevice"></div>
		</div>
		<!-- 地图 -->
		<div id="map">
			<h5>
				<span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
				水机分布图
			</h5>
			<div id="allmap"></div>
		</div>
	</div>
	<script src="/xzy/project/Public/Admin/js/echarts.min.js"></script>
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=wtDIIDonPda8nBPCAPtSqMZj3QBGVuzP"></script>
	<script>
		$('.content').css({height: window.innerHeight - 30 + 'px'});
		var series_data = [[],[]];
		$.ajax({
			url: "<?php echo U('Admin/Index/index');?>", 
			type: 'post',
			success: function(res){
				// console.log(res);
				// 存放收入
				var allincome = 0;

				// 当月收入
				for(var i in res.flows){
					if(res.flows[i]){
						allincome += parseInt(res.flows[i].money)/100
					}
					
				}
				// 总收入
				$("#allincome").text(allincome.toFixed(2));
				// 订单数量
				$("#orderNum").text(parseInt(res.order_filters.total));
				// 报修数量
				$("#repaire").text(parseInt(res.repairs.total));
				// 建议数量
				$("#feeds").text(parseInt(res.feeds.total));
			},
			error: function(res){
				console.log('错误 ',res)
			}
		})
		// 请求后台数据
		$.ajax({
			url: "<?php echo U('Admin/Index/index');?>",
			type: 'post',
			async: false,
			dataType: 'json',
			success: function(res){
				// console.log('连接成功！',res)
				// 当月收入
				for(var i in res.flows){
					if(res.flows[i]){
						series_data[0].push(parseInt(res.flows[i].money)/100)
					}else {
						series_data[0].push(0);
					}
					
				}
				for(var i in res.devices){
					// 遍历新增设备数量
					if(res.devices[i]){
						series_data[1].push(parseInt(res.devices[i].count));

					}else {
						series_data[1].push(0);
					}
				}
			},
			error: function(res){
				console.log('错误 ',res)
			}
		})
	</script>
	<script>
		sessionStorage.setItem('nav_now', '');
		var headlink = $('.headlink');
		// 设置首页点击后的导航定位，高亮
		for(var i=0; i<headlink.length; i++){
			console.log(headlink[i])
			$('.headlink').eq(i).click(function(){
				console.log($(this))
				sessionStorage.setItem('nav_now', this.getAttribute("href"));
			})
		}
		// 收入
		var income = echarts.init(document.getElementById('income'));
		// 新增设备
		var newMoreDevice = echarts.init(document.getElementById('newMoreDevice'));

		var getOption = function(_subtext,legend_data, yAxis_data, series_data, markName){
			return option = {
			    title: {
			        text: '',
			        subtext: _subtext
			    },
			    color: '#00c0ef',
			    legend: {
			        data: [legend_data]
			    },
			    grid: {
			    	containLabel: true
			    },
			    tooltip: {
			        trigger: 'axis',
			        axisPointer: {
			            type: 'cross'
			        }
			    },
			    toolbox: {
			        show: true,
			        right: '5%',
			        feature: {
			            dataView: {readOnly: false},
			            magicType: {type: ['line', 'bar']},
			            restore: {},
			            saveAsImage: {}
			        }
			    },
			    xAxis:  {
			        type: 'category',
			        boundaryGap: false,
			        interval: 1,
			        axisLabel: {
			        	align: 'left',
			        	interval: 0
			        },
			        data: ['01','','','','05','','','','','10','', '','','','15','', '','', '','20','','','','','25','','','','','30','']	//x轴坐标数据
			    },
			    yAxis: {
			        type: 'value',
			        offset: 1,
			        boundaryGap: false,
			        data: yAxis_data,	//y轴坐标数据
			        axisPointer: {
			            snap: true
			        }
			    },
			    visualMap: {
			        show: false,
			        dimension: 0,
			        pieces: [{
			            lte: 6,
			            color: '#00c0ef'
			        }, {
			            gt: 6,
			            lte: 8,
			            color: '#00c0ef'
			        }, {
			            gt: 8,
			            lte: 14,
			            color: '#00c0ef'
			        }, {
			            gt: 14,
			            lte: 17,
			            color: '#00c0ef'
			        }, {
			            gt: 17,
			            color: '#00c0ef'
			        }]
			    },
			    series: [
			        {
			            name: markName,
			            type:'line',
			            data: series_data,
			            markArea: {
			                data: [ [{
			                    name: '早高峰',
			                    xAxis: '2017-12-11'
			                }, {
			                    xAxis: '2017-12-11'
			                }] ]
			            }
			        }
			    ]
			};
		}
		var legend_data = ['收入','新增设备'];
		// y轴坐标数据
		var yAxis_data = [['','',''],[ '0台', '1台', '2台']];
		// 要显示的数据

		if(series_data[0].length){

			//收入 折线图显示
		    income.setOption(getOption('金额','收入',yAxis_data[0],series_data[0], legend_data[0]));
			//新增设备 折线图显示
		    newMoreDevice.setOption(getOption('设备数量','新增设备',yAxis_data[1],series_data[1], legend_data[1]));
		}else{ 
			//收入 折线图显示
		    income.setOption(getOption('金额','收入',yAxis_data[0],series_data[0], legend_data[0]));
			//新增设备 折线图显示
		    newMoreDevice.setOption(getOption('设备数量','新增设备',yAxis_data[1],series_data[1], legend_data[1]));
		}
		/*************************  地图  **************************/
		// 百度地图API功能
		var map = new BMap.Map("allmap");    // 创建Map实例
		map.centerAndZoom(new BMap.Point(113.404, 23.115), 9);  // 初始化地图,设置中心点坐标和地图级别
		//添加地图类型控件
		map.addControl(new BMap.MapTypeControl({
			mapTypes:[
		        BMAP_NORMAL_MAP,
		        BMAP_HYBRID_MAP
		    ]}));	  
		map.setCurrentCity("广州");          // 设置地图显示的城市 此项是必须设置的
		map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放

		var marker = new BMap.Marker(new BMap.Point(116.404, 39.915)); // 创建点
	</script>
	<script>
		window.onresize = function() {
            location.reload(true);
        }
	</script>
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
	<script src="/xzy/project/Public/Admin/js/index/moment-with-locales.min.js"></script>
	<!-- <script src="/xzy/project/Public/Admin/js/index/bootstrap-datetimepicker.js"></script> -->
	<script src="https://cdn.bootcss.com/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script> 
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