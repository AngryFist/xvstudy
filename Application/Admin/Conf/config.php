<?php
return array(
	//'配置项'=>'配置值'

    'uploadConfig' => array(
        'maxSize'    =>    0,
        'rootPath'   =>    './Public/Uploads/',
        'saveName'   =>    array('uniqid',''),
        'exts'       =>    array('jpg', 'gif', 'png', 'jpeg', 'flv', 'mp4'),
        //'autoSub'    =>    false,
        'subName'    =>    array('date','Ymd'),
    )
);
