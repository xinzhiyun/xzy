<?php
/**
 * Created by PhpStorm.
 * User: 梁康伦
 * Date: 2017/10/20/0020
 * Time: 9:28
 */
namespace Api\Controller;
use Think\Controller;

class LoginController extends Controller
{   
    /**
     * [login 登录接口Api]
     * @return [type] [description]
     */
    public function login()
    {

        if (IS_POST) {
            //接受安卓端的json数据
            $password = md5($_POST['password']);
            $info = M('adminuser')->where("name='{$_POST['name']}'")->find();

            if ($info) {
                if ($info['password'] == $password) {
                    // $_SESSION['adminuser'] = $info;

                    //验证成功
                    $_SESSION['apiuser'] = $info;
                    $this->ajaxReturn(array('msg'=>'登录成功','code'=>'200'));

                } else {
                    $this->ajaxReturn(array('msg'=>'密码错误','code'=>'201'));
                }
            } else {
                $this->ajaxReturn(array('msg'=>'用户名错误','code'=>'201'));
            }
        } else {
            $this->ajaxReturn(array('msg'=>'请求方式有误','code'=>'201'));
        }

    }
}