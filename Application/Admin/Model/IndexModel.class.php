<?php
namespace Admin\Model;
use Think\Model;
class IndexModel extends Model{
    protected $tableName='user';

    protected $_validate = array(
        array('username','require','请输入用户名'), //默认情况下用正则进行验证
        array('password','require','请输入密码'), //默认情况下用正则进行验证
    );

    public function __construct(){
        parent::__construct();
    }

    public function loginCheck(){
        $map['id'] = session('userid');
        $map['username'] = session('username');
        $map['password'] = session('password');
        $id = M('User')->where($map)->getField('id');
        if(!$id){
            header("Location: /Admin/Index");
        }
    }

}

?>
