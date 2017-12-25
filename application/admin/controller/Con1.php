<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;

class Con1 extends Controller
{
    public function index()
    {
        return view('admin@con1/tables');
    }

    public function func1()
    {

            $uid = Db::name('user')->field('userpass',true)->order('id','asc')->select();
//            声明一个空数组
            $arr = array();
            foreach ($uid as $v){
                $urid = Db::name('user_role')->field('rid')->where(array('uid'=>array('eq',$v['id'])))->select();
//                var_dump($urid);
                $rid = array();
                foreach ($urid as $vo){
                    $rid[] = Db::name('role')->where(array('id'=>array('eq',$vo['rid']),'status'=>array('eq',1)))->value('name');
//                    var_dump($rid);
                }
                $v['role']= $rid;
                $arr[] = $v;
//                var_dump($v);
            }
//            var_dump($user);


        return view('con1/func1',[
            'title'=>'用户列表',
            'user' => $arr]);
    }

    public function func2()
    {

        return view('admin@con1/func2',[
            'title' => '用户添加'
        ]);
    }

}