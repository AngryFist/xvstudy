<?php
namespace Admin\Controller;
use Think\Controller;

class SubjectController extends Controller{

    protected $_validate = array(
        // array('verify','require','验证码必须！'), //默认情况下用正则进行验证
        // array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        // array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
        // array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
        // array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
    );

    public function __construct(){
        parent::__construct();
        $this->model = D('Subject');
    }
    //index初始化
    public function index(){
        D('Index')->loginCheck();
        $this->parentid = 77;
        $this->id = I('id');
        $this->action = I('action') ? I('action') : 'add';
        $this->childrenList = $this->getChildren($this->parentid);
        $this->treeHtml = $this->getTree(D('category')->getTree($this->parentid, 'subject'));
        $this->category = D('category')->getCategory($this->id);
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
                $treeHtml .= '<li data-id="'.$value['id'].'">'.$value['name'].' <a href="/Admin/Subject/index/id/'.$value['id'].'/action/edit" class="ajax">编辑</a>  ';
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
    //获取子类目
    public function getChildren($parent = 0){
        $result = D('category')->getTree($parent, 'subject');
        $result[] = array('mode'=>'new');
        $result[] = array('mode'=>'new');
        $result[] = array('mode'=>'new');
        $result[] = array('mode'=>'new');
        return $result;
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
        if(I('post.')){
            $result['status'] = $this->model->del(I('id'));
            $this->success();
        }
    }

}
