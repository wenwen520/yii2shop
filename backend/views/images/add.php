<?php
//表单开始
$form = \yii\bootstrap\ActiveForm::begin();
//商品ID
echo $form->field($model,'goods_id')->dropDownList(\yii\helpers\ArrayHelper::map($goods,'id','name'),['prompt'=>'请选择商品']);
//图片
echo $form->field($model,'imgFile')->fileInput();
//图片回显
if($model->image){echo '<div>'.\yii\bootstrap\Html::img($model->image,['style'=>'width:80','height'=>'50px']).'</div>';}
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
//表单结束
\yii\bootstrap\ActiveForm::end();