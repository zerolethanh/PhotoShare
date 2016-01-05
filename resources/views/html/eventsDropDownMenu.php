<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/11/30
 * Time: 19:57
 */
$html = '';

$addPhotosButton = view("html.downloadAll_And_PlusPhotosButton", ['event' => $event]);

$html .= <<<EOT


    <button class="btn btn-sm btn-default dropdown-toggle"
            type="button"
            style="color: blue;"
            id="eventsDropDownMenu"
            data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="true">
    </button>

<ul class="dropdown-menu scrollable-menu"
    id="eventsDropDownMenuList"
    aria-labelledby="eventsDropDownMenu">
EOT;

foreach ($events as $e) {//dropdown list

    $isShowingEvent = $e->id == $event->id;

    $divStyle = $isShowingEvent ?
        "margin: 5px;background-color:yellow;"
        : "margin: 5px";

    $html .= "
            <li>
                <div class='row' style='$divStyle'>
                <a href='javascript: getPhotos($e->id)' class='pull-left'>
                    <span class='text-muted' style='font-size: xx-small;'>{$e->or_time_ja_jp}</span>
                    <span style='font-weight: bolder;'> {$e->event_name}</span>
                </a>
                </div>
            </li>
            ";

}
if (count($events) > $events_max_take) {
    $html .= "<li><a href='/events/more'>More...</a></li>";//last dropdown list
}
$html .= "</ul>";
$html .= $addPhotosButton;

//$html .= view('html.group_by')->render();
//dd($html);
echo $html;