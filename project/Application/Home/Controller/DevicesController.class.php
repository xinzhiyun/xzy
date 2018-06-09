<?php
namespace Home\Controller;
use Think\Controller;
use \Org\Util\WeixinJssdk;

class DevicesController extends Controller 
{
	/**
	 * [index 我的设备]
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
        

        

        if (empty($_SESSION['openid'])) {
            $openid = $weixin->GetOpenid();
            $_SESSION['openid'] = $openid;
        }

        $this->assign('weixin',$openId_ifno);
        $this->display();
    }


    /**
     * [unbind 设备解绑]
     * @return [type] [description]
     */
    public function unbind()
    {
        $ticket = $_POST['ticket'];
        $device_id = $_POST['device_id'];
        $openid = $_SESSION['openid'];


        // 根据客户id获取客户微信公众号信息
        $info = M('adminuser')->where('id='.$_SESSION['auid'])->find();

        $appid = $info['appid'];
        $appsecret = $info['appsecret'];

        $weixin  = new WeixinJssdk($appid, $appsecret);

        $access_token = $weixin->getAccessToken();


        $url = "https://api.weixin.qq.com/device/unbind?access_token=".$access_token;

        $data = '
            {
                "ticket": "'.$ticket.'",
                "device_id": "'.$device_id.'",
                "openid": "'.$openid.'"
            }
        ';

        $result = $this->https_request($url,$data);

        $bool = json_decode($result,true)['base_resp']['errmsg'];

        if ($bool == 'ok') {
            //修改设备状态
            M('devices')->startTrans();
            M('binding')->startTrans();
            $mes['status'] = 0;

            $info = M('devices')->where("device_code='{$device_id}'")->find();
            $info1 = M('binding')->where("device_id='{$device_id}'")->find();

            if ($info && $info1) {
                $a = M('devices')->where("device_code='{$device_id}'")->save($mes);
                //解除设备跟用户的绑定关系
                $b = M('binding')->where("device_id='{$device_id}'"." AND open_id='{$openid}'")->delete();
            }
            

            if ($a && $b) {
                M('devices')->commit();
                M('binding')->commit();
                $this->ajaxReturn(array('msg'=>'解绑成功','code'=>'200'));

            } else {
                M('devices')->rollback();
                M('binding')->rollback();
                $this->ajaxReturn(array('msg'=>'解绑失败','code'=>'201'));

            }

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
