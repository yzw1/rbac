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
