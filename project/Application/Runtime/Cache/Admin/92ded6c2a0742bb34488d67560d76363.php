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
<div class="content" style="background-color: #ECF0F5">
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
        <div class="row-fluid fl" id="main" style="margin-bottom: 50px">
            <div style="padding: 0 15px;">
                 <div class="titleBar">设备管理/<span>设备列表/设备详情</span>&nbsp;&nbsp;&nbsp;&nbsp;<a href="javascript:history.go(-1)" class="btn btn-info">&lt;&lt;返回</a></div>
                <h1 class="text-center _dev">当前设备：</h1>
                <div class="clearfix" style="background-color: #ECF0F5;margin-left: 1%">
                    <div style="margin: 15px 0;" class="_dive">
                        <div class="_four _margin-right _DD4B39 text-center">
                            <h1 class="_h1 _text H1devicestause">--</h1>
                            <span class="_text">设备状态</span>
                        </div>
                        <div class="_four _margin-right _337AB7 text-center">
                            <h1 class="_h1 _text originalWater">--</h1>
                            <span class="_text">原水TDS值(ppm)</span>
                        </div>
                        <div class="_four _margin-right _05CE3E text-center">
                            <h1 class="_h1 _text cleanWater">--</h1>
                            <span class="_text">纯水TDS值(ppm)</span>
                        </div>
                        <div class="_four _77E1FB text-center">
                            <h1 class="_h1 _text leaseMode">--</h1>
                            <span class="_text">租赁模式</span>
                        </div>
                    </div> 
                </div>
                <div class="clearfix">
                    <div class="_float_left">
                        <div class="_device">
                                <h3>滤芯信息(剩余值)</h3>
                            <table class="table _table btnBox" style="margin-bottom: 0">

                            </table>
                        </div>

                        <div class="_device">
                            <div>
                                <h3>设备操作</h3>
                                <div class="btnBox" style="padding: 0 100px"><button class="btn btn-primary clickBtn switchText" style="padding:15px 25px">关机</button></div>
                            </div>
                        </div>
                    </div>
                    <div class="_float_right">
                        <div style="padding: 5px 10px;" class="_device">
                            <div>
                                <h3>设备信息</h3>
                            </div>
                            <table class="table _table2">

                            </table>
                        </div>
                    </div>
                </div>
                <div class="_tb">
                    <h2>经销商信息列表</h2>
                    <table class="table">
                        <thead>
                            <tr>
                                <td>经销商ID</td>
                                <td>经销商姓名</td>
                                <td>手机号码</td>
                                <td>地址</td>
                                <td>管理级别</td>
                                <td>邮箱</td>
                            </tr>
                        </thead>
                        <tbody class="_vendor">
                            
                        </tbody>
                    </table>
                </div>
              
                
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
</div>

<script>
$(function(){
    //页面数据渲染
    var deviceId=0;
    var devicestause = {//设备状态列表
        '0':'制水',
        '1':'冲洗',
        '2':'水满',
        '3':'缺水',
        '4':'漏水',
        '5':'检修',
        '6':'欠费停机',
        '7':'关机',
        '8':'开机(仅命令)'
    }
    var leasingmode = {//租赁模式列表
        '0':'零售型',
        '1':'按流量计费',
        '2':'按时间计费',
        '3':'时长和流量计费',
        '4':'商务型'
    }
    var filtermode = {//滤芯模式列表
        '0':'按时长',
        '1':'按流量',
        '2':'时长和流量'
    }
    var $status = $('._dive div h1');
    var $_table = $('._table');
    var $_table2 = $('._table2');
    
    $.ajax({
        url:'/xzy/project/index.php/Admin/Devices/deviceDetail?code=' + location.hash.slice(1),
        type:'get',
        error:function(err){
            //console.log('err',err)
        },
        success:function(data){
            deviceId=data.statu.device_code;
            console.log(data);
            $('._dev').append(location.hash.slice(1));//设置当前设备编号

            //设置设备状态
            $status.eq(0).html(devicestause[data.statu.devicestause]?devicestause[data.statu.devicestause]:'--');
            //设置原水值
            $status.eq(1).html(data.statu.rawtds?data.statu.rawtds:'--');
            //设置净水值
            $status.eq(2).html(data.statu.puretds?data.statu.puretds:'--');
            //设置租赁模式
            $status.eq(3).html(data.statu.leasingmode?leasingmode[data.statu.leasingmode]:'--');
            //滤芯信息数据表
            //滤芯背景颜色

            var _color = ['#05CE3E','#005384','#DD4B39','#337AB7','#5CB85C','#77E1FB'];

            data.filterInfo.forEach(function(el,i){
                //按流量计算百分比值
                console.log(el.flowlife);
                var persent1 = Number(data.statu[('reflowfilter'+(i+1))])/Number(el.flowlife?el.flowlife:0)*100;
                //按时间计算百分比值
                var persent2 = Number(data.statu[('redayfilter'+(i+1))])/Number(el.timelife?el.timelife:0)*100;
                if(persent1>100){
                    persent1=100; 
                }else{
                    persent1=persent1;
                };
                if(persent2>100){
                    persent2=100;
                }else{
                    persent2=persent2;
                }
                var persent = Math.min(persent1,persent2);
                //比较大小
                var _bool = persent == persent1?true:false;
                var width = Math.min(persent,100);
                var html='';
                if(data.statu.filtermode=="0"){
                    //按时间
                    html+= '<tr>'+
                        '<td style="width:10%;">'+el.filtername+'</td>'+
                        '<td style="width:70%">'+
                        '<div class="_progress">'+
                        '<div class="_progress_" style="width: '+persent2+'%;background-color:'+_color[i%_color.length]+'">'+
                        '</div>'+
                        '</div>'+
                        '<span style="display:block;text-align:center">还剩'+persent2.toFixed(2)+'%</span>'+
                        '</td>'+
                        '<td>'+Number(data.statu[('redayfilter'+(i+1))])+'/'+el.timelife+'</td>'+
                        '<td>'+'<button class="btn btn-info clickBtn" name="'+ el.filtername+'">复位</button><input type="hidden" value='+i+'></td>'+
                        '</tr>';
                }else if(data.statu.filtermode=="1"){
                    //按流量
                    html+= '<tr>'+
                        '<td style="width:10%;">'+el.filtername+'</td>'+
                        '<td style="width:70%">'+
                        '<div class="_progress">'+
                        '<div class="_progress_" style="width: '+persent1+'%;background-color:'+_color[i%_color.length]+'">'+
                        '</div>'+
                        '</div>'+
                        '<span style="display:block;text-align:center">还剩'+persent1.toFixed(2)+'%</span>'+
                        '</td>'+
                        '<td>'+Number(data.statu[('reflowfilter'+(i+1))])+'/'+el.flowlife+'</td>'+
                        '<td>'+'<button class="btn btn-info clickBtn" name="'+ el.filtername+'">复位</button><input type="hidden" value='+i+'></td>'+
                        '</tr>';
                }else if(data.statu.filtermode=="2"){
                    //按流量/时长
                    html+= '<tr>'+
                        '<td style="width:10%;">'+el.filtername+'</td>'+
                        '<td style="width:70%">'+
                        '<div class="_progress">'+
                        '<div class="_progress_" style="width: '+width+'%;background-color:'+_color[i%_color.length]+'">'+
                        '</div>'+
                        '</div>'+
                        '<span style="display:block;text-align:center">还剩'+width.toFixed(2)+'%</span>'+
                        '</td>'+
                        '<td>'+(_bool?Number(data.statu[('reflowfilter'+(i+1))]):Number(data.statu[('redayfilter'+(i+1))]))+'/'+(_bool?el.flowlife:el.timelife)+'</td>'+
                        '<td>'+'<button class="btn btn-info clickBtn" name="'+ el.filtername+'">复位</button><input type="hidden" value='+i+'></td>'+
                        '</tr>';
                }
                $_table.append(html);
            })
            //设备信息数据表
            var _devicestause = devicestause[data.statu.devicestause]?devicestause[data.statu.devicestause]:'--'
            var _address = data.statu.address?data.statu.address:'--'
            var _deviceid = location.hash.slice(1)
            var _puretds = data.statu.puretds?data.statu.puretds:'--'
            var _rawtds = data.statu.rawtds?data.statu.rawtds:'--'
            var _reflow = data.statu.reflow?data.statu.reflow:'--'
            var _reday = data.statu.reday?data.statu.reday:'--'
            //设备信息页面数据渲染
            var html2 = '<tr>'+
                '<td>'+'设备编号'+'</td>'+
                '<td class="tdDeviceId">'+_deviceid+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>'+'设备状态'+'</td>'+
                '<td class="tdDeviceStause">'+_devicestause+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>'+'安装时间'+'</td>'+
                '<td>'+(data.statu.addtime?new Date(data.statu.addtime*1000).toLocaleString():'--')+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>'+'安装地址'+'</td>'+
                '<td>'+_address+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>'+'原水值(ppm)'+'</td>'+
                '<td  class="tdOriginalWater">'+_rawtds+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>'+'纯水值(ppm)'+'</td>'+
                '<td  class="tdCleanWater">'+_puretds+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>'+'剩余流量 (L)'+'</td>'+
                '<td class="reflow">'+_reflow+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>'+'剩余天数 (D)'+'</td>'+
                '<td class="reday">'+_reday+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>'+'租赁模式'+'</td>'+
                '<td class="tdLeaseMode">'+(data.statu.leasingmode?leasingmode[data.statu.leasingmode]:'--')+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>'+'激活状态'+'</td>'+
                '<td>'+(Number(data.statu.alivestause)?'已激活':'未激活')+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>'+'滤芯模式'+'</td>'+
                '<td class="tdFilterMode">'+(data.statu.filtermode?filtermode[data.statu.filtermode]:'--')+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>'+'温度(℃)'+'</td>'+
                '<td class="temperature">'+(data.statu.temperature?data.statu.temperature:'--')+'</td>'+
                '</tr>'+
                '<tr>'+
                '<td>'+'更新时间'+'</td>'+
                '<td>'+(data.statu.updatetime?new Date(data.statu.updatetime*1000).toLocaleString():'--')+'</td>'+
                '</tr>'
            $_table2.append(html2);
            //经销商列表
            if(data.vendor){
                var _html3 = '<tr>'+
                    '<td>'+(data.vendor.id?data.vendor.id:'--')+'</td>'+
                    '<td>'+(data.vendor.name?data.vendor.name:'--')+'</td>'+
                    '<td>'+(data.vendor.phone?data.vendor.phone:'--')+'</td>'+
                    '<td>'+(data.vendor.address?data.vendor.address:'--')+'</td>'+
                    '<td>'+(data.vendor.leavel?data.vendor.leavel:'--')+'</td>'+
                    '<td>'+(data.vendor.email?data.vendor.email:'--')+'</td>'+
                    '</tr>'
                $('._vendor').html(_html3)
            }

            //alert($(".tdDeviceId").html());
            //    success
            //websoket
            //1.建立连接
            var timer=null;
            var numAdd=0;
            var identify=0;
            var websoket=new WebSocket("ws://120.27.12.1:6001");
            var PackNum=0;//包数据
            var CmdList=[];//命令队列
            websoket.onopen=function(){
                //包数据
                ajson={
                    "DeviceID":deviceId,
                    "PackType":"login",
                    "Vison":0,
                };
                websoket.send(JSON.stringify(ajson));
                setTimeout(function(){
                    ajson.PackType="Select";
                    websoket.send(JSON.stringify(ajson));
                    setInterval(function(){
                        websoket.send(JSON.stringify(ajson));
                    },10000);
                },1000);
            }
            //实时接收数据
            websoket.onmessage=function(res){
                numAdd=0;
                var dataList=JSON.parse(res.data);
                console.log(dataList);
                if(dataList.PackType=="Select")
                {
                    numAdd=0;
                    $_table.html("");
                    var filterMode='';
                    console.log(dataList);
                    data.filterInfo.forEach(function(el,i){
                        //console.log(data.filterInfo.length);
                        //按流量计算百分比值
                        var ReFlowFilter=0;//剩余
                        var ReDayFilter=0;
                        if(parseInt(dataList['ReFlowFilter'+(i+1)])){
                            ReFlowFilter=parseInt(dataList['ReFlowFilter'+(i+1)]);
                        }else{
                            ReFlowFilter=0;
                        }
                        if(parseInt(dataList['ReDayFilter'+(i+1)])){
                            ReDayFilter=parseInt(dataList['ReDayFilter'+(i+1)]);
                        }else{
                            ReDayFilter=0;
                        }
                        var persent1 = ReFlowFilter/parseInt(el.flowlife)*100;
                        //按时间计算百分比值
                        var persent2 = ReDayFilter/parseInt(el.timelife)*100;
                        if(persent1>100){
                            persent1=100;
                        }else{
                            persent1=persent1;
                        };
                        if(persent2>100){
                            persent2=100;
                        }else{
                            persent2=persent2;
                        }
                        var persent = Math.min(persent1,persent2);
                        //比较大小
                        var _bool = persent == persent1?true:false;
                        var width = Math.min(persent,100);
                        var html='';

                        if(parseInt(dataList['ReDayFilter1']) > 0 && parseInt(dataList['ReFlowFilter1']) <= 0 ){
                            //按时间
                            filterMode='按时长';
                            html+= '<tr>'+
                                '<td style="width:10%;">'+el.filtername+'</td>'+
                                '<td style="width:70%">'+
                                '<div class="_progress">'+
                                '<div class="_progress_" style="width: '+persent2.toFixed(2)+'%;background-color:'+_color[i%_color.length]+'">'+
                                '</div>'+
                                '</div>'+
                                '<span style="display:block;text-align:center">还剩'+persent2.toFixed(2)+'%</span>'+
                                '</td>'+
                                '<td>'+ReDayFilter+'/'+el.timelife+'</td>'+
                                '<td>'+'<button class="btn btn-info clickBtn washBtn" name="'+ el.filtername+'">复位</button><input type="hidden" value='+i+'></td>'+
                                '</tr>';

                        }else if(parseInt(dataList['ReFlowFilter1'])>0 && parseInt(dataList['ReDayFilter1'])<=0){
                            //流量模式
                            //alert(124);
                            filterMode='按流量';
                            html+= '<tr>'+
                                '<td style="width:10%;">'+el.filtername+'</td>'+
                                '<td style="width:70%">'+
                                '<div class="_progress">'+
                                '<div class="_progress_" style="width: '+persent1.toFixed(2)+'%;background-color:'+_color[i%_color.length]+'">'+
                                '</div>'+
                                '</div>'+
                                '<span style="display:block;text-align:center">还剩'+persent1.toFixed(2)+'%</span>'+
                                '</td>'+
                                '<td>'+ReFlowFilter+'/'+el.timelife+'</td>'+
                                '<td>'+'<button class="btn btn-info clickBtn washBtn" name="'+ el.filtername+'">复位</button><input type="hidden" value='+i+'></td>'+
                                '</tr>';

                        }else if(parseInt(dataList['ReFlowFilter1'])>0 && parseInt(dataList['ReDayFilter1'])>0){
                            //按流量/时间
                            filterMode='按流量/时长';
                            html+= '<tr>'+
                                '<td style="width:10%;">'+el.filtername+'</td>'+
                                '<td style="width:70%">'+
                                '<div class="_progress">'+
                                '<div class="_progress_" style="width: '+width+'%;background-color:'+_color[i%_color.length]+'">'+
                                '</div>'+
                                '</div>'+
                                '<span style="display:block;text-align:center">还剩'+width.toFixed(2)+'%</span>'+
                                '</td>'+
                                '<td>'+(_bool?ReFlowFilter:ReDayFilter)+'/'+(_bool?el.flowlife:el.timelife)+'</td>'+
                                '<td>'+'<button class="btn btn-info clickBtn washBtn" name="'+ el.filtername+'">复位</button><input type="hidden" value='+i+'></td>'+
                                '</tr>';

                        }else{
                            // 没有数据时的显示状态
                            html+='暂无数据';
                        }
                        // ;
                        $_table.append(html);
                    })








                    $(".H1devicestause").text(devicestause[dataList.DeviceStause]?devicestause[dataList.DeviceStause]:'--');//设置设备状态
                    $(".tdDeviceStause").text(devicestause[dataList.DeviceStause]?devicestause[dataList.DeviceStause]:'--');//设置设备状态
                    $(".originalWater").text(dataList.RawTDS);//设置当前设备原水值
                    $(".tdOriginalWater").text(dataList.RawTDS);
                    $(".cleanWater").text(dataList.PureTDS);//设置当前设备纯水值
                    $(".tdCleanWater").text(dataList.PureTDS);//设置当前设备纯水值
                    $(".leaseMode").text(leasingmode[dataList.LeasingMode]?leasingmode[dataList.LeasingMode]:'--');//设置当前设备租赁模式
                    $(".tdLeaseMode").text(leasingmode[dataList.LeasingMode]?leasingmode[dataList.LeasingMode]:'--');//设置当前设备租赁模式
                    $(".tdFilterMode").text(filterMode);//设置当前设备滤芯模式
                    $(".reflow").text((dataList.ReFlow=='-1')?0:dataList.ReFlow);//设置当前设备剩余流量
                    $(".reday").text(dataList.Reday=='-1'?0:dataList.Reday);//设置当前设备剩余天数
                    $(".filtermode").text(dataList.filtermode);//设置当前设备滤芯模式
                    $(".temperature").text(dataList.Temperature);//设置当前设备温度
                    //$(".updatetime").text(dataList.updatetime);//设置当前设备更新时间
                }else if(dataList.PackType=="SetData")//设置数据类型数据
                {
                    identify=0;
                    console.log(dataList);
                    for(var i=0;i<CmdList.length;i++){
                        console.log(CmdList[i]);
                        if(CmdList[i].cmd.PackNum==dataList.PackNum)
                        {
                            if(CmdList[i].type=="关机中")
                            {
                                $(".switchText").html("开机");
                            }
                            else if(CmdList[i].type=="开机中")
                            {
                                $(".switchText").html("关机");
                            }
                            else if(CmdList[i].cmd.type=="复位中")
                            {
                                $(".washBtn").html("复位");
                            }

                            CmdList.splice(i,1);
                            break;
                        }
                    }
                }

            }
            //90秒后判断设备是否离线
            setInterval(function(){
                numAdd++;
                if(numAdd ==90){
                    $(".H1devicestause").text("离线");
                    $(".tdDeviceStause").text("离线");
                    $(".switchText").text("开机");
                    layui.use('layer', function(){
                        var layer = layui.layer;
                        layer.msg('该设备已离线');
                    });
                }
            },1000);
            //按钮操作
            $(".btnBox").on("click",".clickBtn",function(){
                var thisT=$(this);
                var _this=$(this).html();//获取按钮文本
                var thisIndex=parseInt($(this).siblings("input").val());
                var filterN = $(this).attr('name');
                var ajson;//数据对象
                var ajsonSelect;
                //判断操作类型
                if(_this!="复位"){
                    var tipsText = "确定要"+ _this + deviceId +"吗？";
                } else {
                    var tipsText = "确定要"+ _this + "<a>滤芯" + filterN +"</a>吗";
                }
                //弹框提示
                layui.use('layer', function(){
                    var layer = layui.layer;
                    layer.confirm(tipsText, {icon: 3, title:'温馨提示'}, function(index){
                        layer.close(index);
                        ajson={
                            "DeviceID":deviceId,
                            "PackType":"SetData",
                            "Vison":0,
                            "PackNum":PackNum,
                            "curTime":0,
                        };
                        //根据当前设备状态设置按钮文本
                        var type=0;
                        if(_this=="开机"){
                            ajson['DeviceStause']=8;
                            thisT.html("开机中");
                            type="开机中";

                        }else if(_this=="关机"){
                            ajson['DeviceStause']=7;
                            thisT.html("关机中");
                            type="关机中";

                        }else if(_this=="复位"){
                            ajson['ReFlowFilter'+(thisIndex+1)]=data.filterInfo[thisIndex].flowlife;
                            ajson['ReDayFilter'+(thisIndex+1)]=data.filterInfo[thisIndex].timelife;
                            ajson['FlowLifeFilter'+(thisIndex+1)]=data.filterInfo[thisIndex].flowlife;
                            ajson['DayLifeFiter'+(thisIndex+1)]=data.filterInfo[thisIndex].timelife;
                            ajson['type']='复位中';
                            thisT.html("复位中");
                            type=filterN;
                        }
                        //发送数据
                        ajsonSelect={
                            "DeviceID":deviceId,
                            "PackType":"Select",
                            "Vison":0,
                        };
                        websoket.send(JSON.stringify(ajson));
                        websoket.send(JSON.stringify(ajsonSelect));
                        CmdList.push({
                            cmd:ajson,
                            type:type
                        });
                        identify=1;
                        timer=setTimeout(function(){
                            if(identify==1){
                                layui.use('layer', function(){
                                    var layer = layui.layer;

                                    // layer.msg('操作超时！');
                                    if(thisT.html()=='开机中'){
                                        thisT.html('开机')
                                    }else if(thisT.html()=='关机中'){
                                        thisT.html('关机')
                                    }else if(thisT.html('复位中')){
                                        thisT.html('复位')
                                    }
                                });
                                identify=0;
                            }
                        },5000)
                    });
                });
            });

        }


    });
    //ajax  请求结束


});



</script>