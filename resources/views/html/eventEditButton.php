<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/11/30
 * Time: 19:30
 */
$csrf_field = csrf_field();
$tags = implode('',array_map(function ($val) {
    return "#" . $val;
}, $event->tags()->lists('tag')->toArray()));
echo <<<EOT
<div id="event_edit_modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5 class="modal-title">イベント編集</h5>
                </div>

                <div class="modal-body">
                     <form class="form"
                            id="event_edit_form"
                              role="form"
                              method="post"
                              action="/events/edit/$event->id">
                            <div class="form-group">
                                $csrf_field

                                <label for='event_name'>Album Name:</label>
                                <input type="text" name="event_name" class="form-control" placeholder="イベント名"
                                        value='$event->event_name'>

                                <label for='or_time'>Time:</label>
                                <input type='date' name='or_time' class='form-control'
                                        value='$event->or_time'>

                                        <br>
                                <label for='tags'>Tags: #から始まる、複数タグ付け可能 (例: #バーベキュー#Japan_tour #funnyCafe)</label>
                                <input type='text' name='tags' value='$tags' class='form-control'>
                            </div>

                            <br>
                            <button type="submit" class="btn btn-primary">変更する</button>
                     </form>
                     <hr>
                     <button class='btn btn-sm btn-danger pull-right' onClick="event_delete($event->id)">このイベントを削除する</button>
                     <br>

                </div>
            </div>
        </div>
    </div>
EOT;
