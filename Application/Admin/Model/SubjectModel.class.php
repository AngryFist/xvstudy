<?php
namespace Admin\Model;
use Think\Model;
class SubjectModel extends Model{

    protected $tableName='category';

    public function __construct(){
        parent::__construct();
    }

    //快速添加  编辑
    public function quickEdit(){
        $postData = I('post.');
        //$data = M('category')->field('name,alias,parent')->create();
        //dump($postData); die;
        foreach ($postData['name'] as $key => $name){
            if($name != ''){
                if($postData['mode'][$key] == 'new'){
                    $newCategoryList[] = array('name'=>$name,'alias'=>$postData['alias'][$key],'parent'=>77,'grade'=>1,'order'=>100 * $key,'type'=>'subject', 'mode'=>$postData['mode'][$key]);
                }else{
                    $updateCategoryList[] = array('id'=>$postData['id'][$key],'name'=>$name,'alias'=>$postData['alias'][$key],'parent'=>77,'grade'=>1,'order'=>100 * $key,'type'=>'subject', 'mode'=>'update');
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
        $data['name'] = $postData['name'];
        $data['alias'] = $postData['alias'];
        $data['desc'] = $postData['desc'];
        $retult = M('category')->save($data);
        return $result;
    }
    //删除
    public function del($id){
        $map['id'] = $id;
        $retult = M('category')->where($map)->delete();
        return $result;
    }
}

?>
