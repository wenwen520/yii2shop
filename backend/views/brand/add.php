<?php
//表单开始
$form=\yii\bootstrap\ActiveForm::begin();
//品牌名称
echo $form->field($model,'name');
//品牌简介
echo $form->field($model,'intro')->textarea();
//品牌logo
echo $form->field($model,'imgFile')->fileInput();
//照片回显
if($model->logo){echo \yii\bootstrap\Html::img($model->logo,['width'=>80,'height'=>80]);}
//品牌排序
echo $form->field($model,'sort');
//品牌状态
echo $form->field($model,'status',['inline'=>true])->radioList(['1'=>'正常','0'=>'隐藏']);
//验证码
echo $form->field($model,'code')->widget(\yii\captcha\Captcha::className(),[
    'captchaAction'=>'brand/captcha',
    'template'=>'<div class="row"><div class="col-md-2">{image}</div><div class="col-md-2">{input}</div></div>'
]);
//提交按钮
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-primary']);
//表单结束
\yii\bootstrap\ActiveForm::end();