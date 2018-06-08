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
            // 搜索功能
            $map = array(
                'days' => trim(I('post.days')),
                'name' => trim(I('post.name')),
            );
            // dump($map);die;
            if (trim(I('post.describe'))) {
                $map['s.describe'] =  array('like','%'.trim(I('post.describe')).'%');
            }
            $minmoney = trim(I('post.minmoney'))?:false;
            $maxmoney = trim(I('post.maxmoney'))?:false;
            if (is_numeric($maxmoney)) {
                $map['s.money'][] = array('elt',$maxmoney*100);
            }
            if (is_numeric($minmoney)) {
                $map['s.money'][] = array('egt',$minmoney*100);
            }
             $minaddtime = strtotime(trim(I('post.minaddtime')))?:false;
             $maxaddtime = strtotime(trim(I('post.maxaddtime')))?:false;
             if (is_numeric($maxaddtime)) {
                 $map['s.addtime'][] = array('elt',$maxaddtime);
             }
             if (is_numeric($minaddtime)) {
                 $map['s.addtime'][] = array('egt',$minaddtime);
             }
            // 删除数组中为空的值
            $map = array_filter($map, function ($v) {
                if ($v != "") {
                    return true;
                }
                return false;
            });

            $total =$setmeal->where($map)
                            ->alias('s')
                            ->join("__ADMINUSER__ admin ON s.auid=admin.id", 'LEFT')
                            ->join("__PRODUCT__ p ON s.product_id=p.id", 'LEFT')
                            ->field("s.*,admin.name,p.typename")
                            ->count();

            $page  = new \Think\Page($total,8);
            D('devices')->getPageConfig($page);
            $pageButton =$page->show();

            // dump($map);die;
            $list = $setmeal->where($map)
                            ->limit($page->firstRow.','.$page->listRows)
                            ->alias('s')
                            ->join("__ADMINUSER__ admin ON s.auid=admin.id", 'LEFT')
                            ->join("__PRODUCT__ p ON s.product_id=p.id", 'LEFT')
                            ->field("s.*,admin.name,p.typename")
                            ->order('s.addtime desc')
                            ->select(); 
            // dump($list);die;

        } else {
            // 只查自己
            // $map['auid'] = $auid;

            // 搜索功能
            $map = array(
                'days' => trim(I('post.days')),
                'describe' => trim(I('post.describe')),
                'name' => trim(I('post.name')),
                'auid' => $auid, // 只查自己
            );
            // dump($map);die;
            if (trim(I('post.describe'))) {
                $map['s.describe'] =  array('like','%'.trim(I('post.describe')).'%');
            }
            $minmoney = trim(I('post.minmoney'))?:false;
            $maxmoney = trim(I('post.maxmoney'))?:false;
            if (is_numeric($maxmoney)) {
                $map['s.money'][] = array('elt',$maxmoney*100);
            }
            if (is_numeric($minmoney)) {
                $map['s.money'][] = array('egt',$minmoney*100);
            }
             $minaddtime = strtotime(trim(I('post.mintime')))?:false;
             $maxaddtime = strtotime(trim(I('post.maxtime')))?:false;
             if (is_numeric($maxaddtime)) {
                 $map['s.addtime'][] = array('elt',$maxaddtime);
             }
             if (is_numeric($minaddtime)) {
                 $map['s.addtime'][] = array('egt',$minaddtime);
             }
            // 删除数组中为空的值
            $map = array_filter($map, function ($v) {
                if ($v != "") {
                    return true;
                }
                return false;
            });

            $total =$setmeal->where($map)
                            ->alias('s')
                            ->join("__ADMINUSER__ admin ON s.auid=admin.id", 'LEFT')
                            ->join("__PRODUCT__ p ON s.product_id=p.id", 'LEFT')
                            ->field("s.*,admin.name,p.typename")
                            ->count();

            $page  = new \Think\Page($total,8);
            D('devices')->getPageConfig($page);
            $pageButton =$page->show();

            // dump($map);die;
            $list = $setmeal->where($map)
                            ->limit($page->firstRow.','.$page->listRows)
                            ->alias('s')
                            ->join("__ADMINUSER__ admin ON s.auid=admin.id", 'LEFT')
                            ->join("__PRODUCT__ p ON s.product_id=p.id", 'LEFT')
                            ->field("s.*,admin.name,p.typename")
                            ->order('s.addtime desc')
                            ->select();

        }

        $this->assign('list',$list);
        $this->assign('button',$pageButton);
        $this->display();

    }

    public function add()
    {
        if (IS_POST) {
            // dump($_POST);die;
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
            $map['auid'] = $_SESSION['adminuser']['id'];
            $info = M('product')->where($map)->select();
            $this->assign('info',$info);
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
