<?php
namespace Home\Controller;
use Think\Controller;
use \Org\Util\WeixinJssdk;
use Think\Log;

class IndexController extends Controller 
{
	/**
	 * [index 加载充值页面]
	 * @return [type] [description]
	 */
    public function index()
    {
        //在充值处获取客户id
        if (empty($_SESSION['auid'])) {
            $auid = $_SESSION['auid'] = $_GET['auid'];
        }
        
        // 根据客户id获取客户微信公众号信息
        $info = M('adminuser')->where('id='.$_SESSION['auid'])->find();

        $appid = $info['appid'];
        $appsecret = $info['appsecret'];

        $weixin  = new WeixinJssdk($appid, $appsecret);

        $openId_ifno = $weixin->getSignPackage();
        
        // dump($openId_ifno);
        $openid = $weixin->GetOpenid();

        if (empty($_SESSION['openid'])) {
            $_SESSION['openid'] = $openid;
        }

        $this->assign('weixin',$openId_ifno);
    	$this->assign('openid',$_SESSION['openid']);
        $this->display();
    }

    /**
     * [getSetmeal 获取充值套餐]
     * @return [type] [description]
     */
    public function getSetmeal()
    {
    	//先接收前端的device_code
    	$deviceCode = $_POST['device_code'];

    	if (empty($deviceCode)) {
            $this->ajaxReturn(array('msg'=>'设备编码为空','code'=>'201'));

    	} else {
    		//根据设备编码获取经销商id
    		$auid = M('devices')->field('auid')->where("device_code='{$deviceCode}'")->find()['auid'];

    		if ($auid) {
    			//在根据经销商id获取对应的充值套餐
    			$setmeal = M('setmeal')->where('auid='.$auid)->select();
            	$this->ajaxReturn(array('msg'=>$setmeal,'code'=>'200'));


    		} else {
            	$this->ajaxReturn(array('msg'=>'该经销商暂无套餐','code'=>'201'));

    		}
    	}
    }

    /**
     * [getDeviceNum 获取当前用户绑定的设备情况]
     * @return [type] [description]
     */
    public function getDeviceNum()
    {	
    	//根据登录的经销商获取其对用的设备编码
        $appId = $_SESSION['apiuser']['appid'];
        $appSecret = $_SESSION['apiuser']['appsecret'];
        $openid = $_SESSION['homeuser']['open_id'];

        // 实例化微信JSSDK类对象  需要传对用的经销商的Appid跟appSecret
        $wxJSSDK = new \Org\Util\WeixinJssdk($appId, $appSecret);
        // 调用获取公众号的全局唯一接口调用凭据
        $access_token = $wxJSSDK->getAccessToken();

        //调用微信接口查看用户绑定的设备
        $url = "https://api.weixin.qq.com/device/get_bind_device?access_token=".$access_token."&openid=".$openid;

        //模拟url请求
        $result = $this->https_request($url);

        //用户绑定设备的数量
        $count = count(json_decode($result,true)['device_list']);

        //各种数量的返回情况
        switch ($count) {
        	case '0':
            	$this->ajaxReturn(array('msg'=>'请绑定设备','code'=>'201'));
        		break;
        	case '1':
            	$this->ajaxReturn(array('msg'=>'正常进入充值流程','code'=>'200'));
        		break;
        	default:
        		$this->ajaxReturn(array('msg'=>'你绑定了多台设备,请选择要充值的设备','code'=>'201'));
        		break;
        }
    }

    /**
     * [makeOrder 充值前生成订单号]
     * @return [type] [description]
     */
    public function makeOrder()
    {
        //接收充值前的地址信息
        // dump($_POST);die;
        
        //获取要充值的设备id
        
        $deviceCode = $_POST['deviceId'];



        //套餐id
        $meal_id = $_POST['meal_id'];

        $days = M('setmeal')->where("id='{$meal_id}'")->find()['days'];

        //测试使用
        if ($days == 0) {
            //展会测试使用
            $data['outtime'] = time() + 10;

        } else {
            //查询该设备的剩余时间    
            $outtime = M('devices')->where("device_code='{$deviceCode}'")->find()['outtime'];

            //设备初始化时间
            $inittime = M('devices')->where("device_code='{$deviceCode}'")->find()['inittime'];

            // M('setmeal')->where("id='{$meal_id}'")->find()['days'];

            // Log::write(M('setmeal')->where("id='{$meal_id}'")->find()['days'],'伦哥哥');
            // Log::write(M()->getLastSql(),'伦哥哥SQL');

            if (is_null($outtime)) {
                //如果为空则将  当前时间戳+充值套餐时间+初始化时间
                //获取充值天数
                $data['outtime'] = time() + (M('setmeal')->where("id='{$meal_id}'")->find()['days']) * 3600 * 24 + $inittime;

            } else {
                //如果不为空  充值套餐时间戳+设备剩余时间
                
                if ($outtime < time()) {
                    //如果到期时间小于当前时间戳，当前时间戳+充值秒数
                    $data['outtime'] = (M('setmeal')->where("id='{$meal_id}'")->find()['days']) * 3600 * 24 + time();
                    

                } else {
                    //如果还有有效期  到期时间戳+充值秒数
                    $data['outtime'] = (M('setmeal')->where("id='{$meal_id}'")->find()['days']) * 3600 * 24 + $outtime;

                }
                
            }
        }

        $data['address'] = $_POST['addr'].$_POST['addrdetail'];
        $data['username'] = $_POST['name'];
        $data['phone'] = $_POST['phone'];
        $data['status'] = 1;
        
        //充值成功后修改设备表数据
        $res = M('devices')->where("device_code='{$deviceCode}'")->save($data);

        // Log::write('luu', $res);

        // dump($res);

        if ($res) {
            //更改设备在微信服务器的信息
            //将设备到期时间给前端
            $outtime = M('devices')->where("device_code='{$deviceCode}'")->find()['outtime'];
            //剩余秒数
            $num = $outtime-time();


            // $arr = array();

            // $arr[0] = 0Xdd;
            // $arr[1] = 0Xaa;
            // $arr[2] = 0X00;
            // $arr[3] = 0X00;
            // $arr[4] = 0X00;
            // $arr[5] = 0X0E;
            // $arr[6] = 0X00;
            // $arr[7] = 0X0A;
            // $arr[8] = ($num>>24)&0XFF;
            // $arr[9] = ($num>>16)&0XFF;
            // $arr[10] = ($num>>8)&0XFF;
            // $arr[11] = ($num>>0)&0XFF;
            // $arr[12] = 0X00;
            // $arr[13] = 0X00;

            $this->ajaxReturn(array('msg'=>$num,'code'=>'200'));
            

        } else {
            $this->ajaxReturn(array('msg'=>'设备更新失败','code'=>'201'));
        }   



    }




    /**
     * [getNum 分配查询需要的微信公众号新]
     * @return [type] [description]
     */
    public function select()
    {   
        //在查询处获取客户id
        if (empty($_SESSION['auid'])) {
            $auid = $_SESSION['auid'] = $_GET['auid'];
        }
        
        // 根据客户id获取客户微信公众号信息
        $info = M('adminuser')->where('id='.$_SESSION['auid'])->find();

        $appid = $info['appid'];
        $appsecret = $info['appsecret'];

        $weixin  = new WeixinJssdk($appid, $appsecret);

        $openId_ifno = $weixin->getSignPackage();
        
        // // dump($openId_ifno);
        // $openid = $weixin->GetOpenid();

        // if (empty($_SESSION['openid'])) {
        //     $_SESSION['openid'] = $openid;
        // }


        $this->assign('weixin',$openId_ifno);
        $this->assign('openid',$_SESSION['openid']);
        $this->display();
    }

    /**
     * [getSelect 查询剩余天数]
     * @return [type] [description]
     */
    public function getSelect()
    {
        foreach ($_POST['deviceList'] as $value) {
            $did = $value['deviceId'];

            if (M('devices')->field('device_code,outtime')->where("device_code='{$did}'")->find()) {
                $dList[] = M('devices')->field('device_code,outtime')->where("device_code='{$did}'")->find();
            } 
            
            foreach ($dList as $key => $value) {

                if ($dList[$key]['outtime']<time()) {
                    $dList[$key]['outtime'] = '0年0天0时0分0秒';
                } else {
                    $dList[$key]['outtime'] = $this->Sec2Time($dList[$key]['outtime']-time());
                }
                
            }
            
        }

        $this->ajaxReturn($dList);

    }

    /**
     * [Sec2Time 将秒数转换为时间（年、天、小时、分、秒）]
     * @param [type] $time [description]
     */
    public function Sec2Time($time)
    {  
        if(is_numeric($time)){  
        $value = array(  
          "years" => 0, "days" => 0, "hours" => 0,  
          "minutes" => 0, "seconds" => 0,  
        );  
        if($time >= 31556926){  
          $value["years"] = floor($time/31556926);  
          $time = ($time%31556926);  
        }  
        if($time >= 86400){  
          $value["days"] = floor($time/86400);  
          $time = ($time%86400);  
        }  
        if($time >= 3600){  
          $value["hours"] = floor($time/3600);  
          $time = ($time%3600);  
        }  
        if($time >= 60){  
          $value["minutes"] = floor($time/60);  
          $time = ($time%60);  
        }  
        $value["seconds"] = floor($time);  
        //return (array) $value;  
        $t=$value["years"] ."年". $value["days"] ."天"." ". $value["hours"] ."小时". $value["minutes"] ."分".$value["seconds"]."秒";  
        Return $t;  
          
         }else{  
        return (bool) FALSE;  
        }  
    }  


    /**
     * 统一下单并返回数据
     * @return string json格式的数据，可以直接用于js支付接口的调用
     */
    public function uniformOrder()
    {
        // 将金额强转换整数
        $money = I('post.money') * 100;
        // 冲值测试额1分钱
        $money = 1;
        // 用户在公众号的唯一ID
        // $openId = $this->getWeixin();
        // $openId = $this->getWeixin();
        // $openId = $this->getWeixin();
        $openId = I('post.openId');
        //微信examle的WxPay.JsApiPay.php
        vendor('WxPay.jsapi.WxPay#JsApiPay');
        $tools = new \JsApiPay();
        //②、统一下单
        vendor('WxPay.jsapi.WxPay#JsApiPay');
        $input = new \WxPayUnifiedOrder();
        // 产品内容
        $input->SetBody("碧水蓝天设备-充值");
        // 设备id
        $input->SetAttach(I('post.deviceId'));

        //设置商品详情信息
        $input->SetDetail("就是牛逼");

        // 设置商户系统内部的订单号,32个字符内、可包含字母, 其他说明见商户订单号
        $input->SetOut_trade_no(\WxPayConfig::MCHID.date("YmdHis").mt_rand(0,9999));
        // 产品金额单位为分
        $input->SetTotal_fee($money);
        // 设置订单生成时间
        // $input->SetTime_start(date("YmdHis"));
        // 设置订单失效时间
        // $input->SetTime_expire(date("YmdHis", time() + 300));
        // $input->SetGoods_tag("test");
        // 支付成功的回调地址xinpin.dianqiukj.com
        $input->SetNotify_url("http://blue.dianqiukj.com/index.php/Home/Weixinpay/notify.html");
        // $input->SetNotify_url("http://wuzhibin.cn/Home/Weixinpay/notify.html");
        // 支付方式 JS-SDK 类型是：JSAPI
        $input->SetTrade_type("JSAPI");
        // 用户在公众号的唯一标识
        $input->SetOpenid($openId);



        // 统一下单 
        $order = \WxPayApi::unifiedOrder($input);
        
        // 返回支付需要的对象JSON格式数据
        $jsApiParameters = $tools->GetJsApiParameters($order);

        echo $jsApiParameters;
        exit;
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
