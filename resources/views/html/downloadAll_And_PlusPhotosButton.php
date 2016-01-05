<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/11/30
 * Time: 19:48
 */
//<button class="btn btn-info" style="margin-left:5px"
//        type="button" onclick="location.href='/photos/download-all/{$event->id}'">
//    <span class="glyphicon glyphicon-download-alt" aria-hidden = "true" ></span>
//</button>
echo <<<EOT
<button class='btn btn-success btn-sm'
        type='button'
        style='margin-top:3px'
        onclick="location.href='/photos/upload/{$event->id}'">
   <span class='glyphicon glyphicon-plus'></span> PHOTOS
</button>
EOT;
