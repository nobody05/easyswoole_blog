<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2018/12/28
 * Time: 18:25
 */

namespace App\HttpController\Api;

use EasySwoole\Http\AbstractInterface\Controller;

class BaseController extends Controller
{
    public function index()
    {
        // TODO: Implement index() method.
    }

    protected function writeJson($statusCode = 200,$result = null,$msg = null)
    {
        if(!$this->response()->isEndResponse()){
            $data = Array(
                "code"=>$statusCode,
                "data"=>$result,
                "msg"=>$msg
            );
            $this->response()->write(json_encode($data,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES));
            $this->response()->withHeader('Content-type','application/json;charset=utf-8');
            $this->response()->withStatus($statusCode);
            return true;
        }else{
            return false;
        }
    }

}