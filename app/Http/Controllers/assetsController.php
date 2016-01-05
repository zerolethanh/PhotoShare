<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;

class assetsController extends Controller
{
    protected $space = "assets";
    protected $resource = "js";//or css
    protected $ContentType_headers = [
        'js' => "text/javascript",
        'css' => "text/css"
    ];

    public function getCss()
    {
        return $this->getResources('css', func_get_args());
    }

    public function getJs()
    {
        return $this->getResources('js', func_get_args());
    }

    function getResources($resource, $func_args)
    {
        $this->resource = $resource;
        $paths = $this->removeRequestObject($func_args);
        if (!ends_with(end($paths), ".$this->resource")) {
            array_splice($paths, -1, 1, end($paths) . ".$this->resource");
        }
        $file_path = realpath(app_path("../resources/assets/$this->resource/") . implode('/', $paths));
        if ($file_path) {
            header($this->getContentTypeHeader());
            readfile($file_path);
            exit;
        }
        return 'no data';
    }

    public function getRealPath()
    {
        return realpath(app_path("../resources/$this->space/"));
    }

    public function removeRequestObject($args)
    {
        return array_values(array_filter($args, function ($val) {
            return is_string($val);
        }));
    }

    public function getAngular()
    {
        $asset_name = 'angular';
        $paths = array_filter(func_get_args());
        dd($paths);
        if (!ends_with(end($paths), '.js')) {
            array_splice($paths, -1, 1, end($paths) . '.js');
        }
        $file_path = realpath(app_path("../resources/assets/$asset_name") . implode('/', $paths));
        dd($file_path);
        if (file_exists($file_path)) {
            return file_get_contents($file_path);
        }
        return 'no data';
    }

    public function getContentTypeHeader()
    {
        if (isset($this->ContentType_headers[$this->resource])) {
            return "Content-Type:" . $this->ContentType_headers[$this->resource];
        }
        return 'text/html';
    }
}
