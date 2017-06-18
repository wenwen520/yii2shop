<div>
    <ul class="breadcrumb">
        <li class="active">首页</li>
        <li class="active">Role</li>
    </ul>
</div>
<?php
echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-plus-sign"></span>新增角色',['rbac/add-role'],['class'=>'btn btn-info']);
?>
<table class="table table-bordered table-responsive" style="margin-top: 10px;">
    <tr>
        <th>角色名</th>
        <th>描述</th>
        <th>权限</th>
        <th>操作</th>
    </tr>
    <?php foreach($roles as $role):?>
        <tr>
            <td><?=$role->name?></td>
            <td><?=$role->description?></td>
            <td>
                <?php foreach(Yii::$app->authManager->getPermissionsByRole($role->name) as $permission){
                    echo $permission->description;
                    echo ' | ';
                }
                ?>
            </td>
            <td>
                <?php echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-edit"></span>更新',['rbac/update-role','name'=>$role->name],['class'=>'btn btn-warning btn-sm']);?>
                <?php echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash"></span>删除',['rbac/del-role','name'=>$role->name],['class'=>'btn btn-danger btn-sm']);?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
