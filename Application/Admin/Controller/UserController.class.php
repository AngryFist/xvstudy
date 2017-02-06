<?php
namespace Admin\Controller;
use Think\Controller;

class UserController extends Controller{
    public function __construct(){
        parent::__construct();
    }
    //index初始化
    public function index(){
        D('Index')->loginCheck();
        $this->id = I('id');
        $this->action = I('action');
        $this->list = $this->getList();
        if($this->id){
            $this->info = $this->getInfo();
        }
        $this->display();
    }
    //获取用户列表
    public function getList(){
        $result = M('User')->order(array('id'))->select();
        return $result;
    }
    //获取单个用户信息
    public function getInfo(){
        $map['id'] = $this->id;
        $result = M('User')->where($map)->find();
        return $result;
    }

    //添加和编辑
    public function edit(){
        if(I('post.')){
            $postData = I('post.');
            if (!D('User')->create($postData)){
                 // 对data数据进行验证
                 $retult['status'] = 0;
                 $retult['info'] = D('User')->getError();
            }else{
                if($postData['act'] == 'add'){
                    $postData['password'] = md5($postData['password']);
                    $retult['status'] = M('User')->add($postData);
                }
                if($postData['act']  == 'edit'){
                    $postData = I('post.');
                    $map['id'] = $postData['id'];
                    $postData['password'] = md5($postData['password']);
                    $retult['status'] = M('User')->where($map)->save($postData);
                }
            }
            echo json_encode($retult);
        }
    }
    //删除
    public function del(){
        if(I('post.id')){
            $map['id'] = I('post.id');
            $retult['status'] = M('User')->where($map)->delete();
            echo json_encode($retult);
        }
    }

}
