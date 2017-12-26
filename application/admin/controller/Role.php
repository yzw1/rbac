<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;
use think\Response;
class Role extends Controller
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
        //
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
        //
    }

    /**
     * 删除指定资源
     *
     * @param  int  $id
     * @return \think\Response
     */
    public function delete($id)
    {
        //
    }
}
