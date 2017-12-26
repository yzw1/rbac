<?php

namespace app\admin\controller;

use think\Controller;
use think\Request;
use think\Db;
class User extends Controller
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
//            var_dump($user);


        return view('con1/func1',[
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
        return view('con1/func2',['title'=>'创建用户']);
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
            'userpass' => $p['pwd']
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
