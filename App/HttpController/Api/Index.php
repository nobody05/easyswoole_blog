<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2018/12/28
 * Time: 15:49
 */

namespace App\HttpController\Api;

class Index extends BaseController
{
    public function index()
    {
        $this->response()->write("hello");
    }
}