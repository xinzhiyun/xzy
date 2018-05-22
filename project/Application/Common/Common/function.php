<?php

header("Content-type:text/html;charset=utf-8");


/**
 * 价格/100   用于页面显示
 * @param $num
 * @return string
 */
function html_price($num){
    return number_format(intval(trim($num), 10)/100,2);
}