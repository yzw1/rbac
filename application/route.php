<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

use think\Route;
//进入后台
Route::get('admin','admin/index/index');
//Route::get('login','admin/main/logindo');
//瞎拼路由
//Route::get('/:name','admin/main/index');
//后台主页的路由
Route::get('main','admin/main/index');
//Route::resource('index','admin/main/index');
//用户保存修改的路由
Route::post('user/saveRole/:id','admin/user/saveRole');
//角色分配的路由
Route::post('role/saveRole/:id','admin/role/saveRole');

//Route::get('user/list','admin/user/index');
//Route::get('user/edit','admin/user/edit');

//Route::get('/', 'index/Index/index');
//用户的资源路由
Route::resource('user', 'admin/User');
//角色的资源路由
Route::resource('role','admin/Role');
//节点的资源路由
Route::resource('node','admin/Node');

//Route::get('users/read/:id', 'rest/User/readpage');


return [

];
