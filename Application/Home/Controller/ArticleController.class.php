<?php
namespace Home\Controller;
use Think\Controller;
class ArticleController extends Controller {
    public function index(){
        $this->id = I('id');
        $map['id'] = $this->id;
        $this->info = M('article')->where($map)->find();
        $this->teacher = $this->teacher($this->info['teacher']);
        $this->subject = $this->subject($this->info['subject']);
        $this->display();
    }
    public function teacher($id){
        $map['id'] = $id;
        $result = M('category')->where($map)->find();
        return $result;
    }
    public function subject($id){
        $map['id'] = $id;
        $result = M('category')->where($map)->find();
        return $result;
    }
    //发送消息
    public function sendMsg(){
        if(I('post.')){
            $postData = I('post.');
            if (!D('Article')->create($postData)){
                 // 对data数据进行验证
                 $result['status'] = 0;
                 $result['info'] = D('Article')->getError();
            }else{
                $result['status'] = M('Msg')->add($postData);
            }
            echo json_encode($result);
        }
    }
    //获取消息列表
    public function msgList(){
        $map['aid'] = I('get.aid');
        $result = M('Msg')->where($map)->order(array('id'))->limit(20)->select();
        echo json_encode($result);
    }
}
