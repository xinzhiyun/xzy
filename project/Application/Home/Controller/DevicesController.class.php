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

   
}