<?php
namespace Home\Controller;
use Home\Controller\WechatController;
use Home\Controller\WechatToolsController;
use Home\Controller\WeixinEventController;
class WeixinDeviceEventController
{
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];      
             
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
    /**
* 讲二进制转换成字符串
* @param type $str
* @return type
*/
function BinToStr($str){
    $arr = explode(' ', $str);
    foreach($arr as &$v){
        $v = pack("H".strlen(base_convert($v, 2, 16)), base_convert($v, 2, 16));
    }
 
    return join('', $arr);
}
  // 接受微信服务器下发的事件
    public function DeviceEvent()
    {
       // $res=$this->checkSignature();
       // echo $_GET["echostr"];
        $file_in = file_get_contents("php://input");
        file_put_contents('./file_in.txt', $file_in);

        $d=json_decode($file_in,true);
        file_put_contents('./file_in2.txt', $d);
        // 判断有无数据返回
        
        switch ($d['msg_type']) {
          case 'device_text':
                  // 根据设备编码拿到客户id
                  $WeixinEvent = new WeixinEventController;
                  $auid = $WeixinEvent->getauids($d['device_id']);
                        file_put_contents('./auid.txt', $auid);

                  if ($auid) {
                    $data['device_id'] = $d['device_id'];
                    $data['device_type'] = $d['device_type'];
                    $data['create_time'] = $d['create_time'];
                    $data['open_id'] = $d['open_id'];
                    $data['auid'] = $auid;
                        file_put_contents('./open_id.txt', $d);

                    $linklog = M('linklog');
                    $info = $linklog->add($data);
                    if ($info) {

                      $binding = M('binding');
                      // 先查询是否存在绑定关系
                      $map['open_id'] = $data['open_id'];
                      $map['device_id'] = $data['device_id'];
                      $res = $binding->where($map)->find();
                      // 没有存储，存绑定关系
                      if (!$res) {$result = $binding->add($map);}
                      
                      // 存库成功，发一个消息！！！！！
                        $auser = $WeixinEvent->getauidAll($auid);
                        if ($auser) {
                          $appId = $auser['appid'];
                          $appSecret = $auser['appsecret'];
                          // 实例化微信JSSDK类对象  需要传对用的经销商的Appid跟appSecret
                          $wxJSSDK = new \Org\Util\WeixinJssdk($appId, $appSecret);
                          // 调用获取公众号的全局唯一接口调用凭据
                          $access_token = $wxJSSDK->getAccessToken();
                          // 剩余天数
                          $day = $WeixinEvent->getday($data['device_id']);
                          // 调用微信模板消息回复
                          $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;

                          $time = date('Y-m-d H:i:s',time());
                          $datas = '{
                            "touser":"'.$data['open_id'].'",
                            "template_id":"HZtL8sn3Vn7dvGn3acHxowiZELdrLvFr026SnArtXKk",
                            "data":{
                                "first": {
                                  "value":"您好，设备已连接",
                                  "color":"#173177"
                                },
                                "keyword1": {
                                  "value":"'.$data['device_id'].'",
                                  "color":"#173177"
                                },
                                "keyword2": {
                                  "value":"'.$day.'",
                                  "color":"#173177"
                                },
                                "keyword3": {
                                  "value":"'.$time.'",
                                  "color":"#173177"
                                },
                                "remark":{
                                  "value":"感谢您的使用",
                                  "color":"#173177"
                                }
                            }
                          }';

                          $result = $WeixinEvent->https_request($url, $datas);
                        file_put_contents('./gangge.txt', $result);

                        }
                    }
                  }
            break;
          
            case 'unbind':
                  $WeixinEvent = new WeixinEventController;
                  $auid = $WeixinEvent->getauids($d['device_id']);
                  if ($auid) {
                    $device_id = $d['device_id'];
                    $open_id = $d['open_id'];

                      // 存库成功，发一个消息！！！！！
                      $auser = $WeixinEvent->getauidAll($auid);
                      if ($auser) {
                        $appId = $auser['appid'];
                        $appSecret = $auser['appsecret'];
                        // 实例化微信JSSDK类对象  需要传对用的经销商的Appid跟appSecret
                        $wxJSSDK = new \Org\Util\WeixinJssdk($appId, $appSecret);
                        // 调用获取公众号的全局唯一接口调用凭据
                        $access_token = $wxJSSDK->getAccessToken();
                        
                        // 调用微信模板消息回复
                        $url = "https://api.weixin.qq.com/device/compel_bind?access_token=".$access_token;

                        $datas = '{
                            "device_id": "'.$device_id.'",
                            "openid": "'.$open_id.'"
                          }
                        ';

                        $result = $WeixinEvent->https_request($url, $datas);
                        file_put_contents('./lunge.txt', $result);


                      }
                    
                  }
            break;
        }

        


        $content=(base64_decode($d['content']));
        $dat=unpack('C2m_magicCode/nm_version/nm_totalLength/nm_cmdid/nm_seq/nm_errorCode', $content);
        $str=$dat['m_version'].'  '.$dat['m_totalLength'].' '.$dat['m_magicCode1'].' '.$dat['m_magicCode2'];
        if(($dat['m_magicCode1']==0xfe)&&($dat['m_magicCode2']==0xcf))
        {
            $t=substr($content,12,$dat['m_totalLength']-12);
            if($dat['m_errorCode']==0)
            {
                if($dat['m_cmdid']==0x0001)
                {

                }
            }
            
        }
        $wechatTool=new WechatToolsController;
        $res=$wechatTool->GetWechat(array("original_id"=>"gh_45d2a107fdc9"),
          'https://api.weixin.qq.com/device/get_bind_device',
          array("openid"=>"ofcch1hoNgWntLOlxkYRD3c80hu4"));

        $this->set_php_file('12.php', $res);
  }


  public function get_php_file($filename) {
    return trim(substr(file_get_contents($filename), 15));
  }


  public function set_php_file($filename, $content) {
    $fp = fopen($filename, "w");
    fwrite($fp, "<?php exit();?>" . $content);
    fclose($fp);
  }

    /**
     * 将xml转为array
     * @param  string $xml xml字符串
     * @return array       转换得到的数组
     */
    public function toArray($xml){   
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $result= json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);        
        return $result;
    }

}