<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/12/09
 * Time: 15:48
 */
if ($control) {
    $html = "<br><button class='btn btn-warning glyphicon glyphicon-resize-small'
onclick='blueimp_gallery_controls_toggle()'>&nbsp;Small-PhotoViewer</button>";
} else {
    $html = "<br><button class='btn btn-warning glyphicon glyphicon-resize-full'
onclick='blueimp_gallery_controls_toggle()'>&nbsp;Full-PhotoViewer</button>";
}

echo $html;