<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Session;
use think\Db;

class Main extends Controller
{

    /**
     * 后台登录处理
     * @return \think\response\View
     */
    public function logindo(Request $request)
    {
//        获取POST数据
        if(!Request::instance()->isPost()){
            $this->error('你是不是迷路了老弟');
        }
        $p = $request->post();
        $data = [
            'username' => $p['username'],
            'userpass' => md5($p['userpass']),
            'code' => $p['code']
        ];
//        查看是否匹配
        $user = Db::name('user')->where(array('username' => array('eq',$data['username'])))->find();
        if (!$user){
            $this->error('查找用户不存在');
        }
        if ($user['userpass'] != $data['userpass']){
            $this->error('密码错误');
        }
//        验证码
        if(!captcha_check($data['code'])){
            $this->error('验证码不对');
        };
        Session::set('admin_user',$p['username']);
//        user表中的所有数据
         Session::set('admin',$user);
        $user = Session::get('admin');
//        var_dump($user['id']);die;
//        获取指定用户有的权限节点
//        1.查看user_role下的uid对应的rid
//        2.查看rid在role中对应的id status
//        3.查看对应的role_node中的rid->role.id
//        4.对应的role_node中的rid对应的nid
//        5.查看nid对应的node中的mname aname status
//        var_dump($user['id']);die;
//        $nodelist = Db::view('user','id='.$user['id'])
//                        ->view('user_role','uid,rid','user.id = user_role.uid')
//                        ->view('role','id status','user_role.rid=role.id')
//                        ->view('role_node','nid,rid','role_node.rid=role.id')
//                        ->view('node',' id mname,aname,status','node.id=role_node.nid')
//                        ->where('status',1)
//                        ->select();
        $user_role = Db::name('user_role')->field('rid')->where(array('uid'=>array('eq',$user['id'])))->select();
//        var_dump($user_role);
        foreach ($user_role as $k=> $v){
             $role = Db::name('role')->field('id')->where(array('id'=>array('eq',$v['rid'])))->select();

        }
//        var_dump($role);die;
        foreach ($role as $v){
            $role_node = Db::name('role_node')->field('nid')->where(array('rid'=>array('eq',$v['id'])))->select();
        }
        $node = array();
        foreach ($role_node as $v){
            $node[] = Db::name('node')->field('mname,aname')->where(array('id'=>array('eq',$v['nid'])))->select();
        }
//        var_dump($role_node);
//        var_dump($node);
        //控制器名转换为大写
        foreach ($node as $k => $vo){
            //控制器名转换为大写
            foreach ($vo as $key => $val) {
                $vo[$key]['mname'] = ucfirst($val['mname']);
            }
//            var_dump($vo);
//            拼接出控制器的所有方法
            foreach ( $vo as $v){
                $nodelist[$v['mname']][] = $v['aname'];
            }
    }
//    每个控制器有什么方法
//        var_dump($nodelist);
        Session::set('nodelist',$nodelist);

        return $this->redirect('admin/Main/index');
      }



    /**
     * 后台登出处理
     * @return \think\response\View
     */
    public function logout()
    {
        Session::delete('username');
        return $this->redirect('admin/index/index');
    }
    /**
     * 后台主页
     * @return \think\response\View
     */
    public function index()
    {

        if (empty(Session::get('admin_user'))){
            return redirect('admin/index/index');
        }else{
         $t = Session::get('admin_user');
//         var_dump($t);die;
            return view('admin@main/index',[
                't'=>$t
            ]);

        }

    }


}
