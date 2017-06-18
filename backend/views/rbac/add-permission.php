<div>
    <ul class="breadcrumb">
        <li class="active">ADD</li>
        <li class="active">UPDATE</li>
    </ul>
</div>
<?php
$form=\yii\bootstrap\ActiveForm::begin();
//权限名
echo $form->field($model,'name');
//描述
echo $form->field($model,'description')->textarea();
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-info']);
//结束
\yii\bootstrap\ActiveForm::end();