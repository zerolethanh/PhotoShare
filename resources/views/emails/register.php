<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/10/28
 * Time: 16:40
 */
echo "
$user->name　様。
IE4A、２班チームのPhotoShareシステムにご登録いただいて誠にありがとうございます。<br>
登録完了メールをお送らせていただけます。<br>
登録情報：<br>
　ーーーーーーーーーーーーーーー
<table>
<tr><td align='right'>お名前：</td><td>　$user->name</td></tr>
<tr><td align='right'>メールアドレス：</td><td>　$user->email</td></tr>
<tr><td align='right'>パスワード：</td><td>　(登録パスワード）</td></tr>
</table>
　ーーーーーーーーーーーーーーー<br>
Hope you have fun<br>
Team: IE4A 2班<br>
Site: <a href='www.welapp.net'>www.welapp.net</a><br>
";