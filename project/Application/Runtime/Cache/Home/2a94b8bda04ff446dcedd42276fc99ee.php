<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
  <head> 
    <title>V型知识库原创---www.vxzsk.com 请尊重版权</title>
    <meta name="viewport" content="width=320.1,initial-scale=1,minimum-scale=1,maximum-scale=1,user-scalable=no"> 
    <meta charset='utf-8'>
	<link rel="stylesheet" href="/dian/project/Public/Home/css/weui.min.css?what=0">
  <script src="/dian/project/Public/Home/js/jquery.min.js?what=0"> </script>
  <script src="http://res.wx.qq.com/open/js/jweixin-1.1.0.js"> </script>
  
</head>
<body ontouchstart>

<!--标题行 V型知识库 www.vxzsk.com-->
<h1 style="color: white;background-color: green;text-align: center;background-position: center;">V型知识库-微信硬件蓝牙jsapi示例</h1>
  

<div class="page">
    <div class="bd spacing">
        <div class="weui_cells weui_cells_form">
             	<input type="hidden" id="BLEState" value=""></input>
	            <input type="hidden" id="open-id" value=""></input> 
        	 
            <div class="weui_cell">
                <div class="weui_cell_hd"><label class="weui_label" style="width: auto;">当前设备:&nbsp</label></div>
                <div class="weui_cell_bd weui_cell_primary">
                   <label id="lbdeviceid" class="weui_label" style="width: auto;"></label>
                </div>
            </div>
           
          
        </div>
    	<div class="weui_btn_area weui_btn_area_inline">
	        <button class="weui_btn weui_btn weui_btn_warn" id="CallGetWXrefresh">获取设备DeviceId</button>
	        <button class="weui_btn  weui_btn_primary" id="CallGetBalance">发送指令并接收硬件返回数据</button>
            
        </div>
        
        
    </div>
    
    
    <div class="weui_dialog_alert" id="Mydialog" style="display: none;">
    <div class="weui_mask"></div>
    <div class="weui_dialog">
        <div class="weui_dialog_hd" id="dialogTitle"><strong class="weui_dialog_title">着急啦</strong></div>
        <div class="weui_dialog_bd" id="dialogContent">亲,使用本功能,请先打开手机蓝牙！</div>
        <div class="weui_dialog_ft">
            <a href="#" class="weui_btn_dialog primary">确定</a>
        </div>
    </div>
    </div>
    
    
    <!--BEGIN toast-->
    <div id="toast" style="display: none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <i class="weui_icon_toast"></i>
            <p class="weui_toast_content" id="toast_msg">已完成</p>
        </div>
    </div>
    <!--end toast-->

    <!-- loading toast -->
    <div id="loadingToast" class="weui_loading_toast" style="display:none;">
        <div class="weui_mask_transparent"></div>
        <div class="weui_toast">
            <div class="weui_loading">
                <div class="weui_loading_leaf weui_loading_leaf_0"></div>
                <div class="weui_loading_leaf weui_loading_leaf_1"></div>
                <div class="weui_loading_leaf weui_loading_leaf_2"></div>
                <div class="weui_loading_leaf weui_loading_leaf_3"></div>
                <div class="weui_loading_leaf weui_loading_leaf_4"></div>
                <div class="weui_loading_leaf weui_loading_leaf_5"></div>
                <div class="weui_loading_leaf weui_loading_leaf_6"></div>
                <div class="weui_loading_leaf weui_loading_leaf_7"></div>
                <div class="weui_loading_leaf weui_loading_leaf_8"></div>
                <div class="weui_loading_leaf weui_loading_leaf_9"></div>
                <div class="weui_loading_leaf weui_loading_leaf_10"></div>
                <div class="weui_loading_leaf weui_loading_leaf_11"></div>
            </div>
            <p class="weui_toast_content" id="loading_toast_msg">数据加载中</p>
        </div>
    </div>
    <!-- End loading toast -->
    
    <!--BEGIN dialog1-->
    <div class="weui_dialog_confirm" id="dialog1" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">弹窗标题</strong></div>
            <div class="weui_dialog_bd">自定义弹窗内容，居左对齐显示，告知需要确认的信息等</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_btn_dialog default" id="qxBtn">取消</a>
                <a href="javascript:;" class="weui_btn_dialog primary" id="okBtn">确定</a>
            </div>
        </div>
    </div>
    <!--END dialog1-->
    <!--BEGIN dialog2-->
    <div class="weui_dialog_alert" id="dialog2" style="display: none;">
        <div class="weui_mask"></div>
        <div class="weui_dialog">
            <div class="weui_dialog_hd"><strong class="weui_dialog_title">弹窗标题</strong></div>
            <div class="weui_dialog_bd">弹窗内容，告知当前页面信息等</div>
            <div class="weui_dialog_ft">
                <a href="javascript:;" class="weui_btn_dialog primary">确定</a>
            </div>
        </div>
    </div>
    <!--END dialog2-->
</div>


<div id="myparams">
 <span id="timestamp">1525667740</span>
 <span id="nonceStr">alakhglkabgkjlb</span>
 <span id="signature">29c72eb633d3eda9c4668b986657f4cc7b571d2d</span>
 <span id="appId">wx57d57fb99d6d838d</span>
</div>
</body> 
<script type="text/javascript">
var C_DEVICEID=null;

/***
 * 初始化硬件库
 */
function loadXMLDoc()
{
	//var url = window.location.href;
	var appId =jQuery("#appId").text();
	var timestamp=jQuery("#timestamp").text();
	var nonceStr =jQuery("#nonceStr").text();
	var signature=jQuery("#signature").text();
	alert("appId:"+appId);
               wx.config({
		 	  beta: true,
		      debug: false,
		      appId: appId, //'wx1704xabcd569oiu0',
		      timestamp: timestamp,
		      nonceStr: nonceStr,
		      signature: signature,
		      jsApiList: [
		        'openWXDeviceLib',
		        'closeWXDeviceLib',
		        'getWXDeviceInfos',
		        'getWXDeviceBindTicket',
		        'getWXDeviceUnbindTicket',
		        'startScanWXDevice',
		        'stopScanWXDevice',
		        'connectWXDevice',
		        'disconnectWXDevice',
		        'sendDataToWXDevice',
		        'onWXDeviceBindStateChange',
		        'onWXDeviceStateChange',
		        'onScanWXDeviceResult',
		        'onReceiveDataFromWXDevice',
		        'onWXDeviceBluetoothStateChange',
		      ]
		  });
              alert("初始化库结束");
	
}

/*********************************************************
* 打开微信设备
* 作者：V型知识库 www.vxzsk.com 2017-05-27
* my_openWXDeviceLib
* 入口参数：无
* 出口参数：0表示打开成功；1表示打开失败
*********************************************************/
function my_openWXDeviceLib(){
   var x=0; 
   WeixinJSBridge.invoke('openWXDeviceLib', {}, 
   function(res){
      if(res.err_msg=='openWXDeviceLib:ok')
        {
          if(res.bluetoothState=='off')
            {    
              alert("太着急啦亲,使用前请先打开手机蓝牙！");  
             
              x=1;
            };
          if(res.bluetoothState=='unauthorized')
            {
              alert("出错啦亲,请授权微信蓝牙功能并打开蓝牙！");    
             
              x=1;
            }; 
          if(res.bluetoothState=='on')
            {
              alert("1.蓝牙已打开");
              
              x=0;
            };      
        }
      else
        {
          alert("1.微信蓝牙打开失败");
          x=1;  
        }
    });
   return x;  //0表示成功 1表示失败
}


/*********************************************************
* 接收到数据事件
* 作者：V型知识库 www.vxzsk.com 2016-04-04
* my_onReceiveDataFromWXDevice
* 入口参数：无
* 出口参数：无
*********************************************************/ 
function my_onReceiveDataFromWXDevice(){ 
    WeixinJSBridge.on('onReceiveDataFromWXDevice', function(argv) {
       alert("接收硬件返回的数据"+argv.base64Data);
    
	  });
}

/**********************************************
* 取得微信设备信息
* 作者：V型知识库 www.vxzsk.com 2016-04-04
* my_getWXDeviceInfos
* 入口参数：无
* 出口参数：返回一个已经链接的设备的ID
**********************************************/
function my_getWXDeviceInfos(){
   
    WeixinJSBridge.invoke('getWXDeviceInfos', {}, function(res){
        var len=res.deviceInfos.length;  //绑定设备总数量
		for(i=0; i<=len-1;i++)
         {
           //alert(i + ' ' + res.deviceInfos[i].deviceId + ' ' +res.deviceInfos[i].state); 
           if(res.deviceInfos[i].state==="connected")
            {
              
              C_DEVICEID = res.deviceInfos[i].deviceId;
              alert("2.设备已成功连接");
              jQuery("#lbdeviceid").text(C_DEVICEID);
              
              break;   
            }  
         }
         	
    }); 
  return;    
}


/**
 *  Byte数组转Base64字符,原理同上 
 *	@Param [0x00,0x00]
 *	@return Base64字符串
 **/
function bytes_array_to_base64(array) {
	if (array.length == 0) {
		return "";
	}
	var b64Chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
	var result = "";
	// 给末尾添加的字符,先计算出后面的字符
	var d3 = array.length % 3;
	var endChar = "";
	if (d3 == 1) {
		var value = array[array.length - 1];
		endChar = b64Chars.charAt(value >> 2);
		endChar += b64Chars.charAt((value << 4) & 0x3F);
		endChar += "==";
	} else if (d3 == 2) {
		var value1 = array[array.length - 2];
		var value2 = array[array.length - 1];
		endChar = b64Chars.charAt(value1 >> 2);
		endChar += b64Chars.charAt(((value1 << 4) & 0x3F) + (value2 >> 4));
		endChar += b64Chars.charAt((value2 << 2) & 0x3F);
		endChar += "=";
	}

	var times = array.length / 3;
	var startIndex = 0;
	// 开始计算
	for (var i = 0; i < times - (d3 == 0 ? 0 : 1); i++) {
		startIndex = i * 3;

		var S1 = array[startIndex + 0];
		var S2 = array[startIndex + 1];
		var S3 = array[startIndex + 2];

		var s1 = b64Chars.charAt(S1 >> 2);
		var s2 = b64Chars.charAt(((S1 << 4) & 0x3F) + (S2 >> 4));
		var s3 = b64Chars.charAt(((S2 & 0xF) << 2) + (S3 >> 6));
		var s4 = b64Chars.charAt(S3 & 0x3F);
		// 添加到结果字符串中
		result += (s1 + s2 + s3 + s4);
	}

	return result + endChar;
}


/*******************************************************************
 * 发送数据函数
 * 作者：
 * 入口参数：
 *     cmdBytes: 需要发送的命令字节
 *     selDeviceID: 选择的需要发送设备的ID 
 * 出口参数：
 *     返回: 0表示发送成功；1表示发送失败
 *     如果成功，则接收事件应该能够收到相应的数据
*******************************************************************/
function senddataBytes(cmdBytes,selDeviceID){
  //1. 如果输入的参数长度为零，则直接退出
  if(cmdBytes.length<=0){return 1};
  //1.1 如果设备ID为空，则直接返回
  if(selDeviceID.length<=0){return 1};
  //2. 发送数据
  var x=0;
  WeixinJSBridge.invoke('sendDataToWXDevice', {
			"deviceId":selDeviceID, 
			"base64Data":bytes_array_to_base64(cmdBytes)
			}, function(res){
			if(res.err_msg=='sendDataToWXDevice:ok')
               {
                 alert("数据发送成功");
                 x=0;
               }  
            else
               {
                 alert("数据发送失败");
                 x=1; 
               } 
		});  
  return x;      
}

 



  jQuery(document).ready(function(){
   loadXMLDoc();
   $("#CallGetWXrefresh").on("click",function(e){  
    //showdialog();
    
     //1. 打开微信设备 
     my_openWXDeviceLib();
     
    //2. 安装接收到数据事件
     my_onReceiveDataFromWXDevice();
     
     //3. 获取设备信息
     my_getWXDeviceInfos();
     
     
      
  });
  
  /****
  发送指令数据并接收硬件返回数据
  此指令可更换为您开发的硬件烧制的指令
  ***/
   $("#CallGetBalance").on("click",function(e){
	var Bytes=new Array();
    Bytes[0]=0x09;
    Bytes[1]=0x00;
    Bytes[2]=0x01;
    Bytes[3]=0x33;
    Bytes[4]=0x06;
    Bytes[5]=0x22;
    var x=senddataBytes(Bytes,C_DEVICEID);
    
  });
  
  
  });

wx.error(function (res) {
  alert(res.errMsg);
});


</script>



</html>