<?php
/**
 * Introduce ：测试原生类
 * Created by Zyp丶.
 * Date: 2019/1/18
 */
Class TestCli
{
    const HHH = 'sadsad' ;
    public function test()
    {
        echo 11;
    }
}
$a = new \ReflectionClass('testcli');
var_dump($a->getConstant('HHH'));
