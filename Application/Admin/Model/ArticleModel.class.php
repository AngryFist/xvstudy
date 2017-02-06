<?php
namespace Admin\Model;
use Think\Model;
class ArticleModel extends Model{

    protected $_validate = array(
        array('title','require','请填写课程标题！'), //默认情况下用正则进行验证
        // array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        // array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
        // array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
        // array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
    );


    public function __construct(){
        parent::__construct();
    }

    //获取文章列表
    public function getList($parent = 0, $type = 'article'){
        $arr = array();
        $map['parent'] = $parent;
        $map['type'] = $type;
        $result = M('category')->where($map)->order(array('order','id'))->select();
        if($result){
            foreach ($result as $value) {
                $value['children'] = $this->getTree($value['id']);
                $arr[] = $value;
            }
            return $arr;
        }
    }

    //快速添加  编辑
    public function add(){
        $postData = I('post.');
        $retult = M('article')->add($postData);
        return $retult;
    }

}

?>
