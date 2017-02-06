<?php
namespace Admin\Controller;
use Think\Controller;

class CategoryController extends Controller{
    public function __construct(){
        parent::__construct();
        $this->model = D('category');
    }
    //index初始化
    public function index(){
        D('Index')->loginCheck();
        $this->id = I('id') ? I('id') : 0;
        $this->action = I('action') ? I('action') : 'add';
        $this->parent = $this->model->getParent($this->id);
        $this->childrenList = $this->getChildren($this->id);
        $this->treeHtml = $this->getTree($this->model->getTree());
        $this->pathHtml = $this->getPath($this->model->getPath($this->id));
        $this->selectHtml = $this->getSelect('', '',$this->model->getTree());
        $this->category = $this->model->getCategory($this->id);
        if($this->action == 'add'){
            $this->add();
        }
        if($this->action == 'edit'){
            $this->edit();
        }
        $this->display();
    }
    //获取类目目录
    public function getTree($treeArr){
        global $treeHtml;
        if($treeArr){
            if($treeArr[0]['parent'] == 0){
                $treeHtml .= '<ul class="tree" data-type="article" data-ride="tree" data-initial-state="expand">';
            }else{
                $treeHtml .= '<ul>';
            }
            foreach ($treeArr as $value) {
                $hasChild = $value['children'] ? true : false;
                $treeHtml .= '<li data-id="'.$value['id'].'">'.$value['name'].' <a href="/Admin/Category/index/id/'.$value['id'].'/action/edit" class="ajax">编辑</a>  <a href="/Admin/Category/index/id/'.$value['id'].'/action/add" class="ajax">子类目</a>  ';
                if($value['readonly'] == 0 && !$hasChild){
                    $treeHtml .= '<a href="#" data-id="'.$value['id'].'" class="ajax catDel">删除</a>';
                }
                if($hasChild){
                    $this->getTree($value['children']);
                }
                $treeHtml .= '</li>';
            }
            $treeHtml .= '</ul>';
            return $treeHtml;
        }
    }
    //获取类目Select
    public function getSelect($selectHtml = '', $preText = '', $treeArr){
        if($treeArr){
            foreach ($treeArr as $value) {
                $hasChild = $value['children'] ? true : false;
                //if($value['id'] != $this->id){
                    $selectHtml .= '<option value="'.$value['id'].'" >'.$preText.'/'.$value['name'].'</value>  ';
                    //'.($value['id'] == $this->parent['id'] ? 'selected' : '').'
                //}

                if($hasChild){
                    $this->getSelect($selectHtml, $preText .'/' .$value['name'], $value['children']);
                }
            }
        }
        return $selectHtml;
    }
    //获取子类目
    public function getChildren($parent = 0){
        $result = $this->model->getTree($parent);
        $result[] = array('mode'=>'new');
        $result[] = array('mode'=>'new');
        $result[] = array('mode'=>'new');
        $result[] = array('mode'=>'new');
        return $result;
    }
    //获取类目导航
    public function getPath($pathArr){
        global $pathHtml;
        if(count($pathArr) > 1){
            $this->getPath($pathArr[1]);
        }
        if($pathArr[0]){
            $pathHtml .= '<a href="/admin/Category/index/id/'.$pathArr[0]['id'].'/action/add">'.$pathArr[0]['name'].' <i class="icon-angle-right text-muted"></i></a>  ';
        }
        return $pathHtml;
        //
    }

    //子类目快速添加模板
    public function add(){
        if(I('post.')){
            $result['status'] = $this->model->quickEdit();
            $this->success();
        }
    }
    //子类目快速添加模板
    public function edit(){
        if(I('post.')){
            $result['status'] = $this->model->edit();
            $this->success();
        }
    }
    //添加子类目
    public function del(){
        if(I('post.id')){
            $map['id'] = I('post.id');
            $retult['status'] = M('category')->where($map)->delete();
            echo json_encode($retult);
        }
    }

}
