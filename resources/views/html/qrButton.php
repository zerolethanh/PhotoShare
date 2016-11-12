<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/11/30
 * Time: 19:25
 */
echo <<<EOT
    <div id="qrCode" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h5 class="modal-title">QR CODE - FOR SHARE</h5>
                </div>

                <div class="modal-body">
                    <img src="/events/qr/$event->id" class="img img-responsive center-block"/>
                </div>
            </div>
        </div>
    </div>
EOT;
