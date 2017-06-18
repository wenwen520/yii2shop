<div>
    <ul class="breadcrumb">
        <li class="active">ADD</li>
        <li class="active">UPDATE</li>
    </ul>
</div>
<?php
//表单开始
$form=\yii\bootstrap\ActiveForm::begin();
//菜单名称
echo $form->field($model,'label');
//路由
echo $form->field($model,'url')->dropDownList(\yii\helpers\ArrayHelper::map(Yii::$app->authManager->getPermissions(),'name','description'),['prompt'=>'请选择路由']);
//菜单分类
echo $form->field($model,'parent_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\Menu::DropMenus(),'id','label'));
//排序
echo $form->field($model,'sort');
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>"btn btn-info"]);
//表单结束
\yii\bootstrap\ActiveForm::end();
?>
