<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/1/13
 * Time: 10:20
 */

namespace App\HttpController\Api;

use App\Service\ArticleService;
use App\Service\BlogService;
use App\Service\CategoryService;
use App\Service\TagService;

class Category extends BaseController
{
    public function list()
    {
        $categoryService = new CategoryService();
        $all = $this->request()->getRequestParam('all');
        $page = $this->request()->getRequestParam('page');
        $pageSize = $this->request()->getRequestParam('pageSize');

        $count = $categoryService->getCategoryCount();


        if ('true' == $all) {
            $pageSize = $count;
        }

        $result = $categoryService->getCategoryList(['page' => $page, 'pageSize' => $pageSize]);


        return $this->writeJson(200, $result, 'ok');

    }



}