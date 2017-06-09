<?php
//表单开始
$form=\yii\bootstrap\ActiveForm::begin();
//分类名称
echo $form->field($model,'name');
//分类简介
echo $form->field($model,'intro')->textarea();
//分类排序
echo $form->field($model,'sort');
//分类状态
echo $form->field($model,'status',['inline'=>true])->radioList(['1'=>'正常','0'=>'隐藏']);
//分类类型
echo $form->field($model,'is_help',['inline'=>true])->radioList(['1'=>'是','0'=>'否']);
//提交按钮
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-primary']);
//表单结束
\yii\bootstrap\ActiveForm::end();