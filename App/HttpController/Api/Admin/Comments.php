<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/3/24
 * Time: 20:37
 */

namespace App\HttpController\Api\Admin;

use App\HttpController\Api\BaseController;
use App\Service\CommentsService;
use App\Service\UtilService;

class Comments extends BaseController
{
    /**
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\JoinFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function alllist()
    {
        $page = UtilService::getInstance()->get_page($this->request()->getRequestParam());

        $commentsService = new CommentsService();
        $list = $commentsService->allList($page['page'], $page['pageSize']);

        $this->writeJson(200, [
            'list' => $list,
            'page' => $page['page'],
            'pageSize' => $page['pageSize'],
            'count' => $commentsService->allCount()
        ], 'success');

    }

    /**
     * @return bool
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\OrderByFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function list()
    {
        $articleId = $this->request()->getRequestParam('articleId');
        if (empty($articleId)) return $this->writeJson(200, [], '请选择文章');
        $commentsService = new CommentsService();
        $this->writeJson(200, [
            'list' => $commentsService->list($articleId),
            'count' => $commentsService->count($articleId)
        ], 'success');

    }
}