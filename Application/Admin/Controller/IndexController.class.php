<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        session(array('name'=>'userinfo','expire'=>3600));
        $this->display();
    }
    public function login(){
        if(I('post.')){
            $postData = I('post.');
            if (!D('Index')->create($postData)){
                 // 对data数据进行验证
                 $retult['status'] = 0;
                 $retult['info'] = D('User')->getError();
            }else{
                $map['username'] = $postData['username'];
                $map['password'] = md5($postData['password']);
                $retult['status'] = M('User')->where($map)->getField('id');
                $retult['info'] = '登录成功';
                if($retult['status']){
                    session('userid', $retult['status']);
                    session('username', $postData['username']);
                    session('password', md5($postData['password']));
                }
            }
        }
            echo json_encode($retult);
        }
}
