<?php
namespace Admin\Controller;
use Admin\Controller\CommonController;
use Think\Controller;

/**
 * 设备控制器
 * 后台用来浏览设备情况的控制器
 * admin看所有，其他只能看自己的
 * @author 潘宏钢 <619328391@qq.com>
 */
class DevicesController extends CommonController
{
    /**
     * 显示设备列表
     */
    public function index()
    {
        // 准备用户id
        $auid = $_SESSION['adminuser']['id'];
        $devices = D('devices');

        if ($auid == 1) {
            // $map 正常做搜索使用
            // 搜索功能
            if(I('post.device_code')){
                $map['device_code']=array('like','%'.trim(I('post.device_code')).'%');
            }

            if(I('post.mac')){
                $map['mac']=array('like','%'.trim(I('post.mac')).'%');
            }

            if(I('post.typename')){
                $map['typename']=array('like','%'.trim(I('post.typename')).'%');
            }

            if(I('post.name')){
                $map['name']=array('like','%'.trim(I('post.name')).'%');
            }

            if(I('post.username')){
                $map['username']=array('like','%'.trim(I('post.username')).'%');
            }

            if(I('post.phone')){
                $map['phone']=array('like','%'.trim(I('post.phone')).'%');
            }

            if(I('post.address')){
                $map['address']=array('like','%'.trim(I('post.address')).'%');
            }

            if(I('post.status') != ''){
                $map['status']=I('post.status');
            }
            // dump($_POST);die;
            // $map = array(
            //     'device_code' => array('like','%'.trim(I('post.device_code')).'%'),
            //     'mac' => array('like','%'.trim(I('post.mac')).'%'),
            //     'name' => array('like','%'.trim(I('post.name')).'%'),
            //     'username' => array('like','%'.trim(I('post.username')).'%'),
            //     'phone' => array('like','%'.trim(I('post.phone')).'%'),
            //     'address' => array('like','%'.trim(I('post.address')).'%'),
            //     'status' => array('like','%'.trim(I('post.status')).'%'),
            // );

            // 到期时间

            $minouttime = strtotime(trim(I('post.minouttime')))?:false;
            $maxouttime = strtotime(trim(I('post.maxouttime')))?:false;


            if (is_numeric($maxouttime)) {
                $map['outtime'] = array(array('egt',$minouttime),array('elt',$maxouttime));
            }
            if ($maxouttime < 0) {
                $map['outtime'] = array(array('egt',$minouttime));
            }
            // 最后访问时间
            $minlasttime = strtotime(trim(I('post.minlasttime')))?:false;
            $maxlasttime = strtotime(trim(I('post.maxlasttime')))?:false;


            if (is_numeric($maxlasttime)) {
                $map['lasttime'] = array(array('egt',$minlasttime),array('elt',$maxlasttime));
            }
            if ($maxlasttime < 0) {
                $map['lasttime'] = array(array('egt',$minlasttime));
            }

            // 删除数组中为空的值
            $map = array_filter($map, function ($v) {
                if ($v != "") {
                    return true;
                }
                return false;
            });

            $total =$devices->where($map)
                            ->alias('d')
                            ->join("__ADMINUSER__ admin ON d.auid=admin.id", 'LEFT')
                            ->join("__PRODUCT__ p ON d.product_id=p.id", 'LEFT')
                            ->field("d.*,admin.name,p.typename")
                            ->count();

            $page  = new \Think\Page($total,8);
            D('devices')->getPageConfig($page);
            $pageButton =$page->show();

            // dump($map);die;
            $list = $devices->where($map)
                            ->alias('d')
                            ->join("__ADMINUSER__ admin ON d.auid=admin.id", 'LEFT')
                            ->join("__PRODUCT__ p ON d.product_id=p.id", 'LEFT')
                            ->field("d.*,admin.name,p.typename")
                            ->order('d.outtime desc')
                            ->limit($page->firstRow.','.$page->listRows)
                            ->select(); 
            // dump($list);die;


        } else {
            // 只查自己
            $map['auid'] = $auid;

            // 搜索功能
            if(I('post.device_code')){
                $map['device_code']=array('like','%'.trim(I('post.device_code')).'%');
            }

            if(I('post.mac')){
                $map['mac']=array('like','%'.trim(I('post.mac')).'%');
            }

            if(I('post.typename')){
                $map['typename']=array('like','%'.trim(I('post.typename')).'%');
            }

            if(I('post.name')){
                $map['name']=array('like','%'.trim(I('post.name')).'%');
            }

            if(I('post.username')){
                $map['username']=array('like','%'.trim(I('post.username')).'%');
            }

            if(I('post.phone')){
                $map['phone']=array('like','%'.trim(I('post.phone')).'%');
            }

            if(I('post.address')){
                $map['address']=array('like','%'.trim(I('post.address')).'%');
            }

            if(I('post.status') != ''){
                $map['status']=I('post.status');
            }

            // $map = array(
            //     'device_code' => array('like','%'.trim(I('post.device_code')).'%'),
            //     'mac' => array('like','%'.trim(I('post.mac')).'%'),
            //     'name' => array('like','%'.trim(I('post.name')).'%'),
            //     'username' => array('like','%'.trim(I('post.username')).'%'),
            //     'phone' => array('like','%'.trim(I('post.phone')).'%'),
            //     'address' => array('like','%'.trim(I('post.address')).'%'),
            //     'status' => array('like','%'.trim(I('post.status')).'%'),
            // );

            // 到期时间

            $minouttime = strtotime(trim(I('post.minouttime')))?:false;
            $maxouttime = strtotime(trim(I('post.maxouttime')))?:false;


            if (is_numeric($maxouttime)) {
                $map['outtime'] = array(array('egt',$minouttime),array('elt',$maxouttime));
            }
            if ($maxouttime < 0) {
                $map['outtime'] = array(array('egt',$minouttime));
            }
            // 最后访问时间
            $minlasttime = strtotime(trim(I('post.minlasttime')))?:false;
            $maxlasttime = strtotime(trim(I('post.maxlasttime')))?:false;


            if (is_numeric($maxlasttime)) {
                $map['lasttime'] = array(array('egt',$minlasttime),array('elt',$maxlasttime));
            }
            if ($maxlasttime < 0) {
                $map['lasttime'] = array(array('egt',$minlasttime));
            }

            // 删除数组中为空的值
            $map = array_filter($map, function ($v) {
                if ($v != "") {
                    return true;
                }
                return false;
            });

            $total =$devices->where($map)
                            ->alias('d')
                            ->join("__ADMINUSER__ admin ON d.auid=admin.id", 'LEFT')
                            ->join("__PRODUCT__ p ON d.product_id=p.id", 'LEFT')
                            ->field("d.*,admin.name,p.typename")
                            ->count();

            $page  = new \Think\Page($total,8);
            D('devices')->getPageConfig($page);
            $pageButton =$page->show();

            // dump($map);die;
            $list = $devices->where($map)
                            ->alias('d')
                            ->join("__ADMINUSER__ admin ON d.auid=admin.id", 'LEFT')
                            ->join("__PRODUCT__ p ON d.product_id=p.id", 'LEFT')
                            ->field("d.*,admin.name,p.typename")
                            ->order('d.outtime desc')
                            ->limit($page->firstRow.','.$page->listRows)
                            ->select(); 
            // dump($list);die;

        }

        $this->assign('list',$list);
        $this->assign('button',$pageButton);
        $this->display();       
    }

    public function userBindDvice()
    {
        
    }

    /**
     * 显示设备添加页面
     */
    public function show_add_device()
    {
        $res = M('DeviceType')->select();
        $this->assign('res', $res);
        $this->display('show_add_device');
    }

    /**
     * 设备添加处理
     */
    public function add_device()
    {
        $data = I('post.');
        $device_code = trim($data['device_code']);

        $devices_model = M('Devices');

        //判断库里有没有这个设备编码
        $devices = $devices_model->where('device_code = '.$device_code)->find();

        //设备添加和更新
        if(!empty($devices)){
            $did = $devices['id'];
            if ($_POST['type_id'] != $devices['type_id']) {
                $bool=$devices_model->where('id = '.$did)->save(['type_id'=>$_POST['type_id'],
                    'addtime'=>time()]);
            }
        }else{
            $bool = $devices_model->add($data);
        }

        if(empty($bool)){
            $this->error('添加失败', 'show_add_device');
        } else {
            $this->success('添加成功', 'show_add_device', 3);
        }
    }

    // 设备详情
    public function devices_detail()
    {
        $this->display();
    }

    // 根据设备编码获取用户
    public function getUsers($code)
    {
        $info = M('Devices')->where("device_code='{$code}'")
                            ->alias('d')
                            ->join("__ADMINUSER__ admin ON d.auid=admin.id", 'LEFT')
                            ->field('admin.*,d.device_code')
                            ->find();

        if ($info) {
            if ($info['appid']) {
                if ($info['appsecret']) {
                    if ($info['original_id']) {
                        // 万事大吉
                        $appId = $info['appid'];
                        $appSecret = $info['appsecret'];
                        $device_type = $info['original_id'];
                        // 调接口查询该用户绑定的设备
                        // 实例化微信JSSDK类对象  需要传对用的经销商的Appid跟appSecret
                        $wxJSSDK = new \Org\Util\WeixinJssdk($appId, $appSecret);
                        // 调用获取公众号的全局唯一接口调用凭据
                        $access_token = $wxJSSDK->getAccessToken();
                        // dump($access_token);die;
                        //调用微信接口查看用户绑定的设备
                        $url = "https://api.weixin.qq.com/device/get_openid?access_token=".$access_token."&device_type=".$device_type."&device_id=".$code;

                        //模拟url请求
                        $result = $this->https_request($url);

                    }else{
                        $this->ajaxReturn(array('msg'=>'您尚未填写公众号原始ID，无法查询','code'=>'204'));
                    }
                }else{
                    $this->ajaxReturn(array('msg'=>'您尚未填写APPsecret，无法查询','code'=>'203'));
                }
            }else{
                $this->ajaxReturn(array('msg'=>'您尚未填写APPID，无法查询','code'=>'202'));
            }
        }else{
            $this->ajaxReturn(array('msg'=>'设备编码错误，该设备不存在','code'=>'201'));
        }
        $this->ajaxReturn($result);
    }

    // 根据设备编码获取用户数据
    public function getUsersInfo($code)
    {
        if (IS_AJAX) {
            $info = M('Devices')->where("device_code='{$code}'")
                            ->alias('d')
                            ->join("__ADMINUSER__ admin ON d.auid=admin.id", 'LEFT')
                            ->field('admin.*,d.device_code')
                            ->find();
            if ($info) {
                $binding = M('binding');
                $map['device_id'] = $code;
                $res = $binding->where($map)
                                ->alias('b')
                                ->join("__WECHAT__ w ON b.open_id=w.open_id", 'LEFT')
                                ->field('w.*')
                                ->select();
                // dump($info);die;
                if ($res) {
                    $this->ajaxReturn($res);
                }else{
                    $this->ajaxReturn(array('msg'=>'该设备未被任何用户绑定','code'=>'202'));
                }  

            }else{
                $this->ajaxReturn(array('msg'=>'设备编码错误，该设备不存在','code'=>'201'));
            }
            
        }

    }

    // 查询滤芯详情
    public function getFilterDetail($sum)
    {
        unset($sum['id'],$sum['typename'],$sum['addtime']);
        $sum = array_filter($sum);
        foreach ($sum as $key => $value) {
            $str = stripos($value,'-');
            $map['filtername'] = substr($value, 0,$str);
            $map['alias'] = substr($value, $str+1);
            $res[] = M('filters')->where($map)->find();
        }
        return $res;
    }

    /**
     * 批量上传
     * @return [type] [description]
     */
    public function upload()
    {   
        header("Content-Type:text/html;charset=utf-8");
        $upload = new \Think\Upload(); // 实例化上传类
        $upload->maxSize = 3145728; // 设置附件上传大小
        $upload->exts = array(
            'xls',
            'xlsx'
        ); // 设置附件上传类
        $upload->savePath = '/'; // 设置附件上传目录
        // 上传文件
        $info = $upload->uploadOne($_FILES['batch']);
        $filename = './Uploads' . $info['savepath'] . $info['savename'];
        $exts = $info['ext'];

        if (! $info) { 
            // 上传错误提示错误信息
            $this->error($upload->getError());
        } else { 
            // 上传成功
            $this->goods_import($filename, $exts);
        }
    }

    public function save_import($data)
    { 

        $i = 0;
        foreach ($data as $key => $val) {
            $_POST['device_code'] = $val['A'];
            $_POST['type_id'] = (string)$val['B'];
            $datas['addtime'] = time();
            $Devices = D('Devices'); 
            $res = D('Devices')->getCate();
            $info = $Devices->create();
 
            $code = $Devices->where("device_code='{$_POST['device_code']}'")->find();
            if(!empty($code)) $this->error( '已导入' . $i . '条数据<br>' . $_POST['device_code'] . '已存在');
            
            if($info){
                if(!in_array($_POST['type_id'], $res)){
                    $this->error('已导入' . $i . '条数据<br>' . $_POST['device_code'] . '设备类型不存在');
                }
                $res = $Devices->add();
                if (!$res) {
                    
                    $this->error('导入失败啦！');
                }else{
                    // echo "导入成功";
                    $bool = true;
                }
            } else {
                // $this->error('已导入' . $i . '条数据<br>');
                $this->error($Devices->getError());
            }   
            $i ++;
        }

        if($bool) $this->success('成功导入'.$i.'条数据',U('Admin/Devices/devicesList'));
        
    }

    private function getExcel($fileName, $headArr, $data)
    {
        vendor('PHPExcel');
        $date = date("Y_m_d", time());
        $fileName .= "_{$date}.xls";
        $objPHPExcel = new \PHPExcel();
        $objProps = $objPHPExcel->getProperties();
        // 设置表头
        $key = ord("A");
        foreach ($headArr as $v) {
            $colum = chr($key);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue($colum . '1', $v);
            $key += 1;
        }
        $column = 2;
        $objActSheet = $objPHPExcel->getActiveSheet();

        foreach ($data as $key => $rows) { 
            // 行写入
            $span = ord("A");
            foreach ($rows as $keyName => $value) { 
                // 列写入
                $j = chr($span);
                $objActSheet->setCellValue($j . $column, $value);
                $span ++;
            }
            $column ++;
        }
        
        $fileName = iconv("utf-8", "gb2312", $fileName);
        // 重命名表
        // 设置活动单指数到第一个表,所以Excel打开这是第一个表
        $objPHPExcel->setActiveSheetIndex(0);
        header('Content-Type: application/vnd.ms-excel');
        header("Content-Disposition: attachment;filename=\"$fileName\"");
        header('Cache-Control: max-age=0');
        
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output'); // 文件通过浏览器下载
        exit();
    }

    protected function goods_import($filename, $exts = 'xls')
    {
        // 导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
        vendor('PHPExcel');
        // 创建PHPExcel对象，注意，不能少了\
        $PHPExcel = new \PHPExcel();
        // 如果excel文件后缀名为.xls，导入这个类
        if ($exts == 'xls') {
            $PHPReader = new \PHPExcel_Reader_Excel5();
        } else 
            if ($exts == 'xlsx') {
                $PHPReader = new \PHPExcel_Reader_Excel2007();
            }
        
        // 载入文件
        $PHPExcel = $PHPReader->load($filename);
        // 获取表中的第一个工作表，如果要获取第二个，把0改为1，依次类推
        $currentSheet = $PHPExcel->getSheet(0);
        // 获取总列数
        $allColumn = $currentSheet->getHighestColumn();
        // 获取总行数
        $allRow = $currentSheet->getHighestRow();
        // 循环获取表中的数据，$currentRow表示当前行，从哪行开始读取数据，索引值从0开始
        for ($currentRow = 2; $currentRow <= $allRow; $currentRow ++) {
            // 从哪列开始，A表示第一列
            for ($currentColumn = 'A'; $currentColumn <= $allColumn; $currentColumn ++) {
                // 数据坐标
                $address = $currentColumn . $currentRow;
                // 读取到的数据，保存到数组$arr中
                $data[$currentRow][$currentColumn] = $currentSheet->getCell($address)->getValue();
            }
        }
        // dump($data);die;
        $this->save_import($data);
    }

    // 解除绑定
    public function remove()
    {
        $code = I('get.');
        if(empty($code)){
            $this->error('设备编码错误');
        }
        $res = M('devices')->where($code)->find();
        if($res['uid']) $this->error("已绑定了用户，不可解除绑定");
        if($res) $delBind = M('binding')->where('did='.$res['id'])->delete();
        if($res || $delBind){
            $data['binding_statu'] = 0;
            $data = M('devices')->where($code)->save($data);
        }
        if(!$data) $this->error('解绑失败');
        $this->success('解绑成功');

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