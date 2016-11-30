<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

/*
 *
 * Parameter	Mearning	Example
w	Maximum width	/slir/w100/path/to/image.jpg
h	Maximum height	/slir/h100/path/to/image.jpg
c	Crop ratio	/slir/c1x1/path/to/image.jpg
q	Quality	/slir/q60/path/to/image.jpg
b	Background fill color	/slir/bf00/path/to/image.png
p	Progressive	/slir/p1/path/to/image.jpg
g	Grayscale	/slir/g1/path/to/image.jpg
 */

class SlirController extends Controller
{
    //
    public function slirAction($size, $user_id, $album_id, $file_path)
    {
        define('SLIR_CONFIG_CLASSNAME', \App\SLIR\SLIRConfig::class);

        $slir = new \SLIR\SLIR();
        $slir->processRequestFromURL();

        // SLIR handle response by itself
        // Do not return anything
    }
}
