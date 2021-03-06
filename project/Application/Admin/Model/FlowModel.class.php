<?php
namespace Admin\Model;

use Think\Model;
use Org\Util\Date;
/**
 * Class 充值数据处理
 * @package Admin\Model
 * @author 潘宏钢 <619328391@qq.com>
 */
class FlowModel extends Model
{   

    // 处理查询数据
    public function getAll()
    {
       $data = $this->select();
       return $data;
    }

    // 获取当月充值数据
    public function getCurrentMonth()
    {
        $date = new Date();
        $firstDayOfMonth = $date->firstDayOfMonth();
        $firstat = strtotime($firstDayOfMonth);
        $lastDayOfMonth = $date->lastDayOfMonth();
        $lastat = strtotime($lastDayOfMonth) + 24*60*60;

        $map['addtime'] = array(array('gt',$firstat),array('lt',$lastat), 'and');
        // $map['_query'] = "status=1";
        $auid = $_SESSION['adminuser']['id'];

        if($auid==1){
            $data = $this->where($map)->select();
        }else{
            $map=[
                'f.addtime' => array(array('gt',$firstat),array('lt',$lastat), 'and'),
                'd.auid' =>$_SESSION['adminuser']['id'],
            ];
            $data = $this
                ->where($map)
                ->alias('f')
                ->join('__DEVICES__ d on f.device_code = d.device_code','LEFT')
                ->select();
        }

        return $data;
    }

    // 当月每一天的数据条数
    public function getTotalByEveryDay($data=[])
    {
        if (count($data) == 0) {
            $data = $this->getCurrentMonth();
        }
        $date = new Date();
        $maxDayOfMonth = $date->maxDayOfMonth();
        $firstDayOfMonth = $date->firstDayOfMonth();
        $startat = strtotime($firstDayOfMonth);
        $result = [];

        for ($i=0; $i < $maxDayOfMonth; $i++) { 
          foreach ($data as $key => $value) {
            // dump($value);die;
            if ($value['addtime'] >= $startat && $value['addtime'] <= $startat+24*60*60) {
              $result["$i"+1]['count'] += 1;
              $result["$i"+1]['money'] += $value['money'];
            }else{
              if (!array_key_exists($i+1,$result)) {
                $result["$i"+1] = null;
              }
            }
          }
          $startat = $startat+24*60*60;
        }
        return $result;
    }

    // 统计历史订单总金额
    public function getOrderMoneyAll()
    {
        $info = $this->sum('money');
        if ($info) {
            return $info;
        }else{
            return false;
        }
    }

    // 统计历史订单总数量
    public function getOrderCount()
    {
        // 客户id
        $auid = $_SESSION['adminuser']['id'];
        if ($auid == 1) {
            $info = $this->count();
        }else{
            $map['d.auid'] = $auid;
            $info = $this->where($map)
                              ->alias('f')
                              ->join("__DEVICES__ d ON f.device_code=d.device_code",'LEFT')
                              ->field("f.*")
                              ->count();
        }
        return $info;
    }


}