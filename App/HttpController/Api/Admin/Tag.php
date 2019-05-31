<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/3/24
 * Time: 20:12
 */

namespace App\HttpController\Api\Admin;

use App\HttpController\Api\BaseController;
use App\Service\CategoryService;
use App\Service\TagService;
use App\Service\UtilService;

class Tag extends BaseController
{
    /**
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function list()
    {
        $pamas = $this->request()->getRequestParam();
        $page = UtilService::getInstance()->get_page($pamas);

        $all = UtilService::getInstance()->get_param($pamas, 'all');
        $tagService = new TagService();
        $count = $tagService->count();
        if ($all == true) {
            $page['pageSize'] = $count;
        }

        $list = $tagService->list($page['page'], $page['pageSize']);

        $this->writeJson(200, [
            'count' => $count,
            'page' => $page['page'],
            'pageSize' => $page['pageSize'],
            'list' => $list
        ], 'success');
    }

    /**
     * @return bool
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function add()
    {
        $tagName = $this->request()->getRequestParam('tagName');
        if (empty($tagName)) return $this->writeJson(200, [], '请填写tag名称');

        $tagService = new TagService();
        $result = $tagService->add($tagName);

        $this->writeJson(200, [], $result);
    }

    /**
     * @return bool|void|boolean
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function index()
    {
        $tagId = $this->request()->getRequestParam('tagId');

        if (empty($tagId)) return $this->writeJson(200, [], '请选择tag');
        $tagService = new TagService();
        $info = $tagService->getTag($tagId);

        $this->writeJson(200, $info, 'success');
    }

}