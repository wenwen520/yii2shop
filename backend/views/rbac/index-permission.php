<div>
    <ul class="breadcrumb">
        <li class="active">首页</li>
        <li class="active">Permission</li>
    </ul>
</div>
<?php
echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-plus-sign"></span>新增权限',['rbac/add-permission'],['class'=>'btn btn-info']);
?>
<table class="table table-responsive table-bordered" style="margin-top: 10px">
    <tr>
        <th>权限名</th>
        <th>描述</th>
        <th>操作</th>
    </tr>
    <?php foreach($permissions as $permission):?>
        <tr>
            <td><?=$permission->name?></td>
            <td><?=$permission->description?></td>
            <td>
                <?php echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-edit"></span>更新',['rbac/update-permission','name'=>$permission->name],['class'=>'btn btn-warning btn-sm']);?>
                <?php echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash"></span>删除',['rbac/del-permission','name'=>$permission->name],['class'=>'btn btn-danger btn-sm']);?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
