<?php
/**
 * Created by PhpStorm.
 * User: ZE
 * Date: 2015/11/30
 * Time: 19:57
 */


$view_by = <<<EOT
<div class='dropdown pull-left' aria-haspopup='true' style='margin: 4px'>

    <button class="btn btn-sm btn-default dropdown-toggle"
            type="button"
            style="color: blue;"
            id="view_by_dropdown"
            data-toggle="dropdown"
            aria-haspopup="true" aria-expanded="true">
            <span class='text-muted'>Group By : </span>
            {$eventGroupBy->description}
            <span class='caret'></span>
    </button>

    <ul class="dropdown-menu scrollable-menu"
        id="view_by_dropdown_list"
        aria-labelledby="view_by_dropdown">

        <li>
            <a href='javascript: ChangeGroupBy("All")'>
                All
            </a>
        </li>

        <li>
            <a href='javascript: ChangeGroupBy("ByUser")'>
                By User
            </a>
        </li>

        <li>


    </ul>
</div>
EOT;

//
//<a href='javascript: ChangeGroupBy("ByUploadedTime")'>
//    By Uploaded Time
//</a>
//        </li>
echo $view_by;