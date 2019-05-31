<?php
/**
 * Created by PhpStorm.
 * User: gaoz
 * Date: 2019/1/13
 * Time: 10:22
 */

namespace App\HttpController\Api\Admin;

use App\HttpController\Api\BaseController;
use App\Service\CategoryService;

class Category extends BaseController
{
    /**
     * @return bool
     * @throws \EasySwoole\Mysqli\Exceptions\ConnectFail
     * @throws \EasySwoole\Mysqli\Exceptions\PrepareQueryFail
     * @throws \Throwable
     */
    public function add()
    {
        $categoryName = $this->request()->getRequestParam("categoryName");

        $categoryService = new CategoryService();
        $result = $categoryService->addCategory($categoryName);
        if (!$result) return $this->writeJson('900', [], $result['msg']);

        return $this->writeJson(200, $result, 'ok');

    }

    public function list()
    {
        $all = $this->request()->getRequestParam('all');
        $page = $this->request()->getRequestParam('page');
        $pageSize = $this->request()->getRequestParam('pageSize');

        $categoryService = new CategoryService();
        $count = $categoryService->getCategoryCount();


        if ('true' == $all) {
            $pageSize = $count;
        }

        $result = $categoryService->getCategoryList(['page' => $page, 'pageSize' => $pageSize]);


        return $this->writeJson(200, $result, 'ok');

    }

    public function index()
    {
        $categoryId = $this->request()->getRequestParam("categoryId");

        $categoryService = new CategoryService();
        $info = $categoryService->categoryInfo($categoryId);

        if (is_string($info)) return $this->writeJson(900, [], $info);

        return $this->writeJson(200, $info, 'ok');
    }

    public function modify()
    {

    }


}