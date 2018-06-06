<?php
namespace Admin\Controller;
use Think\Controller;

/**
 * 配置管理控制器
 * 
 * @author 潘宏钢 <619328391@qq.com>
 */

class ConfigController extends CommonController 
{
    /**
     * 编辑首页配置方法
     *
     * @author 潘宏钢 <619328391@qq.com>
     */
    public function homepage_configedit()
    {
        $auid = $_SESSION['adminuser']['id'];
        $system_config = M('system_config');

        if (IS_POST) {
            // dump($_POST);die;
            $res = $system_config->where('auid='.$auid)->save($_POST);
            if ($res) {
                $this->success('修改配置成功啦！！！',U('Config/homepage_configedit'));
            } else {
                $this->error('修改配置失败啦！');
            }
        }else{

            $info[] = $system_config->where('auid='.$auid)->find();
            $this->assign('info',$info);
            $this->display();
        }
    }

    /**
     * 系统配置方法
     *
     * @author 潘宏钢 <619328391@qq.com>
     */
    public function system_config()
    {
        $auid = $_SESSION['adminuser']['id'];
        $system_config = M('system_config');

        if (IS_POST) {
            $res = $system_config->where('auid='.$auid)->save($_POST);
            if ($res) {
                $this->success('修改配置成功啦！！！',U('Config/system_config'));
            } else {
                $this->error('修改配置失败啦！');
            }
        }else{

            $info[] = $system_config->where('auid='.$auid)->find();
            $this->assign('info',$info);
            $this->display();
        }

    }

    /**
     * 微信配置方法
     *
     * @author 潘宏钢 <619328391@qq.com>
     */
    public function wx_config()
    {
        $auid = $_SESSION['adminuser']['id'];
        $system_config = M('system_config');

        if (IS_POST) {
            $res = $system_config->where('auid='.$auid)->save($_POST);
            if ($res) {
                $this->success('修改微信配置成功啦！！！',U('Config/wx_config'));
            } else {
                $this->error('修改微信配置失败啦！');
            }
        }else{

            $info[] = $system_config->where('auid='.$auid)->find();
            $this->assign('info',$info);
            $this->display();
        }

    }



}