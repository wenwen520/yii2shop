<div>
    <ul class="breadcrumb">
        <li class="active">修改密码</li>
        <li class="active">Edit</li>
    </ul>
</div>
<?php
//表单开始
$form=\yii\bootstrap\ActiveForm::begin();
//旧密码
echo $form->field($model,'old_password')->passwordInput();
//新密码
echo $form->field($model,'password')->passwordInput();
//确认密码
echo $form->field($model,'re_password')->passwordInput();
//提交按钮
echo \yii\bootstrap\Html::submitButton('确认修改',['class'=>'btn btn-primary']);
//表单结束
\yii\bootstrap\ActiveForm::end();