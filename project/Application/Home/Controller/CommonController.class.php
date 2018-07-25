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
        // $_SESSION = [];
        // 获取用户信息写入缓存
        if(empty($_SESSION['homeuser'])){
            // 实例化微信JSSDK对象
            $weixin  = new WeixinJssdk('wx57d57fb99d6d838d', 'ec36152955830ec4191507724f3377a6');
            // 获取用户open_id
            $openId      = 'oyYJxxH7MSPFqDzEZeYQGS22G_f8';



            $openId_ifno = $weixin->getSignPackage();

            // dump($openId_ifno);die;

            /*  微信服务器信息  */
            $weixinInfo = [$openId,$openId_ifno];
            session('weixin',$weixinInfo);


            /*  微信服务器信息  */


            // dump($openId);die;
            if (empty($openId)) {
                redirect(U('/Home/Wechat/follow'), 2, '请先关注微信公众号...');

            }
            // $openId   = 'oXwY4t-9clttAFWXjCcNRJrvch3w';
            // $openId   = 'oXwY4t_vkTgtlD0CBTZ-vTbIMWHs';

            // 查询用户信息
            $info = M('wechat')->where("open_id='{$openId}'")->find();

            // dump($info);die;

            // 判断用户是否存在
            if($info){

                $_SESSION['homeuser'] = $info;

            }else{
                // 用户不存在
                // 将用户写入用户表
                $data['open_id'] = $openId;
                M('wechat')->add($data);

            }
        }

    }


}