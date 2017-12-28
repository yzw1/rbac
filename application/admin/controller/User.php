<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
class User extends  Admain
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //

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
//            var_dump($arr);


        return view('con1/user',[
            'title'=>'用户列表',
            'user' => $arr]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return view('con1/user_add',['title'=>'创建用户']);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //判断是否是post请求
        if (!Request::instance()->isPost()){
            $this->success('你不要拼路径','/');
        }
        $p = $request->post();
//        var_dump($p);
        $data = [
            'username' => $p['username'],
            'name' => $p['name'],
            'userpass' => md5($p['pwd'])
        ];
        if ($p['pwd'] = $p['repwd']){
                unset($p['repwd']);
        }
//        执行新增
        $result = Db::name('user')->data($data)->insert();
        if ($result > 0) {
            return $this->success('添加成功(((((((((((っ･ω･)っ Σ(σ｀･ω･´)σ 起飞！', url('admin/user/index'));
        } else {
            return $this->error('添加失败(o＞ω＜o)雅蠛蝶');
        }


    }

    /**
     * 显示指定的资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function read($id)
    {
        //
//        var_dump($id);
//        $user = Db::view('user',' id  username')
//            ->view('user_role','uid rid','user.id=user_role.uid')
//            ->view('role','id name','role.id = user_role.rid')
//            ->where('user.id',$id)
//            ->select();
//        var_dump($user[0]['username']);
//        查询出指定用户
        $user = Db::name('user')->field('id,username')->find($id);

//        var_dump($user);
//        查询出所有角色的名字
        $role = Db::name('role')->field(' id,name')->select();
//        var_dump($role);

//        查询指定用户已经有哪些权限
        $user_role = Db::name('user_role')->where(array('uid'=>array('eq',$id)))->select();
//        var_dump($user_role);
        $list = array();
        foreach ($user_role as $v){
            $list[] = $v['rid'];

        }
//        var_dump($list);
        return view('admin@/con1/user_fetch',[
            'title' => '用户分配权限',
            'user'=>$user,
            'role'=>$role,
            'list' => $list
        ]);
    }

    /**
     * 角色分配的修改
     * @param Request $request
     * @param $id
     */
    public function saveRole(Request $request,$id)
    {
//记得开启事物做
        $role = $request->post();
        if (empty($role['role'])){
            $this->error('不能为空');
        }
//        //清除用户所有的角色信息，避免重复添加
        $ur = Db::name('user_role')->where(array('uid'=>array('eq',$id)))->delete();

//        选中角色的ID

        foreach ($role['role'] as $v){
           $arr['rid'] = $v;
           $arr['uid'] = $id;
            $result = Db::name('user_role')->data($arr)->insert();
        }

        if ($result > 0){
            $this->success('修改成功','admin/user/index');
        }else{
            $this->error('修改失败');
        }
    }
    /**
     * 显示编辑资源表单页.
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
        $user = Db::name('user')->field('userpass',true)->find($id);
//        var_dump($user);
        return view('admin@con1/user_edit',[
            'title'=>'用户编辑',
            'user'=>$user
        ]);
    }

    /**
     * 保存更新的资源
     *
     * @param  \think\Request  $request
     * @param  int  $id
     * @return \think\Response
     */
    public function update(Request $request, $id)
    {
        //查看是否是put请求
        if (!Request::instance()->isPut()){
            $this->error('你好像迷路了老铁');
        }
        $p = $request->put();
//        var_dump($p);
        $data = [
            'username' => $p['username'],
            'name' => $p['name']
        ];
//        插入数据
        $result = Db::name('user')->where('id',$id)->update($data);
        if ($result > 0){
            $this->success('修改成功','admin/user/index');
        }else{
            $this->error('修改失败');
        }
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //删除语句
        $user = Db::name('user')->delete($id);
        if($user > 0){
            $info['status'] = true;
            $info['id'] = $id;
            $info['info'] = '删除成功';
            return json($info);
        }else{
            $info['status'] = false;
            $info['id'] = $id;
            $info['info'] = '删除失败';
            return json($info);
        }


    }
}
