<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/12/09
 * Time: 15:48
 */
if ($control) {
    $html = "<br><button class='btn btn-sm btn-primary glyphicon glyphicon-resize-small'
onclick='blueimp_gallery_controls_toggle()'>&nbsp;Small Photo Viewer</button>";
} else {
    $html = "<br><button class='btn btn-sm btn-primary glyphicon glyphicon-resize-full'
onclick='blueimp_gallery_controls_toggle()'>&nbsp;Large Photo Viewer</button>";
}

echo $html;