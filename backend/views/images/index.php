<?php
echo \yii\bootstrap\Html::a('添加',['images/add'],['class'=>'btn btn-info']);
?>
<table class="cate table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>图片</th>
        <th>所属商品</th>
        <th>操作</th>
    </tr>
    <?php foreach($images as $image):?>
        <tr>
            <td><?=$image->id?></td>
            <td><?=\yii\bootstrap\Html::img($image->image,['style'=>'width:80','height'=>'50px'])?></td>
            <td><?=$image->goods->name?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['images/update','id'=>$image->id],['class'=>'btn btn-warning btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['images/del','id'=>$image->id],['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
