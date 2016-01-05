<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class angularController extends Controller
{
    protected $asset_name = 'angular';
    protected $url_space = 'angular';
    protected $sub_domain = 'js';// name space : (eg. js, css)
    protected $ContentType_headers = [
        'js' => "text/javascript",
        'css' => "text/css"
    ];

    /* name space : js, css
     * router: /lib/angular/{file path}
     * @result: file contents
     */

    public function index(Request $request)
    {
        $this->sub_domain = $request->sub;
        $realPath = $this->getRealPaths(func_get_args());
//        dd($realPath);
        if (file_exists($realPath)) {
            header($this->getContentTypeHeader());
            readfile($realPath);
            exit;
        }
        return 'file not exists';
    }

    public function getRealPaths($paths)
    {
        $paths = $this->realAssetsPaths(array_values(array_filter($paths, function ($val) {
            return is_string($val);
        })));
        return $this->appendPaths($this->baseAssetsPath(), implode('/', $paths));
    }

    public function realAssetsPaths($paths, $toArray = true)
    {
        $paths = collect($paths);
        $paths->shift();
        $asset_name_idx = $paths->first(function ($key, $val) {
            return $val == $this->asset_name;
        });

        $paths->forget($asset_name_idx);

        $paths = $this->addLastFilename($paths);

        if ($toArray) {
            return $paths->toArray();
        }

        return $paths;

    }

    public function baseAssetsPath()
    {
        return realpath(app_path("../resources/assets/$this->sub_domain/$this->asset_name/"));
    }

    public function appendPaths($paths = null)
    {
        $paths = is_array($paths) ? $paths : func_get_args();
        return implode('/', $paths);
    }

    public function addLastFilename(&$paths)
    {
        if ($paths instanceof Collection) $paths = $paths->toArray();
        if (!ends_with(end($paths), ".$this->sub_domain")) {
            array_splice($paths, -1, 1, end($paths) . ".$this->sub_domain");
        };
        return collect($paths);
    }

    public function getHome()
    {
        return view('angular.home');
    }

    public function getView(Request $request)
    {
        $viewPath = $this->real_func_args(func_get_args());
        array_unshift($viewPath, $this->url_space);
        $viewPath = implode('.', $viewPath);
//        dd($viewPath);
        if (View::exists($viewPath)) {
            return view($viewPath);
        }
        return 'no exists view';
    }

    public function real_func_args($args)
    {
        return array_values(array_filter($args, function ($val) {
            return is_string($val);
        }));
    }

    public function getContentTypeHeader()
    {
        if (isset($this->ContentType_headers[$this->sub_domain])) {
            return "Content-Type:" . $this->ContentType_headers[$this->sub_domain];
        }
        return 'text/html';
    }
}
