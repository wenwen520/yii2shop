<div>
    <ul class="breadcrumb">
        <li class="active">ADD</li>
        <li class="active">UPDATE</li>
    </ul>
</div>
<?php
$form=\yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'description')->textarea();
echo $form->field($model,'permission',['inline'=>true])->checkboxList(\backend\models\RoleForm::getPermissionOptions());
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();