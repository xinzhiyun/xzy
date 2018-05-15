<?php
/**
 * Created by PhpStorm.
 * User: 梁康伦
 * Date: 2017/10/20/0020
 * Time: 9:28
 */
namespace Api\Controller;
use Think\Controller;

class DevicesController extends Controller
{   
    /**
     * [add 设备入库]
     */
    public function add()
    {

        if (IS_POST) {

            //实例化设备表
            $device = M('devices');
            //接受mac数据
            $data['mac'] = $mac = $_POST['mac'];
            $data['product_id'] = $_POST['id'];

            //查看数据库是否已存在该设备
            $info = $device->where("mac='{$mac}'")->find();

            if ($info) {
                //存在返回信息
                $this->ajaxReturn(array('msg'=>'该设备已经录入系统','code'=>'201'));
            } else {

                    //不存在则入库
                    $data['auid'] = $_SESSION['apiuser']['id'];
                    $product_id  = $_POST['product_id'];

                    $data['lasttime'] = time();
                    $data['inittime'] = 7200 * 24;

                    //根据登录的经销商获取其对用的设备编码
                    $appId = $_SESSION['apiuser']['appid'];
                    $appSecret = $_SESSION['apiuser']['appsecret'];

                    // 实例化微信JSSDK类对象  需要传对用的经销商的Appid跟appSecret
                    $wxJSSDK = new \Org\Util\WeixinJssdk($appId, $appSecret);
                    // 调用获取公众号的全局唯一接口调用凭据
                    $access_token = $wxJSSDK->getAccessToken();

                    //调用微信接口自动生成设备号
                    $url = "https://api.weixin.qq.com/device/getqrcode?access_token=".$access_token."&product_id=".$product_id;

                    //模拟url请求
                    $result = $this->https_request($url);

                    //验证是否请求成功
                    if (json_decode($result,true)['base_resp']['errmsg'] == "ok" ) {
                        $deviceid = $data['device_code'] = json_decode($result,true)['deviceid'];
                    } else {
                        $this->ajaxReturn(array('msg'=>'自动生成设备id有问题','code'=>'201'));
                    }

                    //将蓝牙信息跟设备号同步到微信服务器
                    $url1 = "https://api.weixin.qq.com/device/authorize_device?access_token=".$access_token;
                    $body = '{
                        "device_num": "1", 
                        "device_list": [
                            {
                                "id": "'.$deviceid.'", 
                                "mac": "'.$mac.'", 
                                "connect_protocol": "3", 
                                "auth_key": "", 
                                "close_strategy": "1", 
                                "conn_strategy": "1", 
                                "crypt_method": "0", 
                                "auth_ver": "1", 
                                "manu_mac_pos": "-1", 
                                "ser_mac_pos": "-2", 
                                "ble_simple_protocol": "0"
                            }
                        ], 
                        "op_type": "0", 
                        "product_id": "'.$product_id.'" 
                    }';

                    //模拟url请求
                    $res = $this->https_request($url,$body);

                    //验证是否写入微信服务器
                    if (json_decode($res,true)['base_resp']['errmsg'] == "ok" ) {
                        //写入则入库
                        $deviceInfo = $device->add($data);

                        if ($deviceInfo) {
                            $this->ajaxReturn(array('msg'=>'设备入库成功','code'=>'200'));
                        } else {
                            $this->ajaxReturn(array('msg'=>'设备入库失败','code'=>'201'));
                        }
                    } else {
                        $this->ajaxReturn(array('msg'=>'mac地址写入微信服务器失败','code'=>'201'));
                    }
                }

        } else {
            $this->ajaxReturn(array('msg'=>'请求方式有误','code'=>'201'));
        }
        
    }

    /**
     * [getProductId 产品型号id]
     * @return [type] [description]
     */
    public function getProduct()
    {
        if (IS_GET) {
            $productList = M('product')->where("auid=".$_SESSION['apiuser']['id'])->select();
            $this->ajaxReturn(array('msg'=>$productList,'code'=>'200'));
        } else {
            $this->ajaxReturn(array('msg'=>'请求方式有误','code'=>'201'));
        }
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