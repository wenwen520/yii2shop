<?php
echo \yii\bootstrap\Html::a('添加',['goods_category/add'],['class'=>'btn btn-info']);
?>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>树ID</th>
        <th>左值</th>
        <th>右值</th>
        <th>层级</th>
        <th>名称</th>
        <th>上级分类</th>
        <th>简介</th>
        <th>操作</th>
    </tr>
    <?php foreach($categorys as $category):?>
        <tr>
            <td><?=$category->id?></td>
            <td><?=$category->tree?></td>
            <td><?=$category->lft?></td>
            <td><?=$category->rgt?></td>
            <td><?=$category->depth?></td>
            <td><?=$category->name?></td>
            <td><?=$category->parent_id?></td>
            <td><?=$category->intro?></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['goods_category/update','id'=>$category->id],['class'=>'btn btn-warning btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods_category/del','id'=>$category->id],['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page,
]);