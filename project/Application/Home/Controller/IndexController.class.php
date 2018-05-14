<?php
namespace Home\Controller;
use Think\Controller;
use \Org\Util\WeixinJssdk;

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
