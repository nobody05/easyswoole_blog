<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/3/24
 * Time: 20:50
 */

namespace App\HttpController\Api\Admin;

use App\HttpController\Api\BaseController;
use App\Service\BlogService;

class WebConfig extends BaseController
{
    /**
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function index()
    {
        $blogService = new BlogService();
        $info = $blogService->getBlogConfig();

        $this->writeJson(200, $info, 'success');

    }

    /**
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function modify()
    {
        $params = $this->request()->getRequestParam();
        $blogService = new BlogService();
        $result = $blogService->modify($params);

        $this->writeJson(200, [], $result);

    }

    /**
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getAbout()
    {
        $blogService = new BlogService();
        $info = $blogService->getAboutMe('about');

        $this->writeJson(200, $info, "success");
    }

    /**
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function modifyAbout()
    {
        $params = $this->request()->getRequestParam();
        $data = [
            'type' => 'about',
            'md' => $params['aboutMeContent'],
            'html' => $params['htmlContent']
        ];
        $blogService = new BlogService();
        $result = $blogService->modifyAboutMe($data);

        $this->writeJson(200, [], $result);
    }


    /**
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function getResume()
    {
        $blogService = new BlogService();
        $info = $blogService->getAboutMe('resume');

        $this->writeJson(200, $info, "success");
    }

    /**
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function modifyResume()
    {
        $params = $this->request()->getRequestParam();
        $data = [
            'type' => 'resume',
            'md' => $params['resumeContent'],
            'html' => $params['htmlContent']
        ];
        $blogService = new BlogService();
        $result = $blogService->modifyAboutMe($data);

        $this->writeJson(200, [], $result);
    }

}