<?php
namespace Admin\Model;

use Think\Model;
/**
 * Class 设备连接日志数据处理
 * @package Admin\Model
 * @author 潘宏钢 <619328391@qq.com>
 */
class LinklogModel extends Model
{   
    // 获取本周开始时间
    public function getWeekStart()
    {
        $weekstart = date('Y-m-d H:i:s',mktime(0, 0 , 0,date('m'),date('d')-date('w')+1,date('Y')));
        return strtotime($weekstart);
    }

    // 获取本周结束时间
    public function getWeekEnd()
    {
        $weekend = date('Y-m-d H:i:s',mktime(23,59,59,date('m'),date('d')-date('w')+7,date('Y')));
        return strtotime($weekend);
    }

    // 获取本周内所有连接过的设备
    public function getWeekLinkDeviceAll()
    {
        // 本周开始时间
        $weekstart = $this->getWeekStart();
        // 本周结束时间
        $weekend = $this->getWeekEnd();
        //准备条件
        $map['create_time'] = array(array('egt',$weekstart),array('elt',$weekend)) ;

        $auid = $_SESSION['adminuser']['id'];
        if ($auid == 1) {
            $info = $this->where($map)->select();
            if ($info) {
                return $info;
            }else{
                return false;
            }    
        }else{
            $map['auid'] = $auid;
            $info = $this->where($map)->select();
            if ($info) {
                return $info;
            }else{
                return false;
            } 
        }
    }

    // 获取本周每一天有多少台设备连接
    public function getLinkByEveryDay($data=[])
    {
        if (count($data) == 0) {
            $data = $this->getWeekLinkDeviceAll();
        }
        // 本周开始时间
        $weekstart = $this->getWeekStart();
        // 本周结束时间
        $weekend = $this->getWeekEnd();
        $result = [];
        for ($i=0; $i < 7; $i++) { 
          foreach ($data as $key => $value) {
            if ($value['create_time'] >= $weekstart && $value['create_time'] <= $weekstart+24*60*60) {
              $result["$i"+1]['count'] += 1;
            }else{
              if (!array_key_exists($i+1,$result)) {
                $result["$i"+1] = null;
              }
            }
          }
          $weekstart = $weekstart+24*60*60;
        }
        return $result;
    }

    /**
     * 获取某时间段内的数据量
     * fieldname string,
       starttime int(0-24),
       endtime int(0-24)
     * @author 潘宏钢 <619328391@qq.com>
     */
    public function getTimeSlotData($fieldname,$starttime,$endtime)
    {
        // SELECT * from pub_linklog WHERE((create_time/3600+8)%24 >8)AND((create_time/3600+8)%24 <11);

        $map['_string'] = "($fieldname/3600+8)%24 >$starttime AND ($fieldname/3600+8)%24 < $endtime";

        $auid = $_SESSION['adminuser']['id'];
        
        if ($auid == 1) {
            $info = $this->where($map)->count();
            return $info;
        }else{
            $map['auid'] = $auid;
            $info = $this->where($map)->count();
            return $info;
        }

    }
}