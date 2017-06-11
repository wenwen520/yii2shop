<?php
\backend\assets\AppAsset::addScript($this,'/zTree/js/jquery.ztree.core.js');
\backend\assets\AppAsset::addScript($this,'/myjs/demoZtree.js');
echo \yii\bootstrap\Html::a('新增分类',['goods_category/add'],['class'=>'btn btn-info']);
?>
<!DOCTYPE html>
<HTML>
<HEAD>
    <TITLE>商品分类</TITLE>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="stylesheet" href="/zTree/css/zTreeStyle/zTreeStyle.css" type="text/css">
</HEAD>
<BODY>
<div>
    <ul id="treeDemo" class="ztree"></ul>
</div>
</BODY>
</HTML>