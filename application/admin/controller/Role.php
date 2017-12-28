<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Response;
class Role extends  Admain
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //
        $role = Db::name('role')->order('id','asc')->select();
//            声明一个空数组
        $arr = array();
        foreach ($role as $v){
            $nrid = Db::name('role_node')->field('nid')->where(array('rid'=>array('eq',$v['id'])))->select();
//                var_dump($urid);
            $nid = array();
            foreach ($nrid as $vo){
                $nid[] = Db::name('node')->where(array('id'=>array('eq',$vo['nid']),'status'=>array('eq',1)))->value('name');
//                    var_dump($rid);
            }
            $v['role']= $nid;
            $arr[] = $v;
//                var_dump($v);
        }

        return view('admin@con1/role',[
            'title' => '角色列表',
            'role' => $arr
        ]);
    }

    /**
     * 显示创建资源表单页.
     *
     * @return \think\Response
     */
    public function create()
    {
        //
        return view('admin@con1/role_add',[
            'title' => '角色添加'

        ]);
    }

    /**
     * 保存新建的资源
     *
     * @param  \think\Request  $request
     * @return \think\Response
     */
    public function save(Request $request)
    {
        //查看是不是post请求
        if(!Request::instance()->isPost()){
            $this->error('你好像迷路了');
        }
//        获取数据
        $p = $request->post();
        $data =[
            'name' => $p['name'],
            'remark'=>$p['remark'],
            'status'=>$p['status']
        ];
//        插入数据
        $nr = Db::name('role')->data($data)->insert();
        if ($nr>0){
            $this->success('添加唱功','admin/role/index');
        }else{
            $this->error('添加失败');
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
        //查找所带过来的信息
//        查看指定的角色
        $role = Db::name('role')->field('id,name')->find($id);
//        var_dump($role);
//        查看所有节点
        $node = Db::name('node')->field('id,name')->select();
//        var_dump($node);
//        查看指定角色带有的节点
        $role_node = Db::name('role_node')->where(array('rid' => array('eq',$id)))->select();
//        var_dump($role_node);
        $rn = array();
        foreach ($role_node as $v){
                $rn[] = $v['nid'];

        }

        return view('admin@con1/role_fetch',[
            'title' => '角色分配',
            'role' => $role,
            'node' => $node,
            'rn' => $rn
        ]);
    }
    public function saveRole(Request $request,$id)
    {
          $node = $request->post();
        if (empty($node['node'])){
            $this->error('不能为空');
        }
//        删除重复选择的节点
        $rn = Db::name('role_node')->where(array('rid' =>array('eq',$id)))->delete();
//        var_dump($rn);
//        根据选择的节点插入表中

//            var_dump($node['node']);die;
            foreach ($node['node'] as $v){
//            循环插入值（node有两个值）
                $data['nid'] = $v;
                $data['rid'] = $id;

            $result = Db::name('role_node')->data($data)->insert();
            }
        if ($result > 0 ){
            $this->success('分配成功','admin/role/index');
        }else{
            $this->error('分配失败');
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
        //点击修改带回来的数据
        $role = Db::name('role')->find($id);
//        var_dump($role);
        return view('admin@con1/role_edit',[
            'title' => '角色编辑',
            'role' => $role
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
        //验证是否是PUT请求
        if (!Request::instance()->isPut()){
            $this->error('你好像没迷路了');
        }
        $p = $request->put();
//        var_dump($p);
        $data = [
            'name' => $p['name'],
            'remark' => $p['remark'],
            'status' => $p['status']
        ];
//        更新数据
        $role = Db::name('role')->where('id',$id)->update($data);
//        插入成功会大于0
        if ($role>0){
            $this->success('更新成功','admin/role/index');
        }else{
            $this->error('更新失败');
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
//        删除语句
        $role = Db::name('role')->delete($id);
        if ($role > 0){
            $info['status'] = true;
            $info['id'] = $id;
            $info['info'] = '删除成功';
        }else{
            $info['status'] = false;
            $info['id'] = $id;
            $info['info'] = '删除失败';
        }
        //先给个假数据

        return json($info);

    }
}
