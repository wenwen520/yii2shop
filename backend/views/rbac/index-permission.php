<div>
    <ul class="breadcrumb">
        <li class="active">首页</li>
        <li class="active">Permission</li>
    </ul>
</div>
<?php
//echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-plus-sign"></span>新增权限',['rbac/add-permission'],['class'=>'btn btn-info']);
?>
<table class="table table-responsive table-bordered" style="margin-top: 10px">
    <thead>
        <tr>
            <th>权限名</th>
            <th>描述</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($permissions as $permission):?>
        <tr>
            <td><?=$permission->name?></td>
            <td><?=$permission->description?></td>
            <td>
                <?php if(Yii::$app->user->can('rbac/update-permission')){echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-edit"></span>更新',['rbac/update-permission','name'=>$permission->name],['class'=>'btn btn-warning btn-sm']);}?>
                <?php if(Yii::$app->user->can('rbac/del-permission')){echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash"></span>删除',['rbac/del-permission','name'=>$permission->name],['class'=>'btn btn-danger btn-sm']);}?>
            </td>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({

});');

