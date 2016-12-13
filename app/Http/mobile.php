<?php
/**
 * User: ZE
 * Date: 2016/11/30
 * Time: 2:37
 */
Route::get('token',function (){
    return request()->session()->token();
});
