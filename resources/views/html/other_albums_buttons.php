<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/12/09
 * Time: 15:47
 */
$html =  "
<button class='btn btn-sm btn-default' style='margin: 1px;color: #0040FF;' onclick='getPhotos($e->id)'>
    <span style='font-size:xx-small;' class='text-muted'>{$e->or_time_ja_jp}</span>
    <span style='font-weight:bold;font-size:xx-small;'>{$e->event_name}</span>
</button>";

echo $html;