<?php
namespace Admin\Model;
use Think\Model;
class CategoryModel extends Model{
    public function __construct(){
        parent::__construct();
    }
    //获取分类树
    public function getTree($parent = 0, $type = 'article'){
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
    //获取某个分类到根分类的完整数组链
    public function getPath($id){
        $arr = array();
        $map['id'] = $id;
        $result = M('category')->where($map)->find();
        $arr[] = $result;
        if($result['parent'] != 0){
            $arr[] = $this->getPath($result['parent']);
        }
        return $arr;
    }
    //获取某个分类的父分类
    public function getParent($id){
        $map['id'] = $id;
        $parent = M('category')->where($map)->getField('parent');
        $map2['id'] = $parent;
        $result = M('category')->where($map2)->find();
        return $result;
    }
    //获取分类信息
    public function getCategory($id){
        $map['id'] = $id;
        $result = M('category')->where($map)->find();
        return $result;
    }

    //快速添加  编辑
    public function quickEdit(){
        $postData = I('post.');
        //$data = M('category')->field('name,alias,parent')->create();
        //dump($postData); die;
        foreach ($postData['name'] as $key => $name){
            if($name != ''){
                if($postData['mode'][$key] == 'new'){
                    $newCategoryList[] = array('name'=>$name,'alias'=>$postData['alias'][$key],'parent'=>$postData['parent'],'grade'=>$postData['grade'],'order'=>100 * $key,'type'=>'article', 'mode'=>$postData['mode'][$key]);
                }else{
                    $updateCategoryList[] = array('id'=>$postData['id'][$key],'name'=>$name,'alias'=>$postData['alias'][$key],'parent'=>$postData['parent'],'grade'=>$postData['grade'],'order'=>100 * $key,'type'=>'article', 'mode'=>'update');
                }
            }
        }
        $retult = true;
        //dump($updateCategoryList); die;
        if(count($newCategoryList)) {
            $retult = M('category')->addAll($newCategoryList);
        }
        if(count($updateCategoryList)) {
            foreach ($updateCategoryList as $key => $value) {
                $retult = M('category')->save($value);
            }

        }
        return $retult;

    }
    //编辑
    public function edit($id){
        $postData = I('post.');
        $data['id'] = $postData['id'];
        $data['parent'] = $postData['parent'];
        $data['name'] = $postData['name'];
        $data['abbr'] = $postData['abbr'];
        $data['desc'] = $postData['desc'];
        $retult = M('category')->save($data);
        return $result;
    }
}

?>
