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
Route::post('admin/initialize', 'Admin\Home@initialize');
Route::post('admin/login', 'Admin\Home@login');
Route::post('admin/logout', 'Admin\Home@logout');
Route::get('admin/migration', 'Admin\Home@migration');
Route::get('admin/rollback', 'Admin\Home@rollback');
Route::post('admin/rate/list', 'Admin\Rate@list');
Route::post('admin/rate/add', 'Admin\Rate@add');
Route::post('admin/rate/edit', 'Admin\Rate@edit');
Route::post('admin/rate/update', 'Admin\Rate@update');
Route::post('admin/rate/destroy', 'Admin\Rate@destroy');
Route::post('admin/bank/list', 'Admin\Bank@list');
Route::post('admin/bank/add', 'Admin\Bank@add');
Route::post('admin/bank/edit', 'Admin\Bank@edit');
Route::post('admin/bank/update', 'Admin\Bank@update');
Route::post('admin/bank/destroy', 'Admin\Bank@destroy');
Route::post('admin/bank_loan/list', 'Admin\BankLoan@list');
Route::post('admin/bank_loan/create', 'Admin\BankLoan@create');
Route::post('admin/bank_loan/add', 'Admin\BankLoan@add');
Route::post('admin/bank_loan/edit', 'Admin\BankLoan@edit');
Route::post('admin/bank_loan/update', 'Admin\BankLoan@update');
Route::post('admin/bank_loan/destroy', 'Admin\BankLoan@destroy');
Route::post('admin/bank_rate/list', 'Admin\BankRate@list');
Route::post('admin/bank_rate/create', 'Admin\BankRate@create');
Route::post('admin/bank_rate/add', 'Admin\BankRate@add');
Route::post('admin/bank_rate/edit', 'Admin\BankRate@edit');
Route::post('admin/bank_rate/update', 'Admin\BankRate@update');
Route::post('admin/bank_rate/destroy', 'Admin\BankRate@destroy');
?>