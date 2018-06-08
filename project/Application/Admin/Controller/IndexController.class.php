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
            // $money = D('Flow')->getOrderMoneyAll();
            // 历史订单总次数
            $count = D('Flow')->getOrderCount();
            // 本周每一天连接次数
            $week = D('Linklog')->getLinkByEveryDay();
            // 统计用户总数
            $usercount = D('Users')->getUserCount();
            // 统计设备总数
            $devicecount = D('Devices')->getDeviceCount();

            // 自定义时间段字段
            $fieldname = 'create_time';
            // 自定义时间段一
            $starttime1 = $_SESSION['adminuser']['system_config']['starttime1'];
            $endtime1   = $_SESSION['adminuser']['system_config']['endtime1'];
            $linkcount1 = D('Linklog')->getTimeSlotData($fieldname,$starttime1,$endtime1);
            // 自定义时间段二
            $starttime2 = $_SESSION['adminuser']['system_config']['starttime2'];
            $endtime2   = $_SESSION['adminuser']['system_config']['endtime2'];
            $linkcount2 = D('Linklog')->getTimeSlotData($fieldname,$starttime2,$endtime2);
            // 自定义时间段三
            $starttime3 = $_SESSION['adminuser']['system_config']['starttime3'];
            $endtime3   = $_SESSION['adminuser']['system_config']['endtime3'];
            $linkcount3 = D('Linklog')->getTimeSlotData($fieldname,$starttime3,$endtime3);

	    	$data = [
				'flows'      => $flows,
				// 'money'      => $money,
	    		'count'      => $count,
	    		'week'       => $week,
                'linkcount1' => $linkcount1,
                'linkcount2' => $linkcount2,
                'linkcount3' => $linkcount3,
                'usercount'  => $usercount,
                'devicecount'=> $devicecount
	    	];
            // dump($data);
	    	$this->ajaxReturn($data);
    	}
        
        $this->display('index');

    }

    public function welcome()
    {
        $this->show('<h1>欢迎回来！</h1>');
    }
}

