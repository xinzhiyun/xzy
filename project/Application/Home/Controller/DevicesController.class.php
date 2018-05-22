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
        

        $openid = $weixin->GetOpenid();

        if (empty($_SESSION['openid'])) {
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
        dump($_POST['ticket']);

        $data = '
            {
                "ticket": "TICKET",
                "device_id": "DEVICEID",
                "openid": " OPENID"
            }
        ';



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
