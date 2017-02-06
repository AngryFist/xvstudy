<?php
namespace Admin\Controller;
use Think\Controller;
class ArticleController extends Controller {
    public function __construct(){
        parent::__construct();
        $this->model = D('article');
    }
    public function index(){
        D('Index')->loginCheck();
        $this->id = I('id');
        $this->cid = I('cid');
        $this->action = I('action') ? I('action') : 'articles';
        $this->treeHtml = R('Category/getTree',array(D('category')->getTree()));
        $this->selectHtml = R('Category/getSelect',array('', '',D('category')->getTree()));
        $this->subjectSelectHtml = R('Category/getSelect',array('', '',D('category')->getTree(77, 'subject')));
        $this->teacherSelectHtml = R('Category/getSelect',array('', '',D('category')->getTree(76, 'teacher')));
        //dump(session());
        if($this->id && $this->action == 'edit'){
            $map['id'] = $this->id;
            $this->info = M('article')->where($map)->find();
        }else{
            $map['cid'] = $this->cid;
            $User = M('article'); // 实例化User对象
            $count      = $User->where($map)->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $Page->setConfig('prev','上一页');
            $Page->setConfig('next','下一页');
            $pageShow       = $Page->show();// 分页显示输出
            // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
            $list = $User->alias('a')->join('xvs_category c0 ON a.cid = c0.id')->join('xvs_category c1 ON a.subject = c1.id')->join('xvs_category c2 ON a.teacher = c2.id')->where($map)->order(array('a.order','a.id desc'))->limit($Page->firstRow.','.$Page->listRows)->getField('a.*,c0.name as category_name,c1.name as subject_name,c2.name as teacher_name');
            $this->assign('list',$list);// 赋值数据集
            $this->assign('page',$pageShow);// 赋值分页输出
        }
        $this->display(); // 输出模板

    }
    //
    public function articles(){
        $this->display();
    }
    //添加和编辑
    public function editArticle(){
        if(I('post.')){
            $postData = I('post.');
            if (!D('article')->create($postData)){
                 // 对data数据进行验证
                 $retult['status'] = 0;
                 $retult['info'] = D('article')->getError();
            }else{
                if($postData['act'] == 'add'){

                    $retult['status'] = M('article')->add($postData);
                }
                if($postData['act']  == 'edit'){
                    $postData = I('post.');
                    $map['id'] = $postData['id'];
                    $retult['status'] = M('article')->where($map)->save($postData);
                }
            }
            echo json_encode($retult);
        }
    }
    //添加子类目
    public function del(){
        if(I('post.id')){
            $map['id'] = I('post.id');
            $retult['status'] = M('article')->where($map)->delete();
            echo json_encode($retult);
        }
    }
    public function uploadImg(){
        $upload = new \Think\Upload(C('uploadConfig'));
        $upload->maxSize  = 2048000;
        $upload->exts     = array('jpg', 'gif', 'png', 'jpeg');
        $upload->rootPath = './Public/Uploads/images/';
        $info   =   $upload->uploadOne($_FILES['thumb']);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            $info['rootPath'] = $upload->rootPath;
            echo json_encode($info);
        }
    }
    public function uploadVideo(){
        $upload = new \Think\Upload(C('uploadConfig'));
        $upload->exts     = array('flv','mp4');
        $upload->rootPath = './Public/Uploads/videos/';
        $info   =   $upload->uploadOne($_FILES['video']);
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功 获取上传文件信息
            $info['rootPath'] = $upload->rootPath;
            echo json_encode($info);
        }
    }
}
