<?php
//general
Route::get('test', 'Website\Test@index');
Route::post('register', 'Website\Home@register');

//website
Route::get('', 'Website\Home@index');
Route::post('website/register', 'Website\Home@register');
Route::post('website/chat/initialize', 'Website\Chat@initialize')->middleware('website');
Route::post('website/chat/message', 'Website\Chat@message')->middleware('website');

//admin
Route::get('admin', 'Admin\Home@index');
Route::post('admin/initialize', 'Admin\Home@initialize')->middleware('admin');
Route::post('admin/login', 'Admin\Home@login');
Route::post('admin/logout', 'Admin\Home@logout');
Route::post('admin/chat/index', 'Admin\Chat@index');
?>