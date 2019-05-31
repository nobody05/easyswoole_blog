<?php

namespace App\HttpController\Api;

use App\Service\ArticleService;
use App\Service\CategoryService;
use App\Service\SearchService;
use App\Service\TagService;
use App\Service\UtilService;

class Search extends BaseController
{
    public function search()
    {
        $articleService = new ArticleService();
        $categoryService = new CategoryService();
        $tagService = new TagService();
        $params = $this->request()->getRequestParam();
//        $by = UtilService::getInstance()->get_param($params, 'by', '');
        $pageOpt = UtilService::getInstance()->get_page($params);
        $title = UtilService::getInstance()->get_param($params, 'searchValue', '');
        $searchService = new SearchService();
        $searchService->setIndex('articles');
        $searchService->setType('article');
        $esResult = $searchService->searchByKeyword($title);
        // es查询到
        if (!empty($esResult['hits']['hits'])) {
            $ids = array_column($esResult['hits']['hits'], '_source');
            $ids = array_column($ids, 'id');
            $result = $articleService->getArticleList('id', 1, 0, 0, $pageOpt, $ids);
            foreach ($result['list'] as $k=>$articleInfo) {
                unset($result['list'][$k]);
                $result['list'][$k]['article'] = $articleInfo;
                $result['list'][$k]['category'] = $categoryService->categoryInfo($articleInfo['categoryId']);
                $result['list'][$k]['tags'] = $tagService->getTagByArticleId($articleInfo['id']);
            }
            return $this->writeJson(200, $result);
        } else {
            return $this->writeJson(200, []);
        }

    }
}