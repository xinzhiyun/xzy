
/**
* 打开微信设备
*/
var openWXDeviceLib = function (){
    var x = 0; 
    wx.invoke('openWXDeviceLib', {}, 
    function(res){
        if(res.err_msg=='openWXDeviceLib:ok') {
            if(res.bluetoothState == 'off'){    
                alert("先打开手机蓝牙再使用！");  
                x=1;
            };
            if(res.bluetoothState == 'unauthorized'){
                alert("出错啦亲,请授权微信蓝牙功能并打开蓝牙！");
                x=1;
            };
            if(res.bluetoothState == 'on'){
                alert("1.蓝牙已打开");
                x = 0;
            };
        }else{
            alert("1.微信蓝牙打开失败");
            x = 1;
        }
    });
    return x;  //0表示成功 1表示失败
}
/**
 * [closeWXDeviceLib 关闭设备库]
 * @return {[type]} [description]
 */
var closeWXDeviceLib = function(callback){
    var obj = {};
    wx.invoke('closeWXDeviceLib', {}, 
    function(res){
        if(res.err_msg == 'closeWXDeviceLib:ok') {
            // 成功
            obj.res = 'ok';

        }else if(res.err_msg == 'closeWXDeviceLib:fail'){
            // 失败
            obj.res = 'fail';

        }else{
            // 未知问题
            obj.res = null;
        }
        // 回调
        callback(obj);
    });
}

/**
* 接收到数据事件
*/ 
var ReceiveData = function (callback){
    wx.on('onReceiveDataFromWXDevice', function(rec) {
        callback({data: rec.base64Data})
        
	});
}

/**
* 取得微信设备信息
* 回调：返回一个已经链接的设备的ID
*/
var getWXDeviceInfos = function (callback){
    var arr = [];
    wx.invoke('getWXDeviceInfos', {}, function(res){
        var len = res.deviceInfos.length;  //绑定设备总数量
		for(i=0; i<=len-1; i++){
            arr[i] = {};
            arr[i]['deviceId'] = res.deviceInfos[i].deviceId;
            arr[i]['state'] = res.deviceInfos[i].state;
            // alert(i + ' ' + res.deviceInfos[i].deviceId + ' ' +res.deviceInfos[i].state); 
            // if(res.deviceInfos[i].state === "connected"){
            //     C_DEVICEID = res.deviceInfos[i].deviceId;
            //     break;   
            // }  
        }
        // 回调
        callback(arr);
    }); 
}


/**
 *  Byte数组转Base64字符,原理同上 
 *	@Param [0x00,0x00]
 *	@return Base64字符串
 **/
var bytes_array_to_base64 = function (array) {
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


/**
 * 发送数据函数
 * @param {string} [data] [需要发送的命令字节]
 * @param {string} [deviceId] [设备ID]
 * 返回参数：1表示发送成功；0表示发送失败
*/
var sendData = function (deviceId, data, callbcak){
    var obj = {};
    // 如果待发送的数据长度为零，或设备id为空，则直接退出
    if( deviceId.length <= 0 ){
        obj.status = '设备号不能为空！';
        return
    }
    if( data.length <= 0 ){
        obj.status = '发送的数据不能为空！';
        return
    }
    // 发送数据
    wx.invoke('sendDataToWXDevice', {
    	"deviceId": deviceId, 
    	"base64Data": bytes_array_to_base64(data)

    },function(res){
    	if(res.err_msg=='sendDataToWXDevice:ok'){
            // 成功
            obj.res = 1;
        } else {
            // 失败
            obj.res = 0;
        }
        // 回调
        callbcak(obj); 
    });  
}

/**
 * [scanWXDevice 扫描设备]
 * @param  {Function} callback [回调函数]
 * @return {[type]}            [description]
 */
var scanWXDevice = function(callback){
    var obj = {};
    wx.invoke('startScanWXDevice', {}, function(res) {
        console.log('startScanWXDevice',res);
        if(res.err_msg == 'startScanWXDevice:ok'){
            // 成功
            obj['res'] = 'ok';

        }else if(res.err_msg == 'startScanWXDevice:fail'){
            // 失败
            obj['res'] = 'fail';

        }
        callback(obj);
    });
}

/**
 * [stopScan 停止扫描设备]
 * @param  {Function} callback [回调函数]
 * @return {[type]}            [description]
 */
var stopScan = function(callback){
    var obj = {};
    wx.invoke('stopScanWXDevice', {}, function(res) {
        console.log('startScanWXDevice',res);
        if(res.err_msg == 'stopScanWXDevice:ok'){
            // 成功
            obj['res'] = 'ok';

        }else if(res.err_msg == 'stopScanWXDevice:fail'){
            // 失败
            obj['res'] = 'fail';

        }
        callback(obj);
    });
}

/**
 * [connectWXDevice 连接设备]
 * @param  {[type]}   deviceId [设备id]
 * @param  {Function} callback [回调函数]
 * @return {[type]}            [description]
 */
var connectWXDevice = function(deviceId, callback){
    var obj = {};
    wx.invoke('stopScanWXDevice', {'deviceId':deviceId, 'connType':'lan'}, function(res) {
        console.log('startScanWXDevice',res);
        if(res.err_msg == 'connectWXDevice:ok'){
            // 成功
            obj['res'] = 'ok';

        }else if(res.err_msg == 'connectWXDevice:fail'){
            // 失败
            obj['res'] = 'fail';

        }
        callback(obj);
    });
}