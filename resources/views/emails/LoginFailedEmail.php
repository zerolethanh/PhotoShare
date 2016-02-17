<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2016/01/25
 * Time: 0:54
 */
echo
    "You are login failed at " . date('Y-m-d H:i:s') .
//    "<br>IPAddress: " . implode(',', request()->getClientIps()) .
    "<br>This email is sent automatically,so please do not reply this email.
    <br>
    If you forgot your password, please reset via link below:
    <br>
    https://photoshare.space/password/email
    <br>
    <br>
    PhotoShare Team.
";