<?php
// +----------------------------------------------------------------------
// | Description: 规则
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;

class Rules extends ApiCommon
{

    public function index()
    {   
        $ruleModel = model('Rule');
        $param = $this->param;
        $type = !empty(input('type'))? input('type'): '';
        $data = $ruleModel->getDataList($type);
        return resultArray(['data' => $data]);
    }

    public function read()
    {   
        $ruleModel = model('Rule');
        $param = $this->param;
        $data = $ruleModel->getDataById(input('id'));
        if (!$data) {
            return resultArray(['error' => $ruleModel->getError()]);
        } 
        return resultArray(['data' => $data]);
    }

    public function save()
    {
        $ruleModel = model('Rule');
        $param = $this->param;
        $data = $ruleModel->createData($param);
        if (!$data) {
            return resultArray(['error' => $ruleModel->getError()]);
        } 
        return resultArray(['data' => '添加成功']);
    }

    public function update()
    {
        $ruleModel = model('Rule');
        $param = $this->param;
        $data = $ruleModel->updateDataById($param, input('id'));
        if (!$data) {
            return resultArray(['error' => $ruleModel->getError()]);
        } 
        return resultArray(['data' => '编辑成功']);
    }

    public function delete()
    {
        $ruleModel = model('Rule');
        $param = $this->param;
        $data = $ruleModel->delDataById(input('id'), true);       
        if (!$data) {
            return resultArray(['error' => $ruleModel->getError()]);
        } 
        return resultArray(['data' => '删除成功']);    
    }

    public function deletes()
    {
        $ruleModel = model('Rule');
        $param = $this->param;
        $data = $ruleModel->delDatas(input('ids'), true);  
        if (!$data) {
            return resultArray(['error' => $ruleModel->getError()]);
        } 
        return resultArray(['data' => '删除成功']); 
    }

    public function enables()
    {
        $ruleModel = model('Rule');
        $param = $this->param;
        $data = $ruleModel->enableDatas(input('ids'), input('status'), true);  
        if (!$data) {
            return resultArray(['error' => $ruleModel->getError()]);
        } 
        return resultArray(['data' => '操作成功']);         
    }
}
 