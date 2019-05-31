<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/1/14
 * Time: 21:01
 */

namespace App\HttpController\Api\Admin;

use App\HttpController\Api\BaseController;
use App\Service\ArticleService;
use App\Service\UtilService;

class Article extends BaseController
{
    /**
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function save()
    {
        $params = $this->request()->getRequestParam();
        $article = array(
            'id'=> '',
            'content'=> '',
            'htmlContent'=> '',
            'title'=> '',
            'cover'=> '',
            'subMessage'=> '',
            'isEncrypt'=> '',
            'categoryId' => ''
        );
        foreach($article as $k => $v) {
            $article[$k] = UtilService::getInstance()->get_param($params, $k);
        }

        $number = is_numeric($article['isEncrypt']);
        if (!$number) {
            $article['isEncrypt'] = 0;
        } else {
            $article['isEncrypt'] = intval($article['isEncrypt']);
            if ($article['isEncrypt'] != 0 && $article['isEncrypt'] != 1) {
                $article['isEncrypt'] = 0;
            }
        }

        $articleService = new ArticleService();
        $result = $articleService->updateArticle($article);

        return $this->writeJson(200, $result, 'ok');
    }

    /**
     * @return bool
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function publish()
    {
        $params = $this->request()->getRequestParam();
        $article = array(
            'id'=> '',
            'content'=> '',
            'htmlContent'=> '',
            'title'=> '',
            'cover'=> '',
            'subMessage'=> '',
            'isEncrypt'=> '',
            'categoryId' => ''
        );
        foreach($article as $k => $v) {
            $article[$k] = UtilService::getInstance()->get_param($params, $k);
        }

        $number = is_numeric($article['isEncrypt']);
        if (!$number) {
            $article['isEncrypt'] = 0;
        } else {
            $article['isEncrypt'] = intval($article['isEncrypt']);
            if ($article['isEncrypt'] != 0 && $article['isEncrypt'] != 1) {
                $article['isEncrypt'] = 0;
            }
        }

        $articleService = new ArticleService();
        $result = $articleService->publishArticle($article);

        return $this->writeJson(200, $result, 'ok');


    }

    public function list()
    {
        $params = $this->request()->getRequestParam();
        $by = UtilService::getInstance()->get_param($params, 'by');
        $status = UtilService::getInstance()->get_param($params, 'status');
        $categoryId = UtilService::getInstance()->get_param($params, 'categoryId');
        $tagId = UtilService::getInstance()->get_param($params, 'tagId');
        $pageOpt = UtilService::getInstance()->get_page($params);

        $articleService = new ArticleService();
        $result = $articleService->getArticleList($by, $status, $categoryId, $tagId, $pageOpt);

        return $this->writeJson(200, $result, 'ok');
    }

    /**
     * @return bool
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function info()
    {
        $id = $this->request()->getRequestParam('id');
        $articleService = new ArticleService();
        $result = $articleService->getArticleAdmin($id);

        if (is_array($result)) {
            return $this->writeJson(200, $result, 'success');
        } else {
            return $this->writeJson(900, [], $result);
        }
    }

    /**
     * @return bool
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function delete()
    {
        $id = $this->request()->getRequestParam('id');
        $articleService = new ArticleService();
        $result = $articleService->delete($id);

        if ("success" == $result) {
            return $this->writeJson(200, $result, 'success');
        } else {
            return $this->writeJson(900, [], $result);
        }
    }
}