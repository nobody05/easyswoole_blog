<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/1/13
 * Time: 16:34
 */

namespace App\Service\Upload;

use EasySwoole\EasySwoole\Config;
use OSS\Core\OssException;
use OSS\OssClient;

class OssService
{
    /**
     * @param $localFile
     * @param $newFile
     * @return null
     */
    public function upload($localFile, $newFile)
    {
        try {
            $config = Config::getInstance()->getConf("OSS");
            $ossClient = new OssClient($config['accessKeyId'], $config['accessKeySecret'], $config['endpoint']);
            $result = $ossClient->uploadFile($config['bucket'], ltrim($newFile, '/'), $localFile);

            $fileInfo = parse_url($result['info']['url']);
            return $config['fileHost']. $fileInfo['path'];

        } catch (OssException $e) {
            return ['msg' => $e->getMessage()];
        }
    }
}
