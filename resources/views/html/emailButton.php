<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/11/30
 * Time: 19:28
 */
$csrf_field = csrf_field();

echo <<<EOT
<div id="share_via_mail" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h5 class="modal-title">アルバム招待メール</h5>
            </div>

            <div class="modal-body text-center">
                <form class="form-inline"
                      role="form"
                      method="post"
                      action="/events/sendemail/$event->id">
                    <div class="form-group">
                        $csrf_field
                        <input type="email" name="email" class="form-control" placeholder="送り先メール">
                    </div>
                    <button type="submit" class="btn btn-primary">招待状を送る</button>
                </form>
            </div>

        </div>
    </div>
</div>
EOT;
