<?php
// +----------------------------------------------------------------------
// | Description: 控制器
// +----------------------------------------------------------------------
// | Author: linchuangbin <linchuangbin@honraytech.com>
// +----------------------------------------------------------------------

namespace app\admin\controller;
use Db;

class Illegalword extends ApiCommon
{
    
    public function index()
    {   
        $param = $this->param;
        $map=[];
        $keywords = !empty(input('keywords'))? input('keywords'): '';
        if ($keywords) {
			$map[] = ['sitename','like', '%'.$keywords.'%'];
        }
        if (input('agent')) {
			$map[] = ['agent','eq',(int)input('agent')];
        }
        $page= input('page');
        $pagesize = input('limit');
        $data = db('illegal_result')->alias('a')
        ->leftjoin('web_users b','a.agent=b.id')
        ->field('a.*,b.nickname as agentname,b.username as job_num')
        ->where($map)->order('id','desc')
        ->limit($pagesize*($page-1),$pagesize)
        ->select();
        $count = db('illegal_result')->where($map)->count();
        $data=['list'=>$data,'dataCount'=>$count];
        return resultArray(['data' => $data]);
    }

    public function getSites(){
        $map[]=['illegal','eq','1'];
        $keyword = input('keyword');
        if($keyword){
            $map[] = ['cn_project','like', '%'.$keyword.'%'];
        }
        $page= input('page',1);
        $pagesize = input('limit',200);
        $data = db('web_sites')->alias('a')
        ->leftjoin('web_users b','a.assignto=b.id')
        ->field('a.*,b.nickname as agentname,b.username as agent_num')
        ->where($map)->limit($pagesize*($page-1),$pagesize)
        ->select();      
        $count = db('web_sites')
        ->where($map)
        ->count();
        $data=['list'=>$data,'dataCount'=>$count];
        return resultArray(['data' => $data]);
    }

    public function assignTo(){
        $map=[];
        
        $userid= input('userid',0);
        $selected = trim(input('selected'),',');
        $map[] = ['id','in',explode(',',$selected)];
        $data1 = db('web_sites')
        ->where($map)
        ->update(['assignto'=>$userid]);
        $data2 = db('illegal_result')
        ->where('siteid','in',explode(',',$selected))
        ->update(['agent'=>$userid]);      

        $data=['result1'=>$data1,'result2'=>$data2];
        return resultArray(['data' => $data]);
    }

    public function changeStatus(){
        $map=[];
        
        $id= input('rowid',0);
        $status = input('changeto');
        $map[] = ['id','eq',(int)$id];

        $data = db('illegal_result')
        ->where($map)
        ->update(['status'=>$status]);      

        $data=['result'=>$data];
        return resultArray(['data' => $data]);
    }

    public function getUsers(){
        $map[]=['status','eq','1'];
        $keyword = input('keyword');
        if($keyword){
            $map[] = ['nickname','like', '%'.$keyword.'%'];
        }
        $data = db('web_users')
        ->field('id,username,nickname')
        ->where($map)
        ->select();      
        $count = db('web_users')
        ->where($map)
        ->count();
        $data=['list'=>$data,'dataCount'=>$count];
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
 