<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/12/01
 * Time: 14:52
 */

$html = "<div class='list-group'>";


foreach ($events as $event) {
    $event_name_text = str_ireplace($search_text, "<span style='color:red'>$search_text</span>", $event->event_name);
    $html .= <<<EOT

  <a href="javascript:getPhotos('{$event->id}');$('#searchAlbum').modal('hide');" class="list-group-item">
    <table>
    <tr>
        <td>{$event->or_time_ja_jp}&nbsp;&nbsp;</td>
        <td>$event_name_text</td>
    </tr>
    </table>
  </a>

EOT;
}


$html .= "</div>";

echo $html;