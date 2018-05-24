<?php
namespace Admin\Controller;
use Think\Controller;
use Admin\Controller\CommonController;
class IndexController extends CommonController {
    public function index(){
    	if (IS_AJAX) {
    		// 本月每天充值次数及金额
	    	$flows = D('Flow')->getTotalByEveryDay();
            // 历史订单总金额
            $money = D('Flow')->getOrderMoneyAll();
            // 历史订单总次数
            $count = D('Flow')->getOrderCount();
            // 本周每一天连接次数
            $week = D('Linklog')->getLinkByEveryDay();
            
	    	$data = [
				'flows' => $flows,
				'money'=> $money,
	    		'count' => $count,
	    		'week' => $week
	    	];
	    	$this->ajaxReturn($data);
    	}

        $this->display('index');

    }

    public function welcome()
    {
        $this->show('<h1>欢迎回来！</h1>');
    }
}

