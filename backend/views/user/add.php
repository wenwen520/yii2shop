<div>
    <ul class="breadcrumb">
        <li class="active">ADD</li>
        <li class="active">UPDATE</li>
    </ul>
</div>
<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
if(!$model->password_hash){echo $form->field($model,'password_hash')->passwordInput();}
echo $form->field($model,'email');
//用户角色
echo $form->field($model,'roles',['inline'=>true])->checkboxList(\backend\models\User::getRolesOptions());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();