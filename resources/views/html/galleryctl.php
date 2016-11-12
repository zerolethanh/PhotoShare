<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/11/30
 * Time: 19:19
 */
$control = request()->user()->blueimp_gallery_controls ?
    ' blueimp-gallery-controls' : '';
echo
<<<EOT
<div id="blueimp-gallery" class="blueimp-gallery $control"  data-hide-page-scrollbars="false">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>

    <div class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header" style="height:25px;padding:0">
                     <p name="photo_title" style="padding-top:5px"></p>
                </div>

                <div class="modal-body next"></div>

                <div class="modal-footer">
                    <div name="photo_description" style="margin-right: 10px"></div>
                    <div>
                        <button class="btn btn-sm btn-default glyphicon glyphicon-download-alt pull-right"
                            name="photo_download_button"
                            onClick="downloadPhoto()">
                        </button>
                        <button class="btn btn-sm btn-danger glyphicon glyphicon-trash pull-left"
                            name="photo_delete_button"
                            onClick="deletePhoto()">
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
EOT;
