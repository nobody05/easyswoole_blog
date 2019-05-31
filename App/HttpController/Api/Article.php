<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2018/12/28
 * Time: 18:35
 */

namespace App\HttpController\Api;

use App\Service\ArticleService;
use App\Service\BlogService;
use App\Service\CategoryService;
use App\Service\TagService;
use App\Service\UtilService;

class Article extends BaseController
{
    /**
     * @return bool
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\JoinFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function list()
    {
        $articleService = new ArticleService();
        $categoryService = new CategoryService();
        $tagService = new TagService();

        $params = $this->request()->getRequestParam();
        $by = UtilService::getInstance()->get_param($params, 'by', '');
        $status = UtilService::getInstance()->get_param($params, 'status', 0);
        $categoryId = UtilService::getInstance()->get_param($params, 'categoryId', '');
        $tagId = UtilService::getInstance()->get_param($params, 'tagId', '');
        $pageOpt = UtilService::getInstance()->get_page($params);

        $result = $articleService->getArticleList($by, $status, $categoryId, $tagId, $pageOpt);


        foreach ($result['list'] as $k=>$articleInfo) {
            unset($result['list'][$k]);
            $result['list'][$k]['article'] = $articleInfo;
            $result['list'][$k]['category'] = $categoryService->categoryInfo($articleInfo['categoryId']);
            $result['list'][$k]['tags'] = $tagService->getTagByArticleId($articleInfo['id']);
        }

        return $this->writeJson(200, $result, 'success');
    }

    /**
     * 添加文章
     * @param $datas
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function add($datas)
    {
        $articleService = new ArticleService();
        $result = $articleService->updateArticle($datas);
        $this->writeJson(200, $result, 'success');
    }

    /**
     * 文章内容
     * @return boolean|void
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function index()
    {
        $articleService = new ArticleService();
        $blogService = new BlogService();

        $id = $this->request()->getRequestParam("id");
        if (empty($id)) $this->writeJson(900, [], '请选择一篇文章');

        $articleInfo = $articleService->getArticle($id);
        $qrCode = $blogService->getBlogConfig();
        $articleNext = $articleService->getNextArticle($id);
        $articlePre = $articleService->getPreArticle($id);
        $articleService->incrementPageView($id);

        $this->writeJson(200, [
            'article' => $articleInfo,
            'category' => ['id' => $articleInfo['categoryId'], 'title' => 'title'],
            'tags' => [],
            'qrcode' => ['alipayQrcode' => $qrCode['alipayQrcode'], 'wxpayQrcode' => $qrCode['wxpayQrcode']],
            'pn' => ['next' => $articleNext, 'pre' => $articlePre]
        ], 'success');
    }


}