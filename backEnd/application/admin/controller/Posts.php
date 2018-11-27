<?php
// +----------------------------------------------------------------------
// | Description: 部门控制器
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Posts extends ApiCommon
{
    
    public function index()
    {   
        $postModel = model('Post');
        $param = $this->param;
        $keywords = empty(input('keywords'))? input('keywords'): '';
        $data = $postModel->getDataList($keywords);
        return resultArray(['data' => $data]);
    }

    public function read()
    {   
        $postModel = model('Post');
        $param = $this->param;
        $data = $postModel->getDataById(input('id'));
        if (!$data) {
            return resultArray(['error' => $postModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $postModel = model('Post');
        $param = $this->param;
        $data = $postModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $postModel->getError()]);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update()
    {
        $postModel = model('Post');
        $param = $this->param;
        $data = $postModel->updateDataById($param, input('id'));
        if (!$data) {
            return resultArray(['error' => $postModel->getError()]);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function delete()
    {
        $postModel = model('Post');
        $param = $this->param;
        $data = $postModel->delDataById(input('id'));       
        if (!$data) {
            return resultArray(['error' => $postModel->getError()]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $postModel = model('Post');
        $param = $this->param;
        $data = $postModel->delDatas(input('ids'));  
        if (!$data) {
            return resultArray(['error' => $postModel->getError()]);
        } 
        return resultArray(['data' => '删除成功']); 
    }

    public function enables()
    {
        $postModel = model('Post');
        $param = $this->param;
        $data = $postModel->enableDatas(input('ids'), input('status'));  
        if (!$data) {
            return resultArray(['error' => $postModel->getError()]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
    
}
 