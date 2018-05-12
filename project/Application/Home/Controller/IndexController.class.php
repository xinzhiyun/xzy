<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends CommonController {
    public function index(){
    	// dump(U());
    	// dump($_SESSION);
    	$weixinInfo = $_SESSION['weixin'];
    	// dump($weixinInfo);
    	// die;
    	$this->assign('weixin',$weixinInfo);
        // $this->display();
    }
}