<div>
    <ul class="breadcrumb">
        <li class="active">管理员</li>
        <li class="active">Administrator</li>
    </ul>
</div>
<?php
//echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-plus-sign"></span>新增管理员',['user/add'],['class'=>'btn btn-info']);
?>
<table class="table table-bordered table-responsive" style="margin-top:10px;">
    <tr>
        <th>ID</th>
        <th>用户名</th>
        <!--<th>密码</th>-->
        <th>邮箱</th>
        <th>角色</th>
        <th>状态</th>
        <th>注册时间</th>
        <th>最后登录</th>
        <th>最后登录IP</th>
        <th>操作</th>
    </tr>
    <?php foreach ($users as $user):?>
        <tr>
            <td><?=$user->id?></td>
            <td><?=$user->username?></td>
            <!--<td><?/*=$user->password_hash*/?></td>-->
            <td><?=$user->email?></td>
            <td>
                <?php foreach (Yii::$app->authManager->getRolesByUser($user->id) as $role){
                    echo $role->description;
                    echo ' | ';
                }?>
            </td>
            <td><?=\backend\models\User::$status_options[$user->status]?></td>
            <td><?=date('Y-m-d G:i:s',$user->created_at)?></td>
            <td><?=date('Y-m-d G:i:s',$user->last_login)?></td>
            <td><?=$user->last_ip?></td>
            <td>
                <?php if(Yii::$app->user->can('user/del')){echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash"></span>删除',['user/del','id'=>$user->id],['class'=>'btn btn-danger btn-sm']);}?>
                <?php if(Yii::$app->user->can('user/update')){echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-edit"></span>修改',['user/update','id'=>$user->id],['class'=>'btn btn-warning btn-sm']);}?>
                <?php if(Yii::$app->user->can('user/edit') || $user->status==1){echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-pencil"></span>修改密码',['user/edit','id'=>$user->id],['class'=>'btn btn-primary btn-sm']);}?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
