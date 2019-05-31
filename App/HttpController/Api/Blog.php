<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2018/12/28
 * Time: 11:05
 */

namespace App\HttpController\Api;

use App\Bean\UserBean;
use App\Service\ArticelTagService;
use App\Service\ArticleService;
use App\Service\BlogService;
use App\Service\CategoryService;
use App\Service\TagService;

class Blog extends BaseController
{
    /**
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function index()
    {
        $articleService = new ArticleService();
        $categoryService = new CategoryService();
        $tagService = new TagService();
        $blogService = new BlogService();
        $result = $blogService->getBlogConfig();
        $returnData = [
            "blogName" => $result['blogName'],
            'avatar' => $result['avatar'],
            'sign' => $result['sign'],
            'github' => $result['github'],
            'articleCount' => $articleService->getArticleCount(),
            'categoryCount' => $categoryService->getCategoryCount(),
            'tagCount' => $articleService->getArticleCount()
        ];

        $this->writeJson(200, $returnData, 'success');
    }

}