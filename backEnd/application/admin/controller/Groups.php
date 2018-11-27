<?php
// +----------------------------------------------------------------------
// | Description: 用户组
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Groups extends ApiCommon
{
    
    public function index()
    {   
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->getDataList();
        return resultArray(['data' => $data]);
    }

    public function read()
    {   
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->getDataById(input('id'));
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update()
    {
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->updateDataById($param, input('id'));
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function delete()
    {
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->delDataById(input('id'), true);       
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->delDatas(input('ids'), true);  
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => '删除成功']); 
    }

    public function enables()
    {
        $groupModel = model('Group');
        $param = $this->param;
        $data = $groupModel->enableDatas(input('ids'), input('status'), true);  
        if (!$data) {
            return resultArray(['error' => $groupModel->getError()]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
}
 