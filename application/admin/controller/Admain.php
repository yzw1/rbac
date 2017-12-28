<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;

class Admain extends Controller
{

    public function _initialize()
    {

        if (empty(Session::get('admin_user'))){
            $this->redirect('admin/index/index');
        }
//        全局页面的用户显示

        $t = Session::get('admin_user');
        $this->assign('t',$t);
//        权限过滤
//        获得方法名，控制器名，模块名
        $request = Request::instance();
        $controller = $request->controller();
        $method = $request->action();
        $module = $request->module();
////        拼接一个名字是判断用户有哪些操作
//        $way = lcfirst($controller);
//        $total = $way.'/'.$method;
//        var_dump($total);die;
//        列出权限列表


//        判断admin用户有所有权限
        if(Session::get('admin_user') != 'admin'){
            $this->error('你没有权限');
        }


    }

}
