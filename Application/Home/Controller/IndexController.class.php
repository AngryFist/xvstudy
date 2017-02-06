<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $this->teachers = $this->teachers();
        $this->subjects = $this->subjects();

        $this->teacherID = I('teacher');
        $this->subjectID = I('subject');

        $pageSize = 9;

        $Article = M('article'); // 实例化User对象
        $map = array();
        if($this->teacherID){
            $map['teacher'] = $this->teacherID;
        }
        if($this->subjectID){
            $map['subject'] = $this->subjectID;
        }
        $count      = $Article->where($map)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,$pageSize);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $Page->setConfig('prev','上一页');
        $Page->setConfig('next','下一页');
        $pageShow       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $Article->alias('a')->join('xvs_category c ON a.subject = c.id')->join('xvs_category c2 ON a.teacher = c2.id')->where($map)->order(array('a.order','a.id desc'))->limit($Page->firstRow.','.$Page->listRows)->getField('a.*,c.name as subject_name,c2.name as teacher_name');
        $this->assign('articles',$list);// 赋值数据集
        $this->assign('page',$pageShow);// 赋值分页输出

        $this->display();
    }
    public function teachers(){
        $map['parent'] = 76;
        $result = M('category')->where($map)->select();
        return $result;
    }
    public function subjects(){
        $map['parent'] = 77;
        $result = M('category')->where($map)->select();
        return $result;
    }
    public function articles(){
        $result = M('article')->alias('a')->join('xvs_category c ON a.subject = c.id')->join('xvs_category c2 ON a.teacher = c2.id')->order(array('a.order','a.id desc'))->getField('a.*,c.name as subject_name,c2.name as teacher_name');
        return $result;
    }
}
