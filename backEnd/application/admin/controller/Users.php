<?php
// +----------------------------------------------------------------------
// | Description: 系统用户
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Users extends ApiCommon
{

    public function index()
    {   
        $userModel = model('User');
        $param = $this->param;
        $keywords = !empty(input('keywords')) ? input('keywords'): '';
        $page = !empty(input('page')) ? input('page'): '';
        $limit = !empty(input('limit')) ? input('limit'): '';    
        $data = $userModel->getDataList($keywords, $page, $limit);
        return resultArray(['data' => $data]);
    }

    public function read()
    {   
        $userModel = model('User');
        $param = $this->param;
        $data = $userModel->getDataById(input('id'));
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $userModel = model('User');
        $param = $this->param;
        $data = $userModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update()
    {
        $userModel = model('User');
        $param = $this->param;
        $data = $userModel->updateDataById($param, input('id'));
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function delete()
    {
        $userModel = model('User');
        $param = $this->param;
        $data = $userModel->delDataById(input('id'));       
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $userModel = model('User');
        $param = $this->param;
        $data = $userModel->delDatas(input('ids'));  
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        } 
        return resultArray(['data' => '删除成功']); 
    }

    public function enables()
    {
        $userModel = model('User');
        $param = $this->param;
        $data = $userModel->enableDatas(input('ids'), input('status'));  
        if (!$data) {
            return resultArray(['error' => $userModel->getError()]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
    
}
 