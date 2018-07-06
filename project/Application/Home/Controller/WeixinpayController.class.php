<?php
namespace Home\Controller;
use Think\Controller;
use Home\WeixinEventController;
use Think\Log;

class WeixinpayController extends Controller 
{
    /**
     * 处理订单写入数据
     * @return array 返回数组格式的notify数据
     */
    public function notify()
    {
        // echo 1;
    	// 获取微信服务器返回的xml文档
        $xml=file_get_contents('php://input', 'r');

        if($xml){
        	//解析微信返回数据数组格式
        	$result = $this->notifyData($xml);
            file_put_contents('./abc.txt',$xml."\r\n", FILE_APPEND);
            file_put_contents('./caocaocao.txt',json_encode($result));
            // file_put_contents('./caonima.txt',$xml);
	    	// 如果订单号不为空
        	if(!empty($result['out_trade_no'])){
        		// file_put_contents('./lun.txt','不为空', FILE_APPEND);

                //返回的随机订单号
                $orderid = $result['out_trade_no'];

                //查库是否存在订单号
                $order = M('flow')->where("orderid='{$orderid}'")->find();

                //不存在则存库
                if (!$order) {
                    // 查询数据库
                    $model = M('flow');
                    // 用户ID号
                    $data['open_id'] = $result['openid'];
                    $data['appid'] = $result['appid'];
                    $data['device_code'] = $device_code = $result['attach'];
                    $data['orderid'] = $orderid;
                    // 金额
                    $data['money'] = $result['total_fee'];

                    //商品详情信息
                    // $data['detail'] = $result['detail'];
                    // file_put_contents('./detail.txt', json_encode($data['detail']));


                    // 充值时间
                    $data['addtime'] = time();
                    // 写入数据库
                    $msg = $model->add($data);
                    file_put_contents('./1.txt', 1);

                    //用户充值完成后更新设备跟用户的绑定关系
                    if ($msg) {
                        //设备编码
                        $device_code = $data['device_code'];
                        $devices['status'] = 1;
                        M('devices')->where("device_code='$device_code'")->save($devices);
                    }


                    //充值成功后给用户返回充值信息
                    //根据设备获取客户id
                    $auid = M('devices')->where("device_code='{$device_code}'")->find()['auid'];

                    // 根据客户id获取微信公众号信息
                    $info = M('adminuser')->where('id='.$auid)->find();
                    $appid = $info['appid'];
                    $appsecret = $info['appsecret'];
                    // 查询模板消息ID
                    $recharge_template = $info['recharge_template'];
                    // 实例化微信JSSDK类对象  需要传对用的经销商的Appid跟appSecret
                    $wxJSSDK = new \Org\Util\WeixinJssdk($appid, $appsecret);
                    // 调用获取公众号的全局唯一接口调用凭据
                    $access_token = $wxJSSDK->getAccessToken();

                    $times = date('Y-m-d H:i:s', time());
                    $moneys = html_price($data['money']);

                    //完成信息后给用户自动发送充值记录
                    $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;

                    $datas = '{
                        "touser":"'.$data['open_id'].'",
                        "template_id":"'.$recharge_template.'",         
                        "data":{
                                "first": {
                                    "value":"您的充值成功啦",
                                    "color":"#173177"
                                },
                                "keyword1":{
                                    "value":"'.$times.'",
                                    "color":"#173177"
                                },
                                "keyword2": {
                                    "value":"'.$moneys.'元",
                                    "color":"#173177"
                                },
                                "keyword3": {
                                    "value":"0",
                                    "color":"#173177"
                                },
                                "keyword4": {
                                    "value":"0",
                                    "color":"#173177"
                                },
                                "remark":{
                                    "value":"芯智云科技",
                                    "color":"#173177"
                                }
                        }
                    }';

                    $result = $this->https_request($url, $datas);
                    file_put_contents('./result.txt', $result);
                        

                }
		    	
	

	    		}else{
	    		 	file_put_contents('./wx_notifyres.txt','订单已经存在', FILE_APPEND);
	    		 	// echo '订单已经存在';
	    		}
        	}
        }

    public function getshoppwd()
    {

    }

    /**
     * 验证服务器返回支付成功订单
     * @return array 返回数组格式的notify数据
     */
    public function notifyData($xml)
    {
        // 获取微信服务器返回的xml文档
        $xml=file_get_contents('php://input', 'r');
        // file_put_contents('./wx_notify.txt',$xml, FILE_APPEND);

        // 转成php数组
        $data=$this->toArray($xml);

        $appid = $data['appid'];

        $data['shoppwd'] = M("adminuser")->where("appid='{$appid}'")->find()['shoppwd'];

        file_put_contents('./wx_notifylun.txt','datalun:'.json_encode($data), FILE_APPEND);	
        // file_put_contents('./wx_notify3.txt','456:'.$data['sign'], FILE_APPEND);	

        // 保存原sign
        $dataSign=$data['sign'];

        // sign不参与签名
        unset($data['sign']);

        // 生成签名
        $sign=$this->makeSign($data);
        file_put_contents('./wx_notify.txt','原签: '.$dataSign.'现签：'.$sign, FILE_APPEND);	
        // 判断签名是否正确  判断支付状态
        if ($sign==$dataSign && $data['return_code']=='SUCCESS' && $data['result_code']=='SUCCESS') {

        	// 返回状态给微信服务器
            echo '<xml><return_code><![CDATA[SUCCESS]]></return_code><return_msg><![CDATA[OK]]></return_msg></xml>';

            // 返回数据给回调函数进行插入操作
        file_put_contents('./wx_notify456.txt','456:'.json_encode($data), FILE_APPEND); 
            
            return $data;
        }else{
  			// 签名错误 或 支付未成功 
            $result=false;
        }

        
    }

    /**
     * 将xml转为array
     * @param  string $xml xml字符串
     * @return array       转换得到的数组
     */
    public function toArray($xml){   
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $result= json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        Log::write('lunge：',json_encode($result));

        return $result;
    }

    /**
     * 生成签名
     * @return 签名，本函数不覆盖sign成员变量，如要设置签名需要调用SetSign方法赋值
     */
    public function makeSign($data){
        // 去空
        $shoppwd = $data['shoppwd'];
        unset($data['shoppwd']);
        $data=array_filter($data);
        //签名步骤一：按字典序排序参数
        ksort($data);
        $string_a=http_build_query($data);
        $string_a=urldecode($string_a);
        //签名步骤二：在string后加入KEY
        $config=$this->config;
        $string_sign_temp=$string_a."&key=".$shoppwd;
        //签名步骤三：MD5加密
        $sign = md5($string_sign_temp);
        // 签名步骤四：所有字符转为大写
        $result=strtoupper($sign);
        return $result;
    }

    /**
     * CURL使用
     * @param  string $url  URL地址
     * @param  Array $data 传递数据
     * @return string  $output     传递数据时返回的结果
     */
    public function https_request($url,$data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
}
