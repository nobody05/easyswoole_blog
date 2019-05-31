<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/1/13
 * Time: 21:02
 */

namespace App\HttpController\Api;

use App\Service\Upload\OssService;
use EasySwoole\Http\Message\UploadFile;

class Common extends BaseController
{
    public function upload()
    {
        if ('POST' != $this->request()->getMethod()) {
            return $this->writeJson(200, []);
        }
        /**@var UploadFile $file*/
        $file = $this->request()->getUploadedFile('file');

        $ossService = new OssService();
        $fileUrl = $ossService->upload($file->getTempName(), 'images/article/'. $file->getClientFilename());

        if (is_array($fileUrl)) {
            return $this->writeJson(999, [], $fileUrl['msg']);
        }

        return $this->writeJson(200, [
            'imgUrl' => $fileUrl,
            'imgPath' => $file->getClientFilename()
        ], 'ok');
    }
}