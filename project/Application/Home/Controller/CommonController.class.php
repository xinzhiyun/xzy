<?php
namespace Home\Controller;
use Think\Controller;
use \Org\Util\WeixinJssdk;
use Home\Controller\WechatController;
/**
 * 前共控制器
 * 前台控制器除login外必须继承我
 * @author 
 */

class CommonController extends Controller 
{
	/**
     * 初始化
     * @author 
     */
    public function _initialize()
    {	
        
        // 获取用户信息写入缓存
        // if(empty($_SESSION['homeuser'])){
            // 实例化微信JSSDK对象
            $weixin      = new WeixinJssdk("wx57d57fb99d6d838d", "ec36152955830ec4191507724f3377a6");
            // 获取用户open_id
            // $openId      = $weixin->GetOpenid();
            $openId_ifno = $weixin->getSignPackage();
            // $openId   = 'oXwY4t-9clttAFWXjCcNRJrvch3w';
            // $openId   = 'oXwY4t_vkTgtlD0CBTZ-vTbIMWHs';
            $weixinInfo = [$openId_ifno];
            session('weixin',$weixinInfo);

            // // 查询用户信息
            // $info = M('Users')->where("open_id='{$openId}'")->find();
            
            // // 判断用户是否存在
            // if($info){
            //     // 用户当前设备
            //     $info['did'] = M('currentDevices')->where("`uid`={$info['id']}")->field('did')->find()['did'];

            //     $_SESSION['homeuser'] = $info;   
                
        //     }else{
        //         // 用户不存在
        //         redirect(U('/Home/Wechat/follow'), 2, '请先关注微信公众号...');
                
        //     }
        // }
    }


}