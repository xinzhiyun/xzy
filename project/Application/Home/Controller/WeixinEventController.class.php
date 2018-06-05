<?php
namespace Home\Controller;
use Home\Controller\WechatController;
use Think\Log;

class WeixinEventController
{
	// 接受微信服务器下发的事件
    public function getEventData()
    {
    	
    	// 接受微信推送的事件
    	$xml=file_get_contents('php://input', 'r');
        file_put_contents('./xml.txt', $xml);

        // $this->responseMsg($xml);
        

		if($xml){
            // 输出空字符串，回复微信服务器
			//echo '1';

			// 转成php数组
			$data = $this->toArray($xml);

            // 判断如果是关注事件
            if($data['Event'] == 'subscribe'){
            // echo 2;   
                // $a = $this->responseMsg($xml);
                // file_put_contents('./aa.txt', $a);
                $this->reactUser($data['FromUserName'],$data['ToUserName']);

                // 实例化微信信息类型
                $Wechat = new WechatController;
                // 调用填写微信信息的方法
                $Wechat->add($data['FromUserName']);
                
                // file_put_contents('./add.txt', $xml);
                exit;
            }

            // 判断如果是取消关注事件
            if($data['Event'] == 'unsubscribe'){
                //file_put_contents('./del.txt', $xml);
                // 实例化微信信息类型
                $Wechat = new WechatController;
                // 调用删除微信信息的方法
                $Wechat->delete($data['FromUserName']);
                exit;
            }

            // 判断如果是点击事件
            if ($data['Event'] == 'CLICK') {
                // 分类一下
                switch ($data['EventKey']) {
                    // 查询天数
                    case 'chaxunday':
                        // 先拿到openid
                        $openid = $data['FromUserName'];
                        // 根据目前公众号原始id查询到客户id，获取APPID
                        $info = $this->getauid($data['ToUserName']);
                        // file_put_contents('./info.txt', $info);

                        $appId = $info['appid'];
                        $appSecret = $info['appsecret'];
                        // 调接口查询该用户绑定的设备
                        // 实例化微信JSSDK类对象  需要传对用的经销商的Appid跟appSecret
                        $wxJSSDK = new \Org\Util\WeixinJssdk($appId, $appSecret);
                        // 调用获取公众号的全局唯一接口调用凭据
                        $access_token = $wxJSSDK->getAccessToken();
                        // file_put_contents('./phg.txt', $access_token);

                        //调用微信接口查看用户绑定的设备
                        $url = "https://api.weixin.qq.com/device/get_bind_device?access_token=".$access_token."&openid=".$openid;

                        //模拟url请求
                        $result = $this->https_request($url);

                        $data = json_decode($result,true)['device_list'];
                        $count = count(json_decode($result,true)['device_list']);

                        // file_put_contents('./data.txt', $data[0]['device_id']);
                        if ($count) {
                            // 根据设备编码查天数
                            foreach ($data as $key => $value) {
                                $day = $this->getday($value['device_id']);
                                // 调用微信模板消息回复
                                $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
                                $time = date('Y-m-d H:i:s',time());
                                $datas = '{
                                    "touser":"'.$openid.'",
                                    "template_id":"HZtL8sn3Vn7dvGn3acHxowiZELdrLvFr026SnArtXKk",
                                    "data":{
                                            "first":{
                                                "value":"您好，查询成功",
                                                "color":"#173177"
                                            },
                                            "keyword1":{
                                                "value":"'.$value['device_id'].'",
                                                "color":"#173177"
                                            },
                                            "keyword2":{
                                                "value":"'.$day.'",
                                                "color":"#173177"
                                            },
                                            "keyword3":{
                                                "value":"'.$time.'",
                                                "color":"#173177"
                                            },
                                            "remark":{
                                                "value":"感谢您的使用",
                                                "color":"#173177"
                                            }
                                    }
                                }';

                                $result = $this->https_request($url, $datas);
                            // file_put_contents('./result.txt', $result);


                            }
                            // $day = $this->getday($data[0]['device_id']);
                            // // file_put_contents('./result.txt', $day);
                            // // 调用微信模板消息回复
                            // $url = "https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;

                            // $datas = '{
                            //     "touser":"'.$openid.'",
                            //     "template_id":"VeFWpHWetPOZNNL2RWXGQHz_RPgueIwx73GGNZqmt_s",         
                            //     "data":{
                            //             "first": {
                            //                 "value":"您的设备'.$data[0]['device_id'].'",
                            //                 "color":"#173177"
                            //             },
                            //             "keyword1":{
                            //                 "value":"剩余天数",
                            //                 "color":"#173177"
                            //             },
                            //             "keyword2": {
                            //                 "value":"'.$day.'",
                            //                 "color":"#173177"
                            //             },
                            //             "keyword3": {
                            //                 "value":"哈哈",
                            //                 "color":"#173177"
                            //             },
                            //             "keyword4": {
                            //                 "value":"哈哈哈",
                            //                 "color":"#173177"
                            //             },
                            //             "remark":{
                            //                 "value":"芯智云科技",
                            //                 "color":"#173177"
                            //             }
                            //     }
                            // }';

                            // $result = $this->https_request($url, $datas);
                            // file_put_contents('./result.txt', $result);

                        }else{
                            // 没有设备
                            // $day = 'no';
                            // file_put_contents('./result.txt', $day);

                            return false;
                        }

                        
                        break;
                    
                    default:
                        # code...
                        break;
                }

            }


		}else{
            // 实例化微信验证对象服务器第一次接入使用
            $wechatObj = new \Org\Util\WechatCallbackapiTest;
            // 执行验证方法
            // 当接入成功后，请注释这段代码，否则会反复验证！！！2018-5-17 潘
            $wechatObj->valid();
        }
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

     public function reactUser($toUser, $fromUser)
    {
        $title = '欢迎您的关注';
        $description = '点球电子，电控板的专家品牌，行业领先地位！在在线检测控制，物联网整体解决方案有着技术领先水平。
我们专注于家、商用净水机电控板的研发、生产、销售。已经服务于国内、外近1000个的厂商，我们更懂得在各种使用环境下的产品需求，我们的专家团队能为厂商提供专业的整体解决方案，提升产品品质和使用体验，进而提升客户的品牌度。
秉持精益求精的工匠精神，专注使得我们更专业，使得我们的客户工作更简单！';
        $src = '';
        $url = '';
        $template = "<xml>
                        <ToUserName><![CDATA[%s]]></ToUserName>
                        <FromUserName><![CDATA[%s]]></FromUserName>
                        <CreateTime>%s</CreateTime>
                        <MsgType><![CDATA[%s]]></MsgType>
                        <ArticleCount>1</ArticleCount>
                        <Articles>
                            <item>
                                <Title><![CDATA[%s]]></Title> 
                                <Description><![CDATA[%s]]></Description>
                                <PicUrl><![CDATA[%s]]></PicUrl>
                                <Url><![CDATA[%s]]></Url>
                            </item>
                        </Articles>
                    </xml> ";

        echo sprintf($template, $toUser, $fromUser, time(), 'news', $title, $description, $src, $url);
        
    }

    // 消息回复
    public function responseMsg()
    {
        //get post data, May be due to the different environments
        //设置了register_globals禁止,不能用$GLOBALS["HTTP_RAW_POST_DATA"];
        // 2018-5-17 潘
        // $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postStr = file_get_contents("php://input");
        //extract post data
        if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
                $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
                            <ToUserName><![CDATA[%s]]></ToUserName>
                            <FromUserName><![CDATA[%s]]></FromUserName>
                            <CreateTime>%s</CreateTime>
                            <MsgType><![CDATA[%s]]></MsgType>
                            <Content><![CDATA[%s]]></Content>
                            <FuncFlag>0</FuncFlag>
                            </xml>";             
                if(!empty( $keyword ))
                {
                    $msgType = "text";
                    $contentStr = "你好，欢迎关注碧水蓝天公众号";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }else{
                    echo "Input something...";
                }

        }else {
            echo "嗨！";
            exit;
        }
    }


    // 根据公众号原始id到咱们的系统查客户id
    public function getauid($ToUserName)
    {
        $info = M('adminuser')->where("original_id='{$ToUserName}'")->select();
        if ($info) {
            return $info[0];
        }else{
            return false;
        }
    }

    // 根据设备编码查剩余天数
    public function getday($device_code)
    {
        // 拿到到期时间
        $outtime = M('devices')->where("device_code='{$device_code}'")->getField('outtime');
        // 到期时间-当前时间
        $time = $outtime-time();
        $outtime = $this->Sec2Time($time);

        return $outtime;

    }

    // 根据客户id查询该客户所有信息
    public function getauidAll($id)
    {
        $info = M('adminuser')->where("id='{$id}'")->select();
        if ($info) {
            return $info[0];
        }else{
            return false;
        }
    }

    // 根据设备编码查客户id
    public function getauids($device_id)
    {
        $info = M('devices')->where("device_code='{$device_id}'")->getField('auid');
        if ($info) {
            return $info;
        }else{
            return false;
        }
    }

    /**
     * [Sec2Time 将秒数转换为时间（年、天、小时、分、秒）]
     * @param [type] $time [description]
     */
    public function Sec2Time($time)
    {  
        if(is_numeric($time)){  
        $value = array(  
          "years" => 0, "days" => 0, "hours" => 0,  
          "minutes" => 0, "seconds" => 0,  
        );  
        // if($time >= 31556926){  
        //   $value["years"] = floor($time/31556926);  
        //   $time = ($time%31556926);  
        // }  
        if($time >= 86400){  
          $value["days"] = floor($time/86400);  
          $time = ($time%86400);  
        }  
        if($time >= 3600){  
          $value["hours"] = floor($time/3600);  
          $time = ($time%3600);  
        }  
        if($time >= 60){  
          $value["minutes"] = floor($time/60);  
          $time = ($time%60);  
        }  
        $value["seconds"] = floor($time);  
        //return (array) $value;  
        $t=$value["days"] ."天";  
        Return $t;  
          
         }else{  
        return (bool) FALSE;  
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