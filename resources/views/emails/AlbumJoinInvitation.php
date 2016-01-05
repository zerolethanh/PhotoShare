<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/11/09
 * Time: 19:10
 */
echo "
<br>
$user->name さんからアルバムに参加の招待があります。<br>
下に「参加する」とアルバムに参加者たちとコミュニティしましょう。<br>
<br>
<table>
<tr><td>イベント名：</td><td>$event->event_name</td></tr>
<tr><td>作成者：</td><td>$user->name</td></tr>
<tr><td>作成日時：</td><td>$event->or_time</td></tr>
<tr><td></td><td>

<a href=\"$path\"
style=\"font-size:16px; font-weight: bold;
font-family: Helvetica, Arial, sans-serif;
text-decoration: none; line-height:40px; width:100%; display:inline-block\">
<span style=\"color: #FF585A\">参加する</span>
</a>

</td></tr>
</table>
";
