<?php
namespace Admin\Model;

use Think\Model;

/**
 * Class 产品类型数据处理
 * @package Admin\Model
 * @author 潘宏钢 <619328391@qq.com>
 */
class ProductModel extends Model
{   

    // 自动验证
    protected $_validate = array(
       array('typename','require','型号名称不能为空'),
       array('product_id','require','微信product_id不能为空'),
//        array('typename','/^[a-zA-Z0-9\x{4e00}-\x{9fa5}]{1,660}$/u','类型名称不能使用特殊字符',1,'regex'),
        array('typename','','该型号名称已存在，请换一个试试，如净水器A型',0,'unique',1)
        
        // array('url','/@(?i)\b((?:[a-z][\w-]+:(?:/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))@','网址格式不对',1,'regex')
    );


    // 自动完成
    protected $_auto = array ( 
        array('time','time',1,'function'), // 对addtime字段在新增的时候写入当前时间戳 

    );



}