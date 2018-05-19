<?php
namespace Home\Controller;
use Think\Controller;
use \Org\Util\WeixinJssdk;
use Think\Log;

// 引入微信事件接收类
// use Home\Controller\WeixinEventController;

class WechatToolsController extends Controller 
{
    public function getWechatInfo($dataSoure)
    {
        if($dataSoure['original_id'])
        {
            $info = M('adminuser')->where("original_id='{$dataSoure['original_id']}'")->select();
        }
        else if($dataSoure['auid'])
        {
            $info = M('adminuser')->where("auid='{$dataSoure['auid']}'")->select();
        }
        if ($info) {
            return $info[0];
        }else{
            return false;
        }
    }
    public function getAccessToken($dataSoure)
    {
        $info=$this->getWechatInfo($dataSoure);
        if($info)
        {
             // 调接口查询该用户绑定的设备
             // // 实例化微信JSSDK类对象  需要传对用的经销商的Appid跟appSecret
            $wxJSSDK = new \Org\Util\WeixinJssdk($info['appid'], $info['appsecret']);
            // 调用获取公众号的全局唯一接口调用凭据
            return $wxJSSDK->getAccessToken();
        }
        else
        {
            return false;
        }
    }
    public function GetWechat($url,$parmlist,$AccessToken)
    {
         $this->set_php_file('12.php', $url);
        $parmliststr=$url.'?access_token='.$AccessToken;
        $i=0;
        foreach ($parmlist as $key => $value) {

            $parmliststr.='&';
            $parmliststr.=$key.'=';
            $parmliststr.=$value;
        }
        //模拟url请求
        return $this->https_request($url);
    }
    public function GetWechat($dataSoure,$url,$parmlist)
    {
        $AccessToken=$this->getAccessToken($dataSoure);
        if($AccessToken)
        {
            return $this->GetWechat($url,$parmlist,$AccessToken);
        }
        else
        {
            return false;
        }
    }
      public function set_php_file($filename, $content) {
    $fp = fopen($filename, "w");
    fwrite($fp, "<?php exit();?>" . $content);
    fclose($fp);
  }
}