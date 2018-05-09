<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\MYExcel;
/**
 * 充值套餐控制器
 * 后台用来设置充值套餐和浏览充值套餐的控制器
 * 
 * @author 潘宏钢 <619328391@qq.com>
 */

class SetmealController extends CommonController 
{
	/**
     * 套餐列表
     * admin看所有，其他只能看自己设置的
     * @author 潘宏钢 <619328391@qq.com>
     */
    public function index()
    {	
        // 准备用户id
        $auid = $_SESSION['adminuser']['id'];
        $setmeal = D('setmeal');

        if ($auid == 1) {
            // $map 正常做搜索使用
            $map = '';
            
            $list = $setmeal->where($map)
                            ->alias('s')
                            ->join("__ADMINUSER__ admin ON s.auid=admin.id", 'LEFT')
                            ->field("s.*,admin.name")
                            ->select(); 
            // dump($list);die;

        } else {
            // 只查自己
            $map['auid'] = $auid;

            $list = $setmeal->where($map)
                            ->alias('s')
                            ->join("__ADMINUSER__ admin ON s.auid=admin.id", 'LEFT')
                            ->field("s.*,admin.name")
                            ->select();

        }

        $this->assign('list',$list);
        $this->display();

    }

    public function add()
    {
        if (IS_POST) {
            // dump($_SESSION);die;
            $_POST['auid'] = $_SESSION['adminuser']['id'];
            $setmeal = D('setmeal');
            $info = $setmeal->create();
            
            if($info){

                $res = $setmeal->add();
                if ($res) {
                    $this->success('套餐设置成功啦！！！',U('setmeal/index'));
                } else {
                    $this->error('套餐设置失败啦！');
                }
            
            } else {
                // getError是在数据创建验证时调用，提示的是验证失败的错误信息
                $this->error($setmeal->getError());
            }

        }else{
            $this->display();
        }
    }

    /**
     * 删除类型方法（废除）
     * 不做删除，只做隐藏，如果要做删除产品类型，请确保产品类型没有被设备所用 
     *
     * @author 潘宏钢 <619328391@qq.com>
     */
    public function del()
    {
        $map['id'] = I("get.id");
        $res = M('setmeal')->where($map)->delete();
        if($res) {
            $this->success("删除成功");
        } else {
            $this->error("删除失败");
        }
    }    

    public function test()
    {
        $filename = '套餐列表';
        $title = '套餐列表1';
        $cellName = ['id','工单编号','工单标题','电话','类型','内容','地址','结果','时间'];
        $data = D('work')->select();
        // dump($data);die;
        $myexcel = new \Org\Util\MYExcel($filename,$title,$cellName,$data);
        $myexcel->output();
    }
}
