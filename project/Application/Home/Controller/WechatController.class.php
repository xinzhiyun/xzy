<?php
namespace Home\Controller;
use Think\Controller;
use \Org\Util\WeixinJssdk;
use Think\Log;
use Home\Controller\WeixinEventController;

// 引入微信事件接收类
// use Home\Controller\WeixinEventController;

class WechatController extends Controller 
{
	/**
     * [index 微信关注事件-填写微信信息表]
     * @return [type] [description]
     */
    public function add($openid,$ToUserName)
    {
        // 获取用户openid
        $openid = $openid;
        // 根据公众号原始id获取客户信息，拿APPID
        $WeixinEvent = new WeixinEventController;
        $info = $WeixinEvent->getauid($ToUserName);
        
        $appId = $info['appid'];
        $appSecret = $info['appsecret'];

        // 实例化微信JSSDK类对象
        $wxJSSDK = new \Org\Util\WeixinJssdk($appId, $appSecret);
        // 调用获取公众号的全局唯一接口调用凭据
        $accessToken = $wxJSSDK->getAccessToken();
        //show($accessToken);die;
        // 请求返回简体中文版用户信息数据
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token='.$accessToken.'&openid='.$openid.'&lang=zh_CN';

        // 发送请求获取用户信息
        $userInfo = $wxJSSDK->httpGet($url);

        // 把 JSON 格式的字符串转换为PHP数组
        $userInfo = json_decode($userInfo, true);

        // 准备微信信息表数据
        // 微信的ID号
        $data['open_id'] = $userInfo['openid'];
        $data['nickname'] = $userInfo['nickname'];
        $data['head'] = $userInfo['headimgurl'];
        // 性别{0:未定义, 1:男, 2:女}
        $data['sex'] = $userInfo['sex'];
        // 地区 省份
        $data['area'] = $userInfo['province'];
        // 国家 省份 市区
        $data['address'] = $userInfo['country'].' '.$userInfo['province'].' '.$userInfo['city'];
        // 根据微信openid查询数据库id字段1条数据
        $userId = M('Wechat')->field('id')->where('`open_id`="'.$data['open_id'].'"')->find();
        file_put_contents('./openid.txt', $userInfo['openid']);

        // 如果数据库并未存储，将用户信息写入数据库
        if(empty($userId)){
            // 将用户信息写入数据库
            $insertId = M('Wechat')->data($data)->add();
        }
    }

    /**
     * [delete 取消关注事件 修改用户状态]
     * @param  [type] $openid [description]
     * @return [type]         [description]
     */
    public function delete($openid)
    {
        // 获取用户openid
        $openid = $openid;
        // 删除库信息
        M('Wechat')->where('`open_id`="'.$openid.'"')->delete();
    }


    // 请先关注微信公众号
    public function follow()
    {
        // 显示模板
        $this->display();
    }


    /**
     * 生成自定义菜单
     * @return bool true or false
     */
    public function create_menu()
    {
        $appid = $_POST['appid'];
        $appsecret = $_POST['appsecret'];
        $auid = $_POST['auid'];
        // $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

        // $output = $this->https_request($url);
        // $jsoninfo = json_decode($output, true);

        // $access_token = $jsoninfo["access_token"];
        // 
        // 实例化微信JSSDK类对象
        $weixin  = new WeixinJssdk($appid, $appsecret);

        // 调用获取公众号的全局唯一接口调用凭据
        $access_token = $weixin->getAccessToken();

        // Log::write($access_token, '伦伦');
        // "button":[{
        //         "name":"查询",
        //         "type":"click",
        //         "key":"chaxunday"
        //     }],
        $jsonmenu = '{

            "button":[
            {   
                "name":"充值及查询",
                "sub_button":[
                    {
                        "type":"view",
                        "name":"充值",
                        "url":"http://blue.dianqiukj.com/index.php/Home/Index/index.html?auid='.$auid.'"
                    },
                    {
                        "type": "click", 
                        "name": "查询", 
                        "key": "chaxunday"
                    } 
                ]
            }],

            

            "button":[{
                "name":"我的设备",
                "type":"view",
                "url":"http://blue.dianqiukj.com/index.php/Home/Devices/index.html?auid='.$auid.'"
            }], 
           
        }';

        // 商城菜单
        // "button":[{
        //         "name":"商城",
        //         "type":"view",
        //         "url":"https://free.modao.cc/app/jqfBZUmESUSi18gaUHqB3E96H44ZUEN#screen=s4CB7DBA5E01527244778913"
        //     }],
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create?access_token=".$access_token;


        $result = $this->https_request($url, $jsonmenu);
        dump($result);

    }

    /**
     * CURL使用
     * @param  string $url  URL地址
     * @param  Array $data 传递数据
     * @return string  $output     传递数据时返回的结果
     */
    public function https_request($url,$data = null){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

}