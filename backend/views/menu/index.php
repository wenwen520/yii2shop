<div>
    <ul class="breadcrumb">
        <li class="active">首页</li>
        <li class="active">Menus</li>
    </ul>
</div>
<?php
//echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-plus-sign"></span>新增菜单',['menu/add'],['class'=>'btn btn-info']);
?>
<table class="table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>菜单名</th>
        <th>路由</th>
        <th>排序</th>
        <th>父类</th>
        <th>操作</th>
    </tr>
    <?php foreach ($menus as $menu):?>
        <tr>
            <td><?=$menu->id?></td>
            <td><?=$menu->label?></td>
            <td><?=$menu->url?></td>
            <td><?=$menu->sort?></td>
            <td><?=$menu->parent?$menu->parent->label:'顶级菜单'?></td>
            <td>
                <?php if(Yii::$app->user->can('menu/del')){echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash"></span>删除',['menu/del','id'=>$menu->id],['class'=>'btn btn-danger btn-sm']);}?>
                <?php if(Yii::$app->user->can('menu/update')){echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-edit"></span>修改',['menu/update','id'=>$menu->id],['class'=>'btn btn-warning btn-sm']);}?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
echo \yii\widgets\LinkPager::widget([
      'pagination'=>$page,
]);
