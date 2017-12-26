<?php

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Request;

class Node extends Controller
{
    /**
     * 显示资源列表
     *
     * @return \think\Response
     */
    public function index()
    {
        //查询有多少节点
        $node = Db::name('node')->select();
//        var_dump($node);
        return view('con1/node',[
            'title' => '节点列表',
            'nodes' => $node
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
        return view('admin@con1/node_add',[
            'title' => '添加节点'
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
        //查看是不是POST请求
        if (!Request::instance()->isPost()){
//            $this->success('迷路了，回家找妈妈去吧','admin/node/create');
            $this->error('迷路了回家找妈妈去吧');
        }
//        $p = input('post.');
        $name = input('post.name');
        $mname = input('post.mname');
        $aname = input('post.aname');
        $status = input('post.status');
        $data = [
            'name' => $name,
            'mname' => $mname,
            'aname' => $aname,
            'status' => $status
        ];
//        var_dump($data);
//        执行新增
        $role = Db::name('node')->data($data)->insert();
//        var_dump($role);
        if ($role>0){
            $this->success('添加成功','admin/node/index');
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
        $node = Db::name('node')->field('id ,name, mname, aname, status')->find($id);
//        var_dump($node);die;
        return view('admin@con1/node_edit',[
            'title'=>'编辑节点',
            'node'=>$node
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
        //查看是不是put请求
        if (!Request::instance()->isPut()){
            $this->error('你好像迷路了');

        }
//        获取到相应数据
        $p = $request->put();
        $data = [
            'name'=>$p['name'],
            'mname'=>$p['mname'],
            'aname'=>$p['aname'],
            'status'=>$p['status']
        ];
        $node = Db::name('node')->where('id',$id)->update($data);
        if ($node){
            $this->success('编辑成功','admin/node/index');
        }else{
            $this->error('编辑失败啦');
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
        $node = Db::name('node')->delete($id);
//        var_dump($node);
        if ($node > 0){
            $info['status'] = true;
            $info['id'] = $id;
        }else{
            $info['status'] = false;
            $info['id'] = $id;
        }
//      返回给data的数据
        return json($info);
    }
}
