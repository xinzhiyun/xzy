<?php
namespace Home\Controller;
use Home\Controller\WechatController;
use Home\Controller\WechatToolsController;
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
        $d=json_decode($file_in,true);

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