<?php
namespace Home\Model;
use Think\Model;
class ArticleModel extends Model{

    protected $_validate = array(
        array('text','require','信息不能为空'),
    );

    public function __construct(){
        parent::__construct();
    }

}

?>
