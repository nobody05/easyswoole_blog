<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/3/24
 * Time: 16:30
 */

namespace App\HttpController\Api;

use App\Service\ArticleService;
use App\Service\BlogService;
use App\Service\CategoryService;
use App\Service\CommentsService;
use App\Service\TagService;
use App\Service\UtilService;

class Comments extends BaseController
{
    /**
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\OrderByFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function list()
    {
        $commonService = new CommentsService();
        $articleId = $this->request()->getRequestParam('articleId');
        if (empty($articleId)) return $this->writeJson(200, [], '请选择文章');

        $this->writeJson(200, [
            'list' => $commonService->list($articleId),
            'count' => $commonService->count($articleId)
        ], 'success');
    }

    /**
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function add()
    {
        $params = $this->request()->getRequestParam();
        $data = array(
            'name' => '',
            'email' => '',
            'content' => '',
            'sourceContent' => '',
            'articleId' => '',
            'replyId' => ''
        );
        foreach($data as $k => $v) {
            $data[$k] =  UtilService::getInstance()->get_param($params, $k);
        }
        if ($data['name'] == '') {
            return $this->writeJson(200, '昵称不能为空');
        }
        if ($data['content'] == '') {
            return $this->writeJson(200, '评论内容不能为空');
        }

        $articleService = new ArticleService();
        $articleInfo = $articleService->getArticle($data['articleId']);

        if (empty($articleInfo)) return $this->writeJson(200, [],'请选择文章');

        $commentService = new CommentsService();
        $result = $commentService->add($data);

        $this->writeJson(200, [], $result);
    }
}