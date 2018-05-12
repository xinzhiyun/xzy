<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController 
{
	/**
	 * [index 加载充值页面]
	 * @return [type] [description]
	 */
    public function index()
    {
    
    	$weixinInfo = $_SESSION['weixin'];
    	
    	$this->assign('weixin',$weixinInfo);
        $this->display();
    }

    /**
     * [getSetmeal 获取充值套餐]
     * @return [type] [description]
     */
    public function getSetmeal()
    {
    	//先接收前端的device_code
    	$deviceCode = $_POST['device_code'];

    	if (empty($deviceCode)) {
            $this->ajaxReturn(array('msg'=>'设备编码为空','code'=>'201'));

    	} else {
    		//根据设备编码获取经销商id
    		$auid = M('devices')->field('auid')->where("device_code='{$deviceCode}'")->find()['auid'];

    		if ($auid) {
    			//在根据经销商id获取对应的充值套餐
    			$setmeal = M('setmeal')->where('auid='.$auid)->select();
            	$this->ajaxReturn(array('msg'=>$setmeal,'code'=>'200'));


    		} else {
            	$this->ajaxReturn(array('msg'=>'该经销商暂无套餐','code'=>'201'));

    		}
    	}
    }

}
