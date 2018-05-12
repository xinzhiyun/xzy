<?php
namespace Home\Controller;
use Think\Controller;
class DevicesController extends CommonController 
{
	/**
	 * [index 我的设备]
	 * @return [type] [description]
	 */
    public function index()
    {
    	echo $_SESSION['homeuser']['open_id'];

    	$this->display();
    }
}