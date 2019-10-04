<?php
//general
Route::get('test', 'Website\Home@test');
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
Route::post('admin/advisor/listing', 'Admin\Advisor@listing');
Route::post('admin/advisor/create', 'Admin\Advisor@create');
Route::post('admin/advisor/add', 'Admin\Advisor@add');
Route::post('admin/advisor/edit', 'Admin\Advisor@edit');
Route::post('admin/advisor/update', 'Admin\Advisor@update');
Route::post('admin/advisor/destroy', 'Admin\Advisor@destroy');
Route::post('admin/bank/listing', 'Admin\Bank@listing');
Route::post('admin/bank/add', 'Admin\Bank@add');
Route::post('admin/bank/edit', 'Admin\Bank@edit');
Route::post('admin/bank/update', 'Admin\Bank@update');
Route::post('admin/bank/destroy', 'Admin\Bank@destroy');
Route::post('admin/bank_loan/listing', 'Admin\BankLoan@listing');
Route::post('admin/bank_loan/create', 'Admin\BankLoan@create');
Route::post('admin/bank_loan/add', 'Admin\BankLoan@add');
Route::post('admin/bank_loan/edit', 'Admin\BankLoan@edit');
Route::post('admin/bank_loan/update', 'Admin\BankLoan@update');
Route::post('admin/bank_loan/destroy', 'Admin\BankLoan@destroy');
Route::post('admin/bank_rate/destroy', 'Admin\BankRate@destroy');
Route::post('admin/building_type/listing', 'Admin\BuildingType@listing');
Route::post('admin/building_type/add', 'Admin\BuildingType@add');
Route::post('admin/building_type/edit', 'Admin\BuildingType@edit');
Route::post('admin/building_type/update', 'Admin\BuildingType@update');
Route::post('admin/building_type/destroy', 'Admin\BuildingType@destroy');
Route::post('admin/client/listing', 'Admin\Client@listing');
Route::post('admin/client/create', 'Admin\Client@create');
Route::post('admin/client/add', 'Admin\Client@add');
Route::post('admin/client/edit', 'Admin\Client@edit');
Route::post('admin/client/update', 'Admin\Client@update');
Route::post('admin/client/destroy', 'Admin\Client@destroy');
Route::post('admin/dashboard/listing', 'Admin\Dashboard@listing');
Route::post('admin/package/listing', 'Admin\Package@listing');
Route::post('admin/package/add', 'Admin\Package@add');
Route::post('admin/package/edit', 'Admin\Package@edit');
Route::post('admin/package/update', 'Admin\Package@update');
Route::post('admin/package/destroy', 'Admin\Package@destroy');
Route::post('admin/rate/listing', 'Admin\Rate@listing');
Route::post('admin/rate/add', 'Admin\Rate@add');
Route::post('admin/rate/edit', 'Admin\Rate@edit');
Route::post('admin/rate/update', 'Admin\Rate@update');
Route::post('admin/rate/destroy', 'Admin\Rate@destroy');

//advisor
Route::get('advisor', 'Advisor\Home@index');
Route::post('advisor/initialize', 'Advisor\Home@initialize');
Route::post('advisor/login', 'Advisor\Home@login');
Route::post('advisor/logout', 'Advisor\Home@logout');
Route::post('advisor/client/listing', 'Advisor\Client@listing');
Route::post('advisor/client/create', 'Advisor\Client@create');
Route::post('advisor/client/add', 'Advisor\Client@add');
Route::post('advisor/client/edit', 'Advisor\Client@edit');
Route::post('advisor/client/update', 'Advisor\Client@update');
Route::post('advisor/client/destroy', 'Advisor\Client@destroy');
Route::post('advisor/dashboard/listing', 'Advisor\Dashboard@listing');
Route::post('advisor/profile/edit', 'Advisor\Profile@edit');
Route::post('advisor/profile/update', 'Advisor\Profile@update');
?>