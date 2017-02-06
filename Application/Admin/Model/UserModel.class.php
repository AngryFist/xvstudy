<?php
namespace Admin\Model;
use Think\Model;
class UserModel extends Model{

    protected $_validate = array(
        array('username','/[A-Za-z0-9]{4,16}/','用户名只能是4-16位数字和字母'), //默认情况下用正则进行验证
        array('username','','用户名已存在', 0,'unique',1), //默认情况下用正则进行验证
        array('repassword','password','两次输入的密码不一致',0,'confirm'), // 验证确认密码是否和密码一致
        array('password','/[A-Za-z0-9]{4,16}/','密码只能是4-16位数字和字母',), // 自定义函数验证密码格式
    );

    public function __construct(){
        parent::__construct();
    }

}

?>
