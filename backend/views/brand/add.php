<?php
use yii\web\JsExpression;


//表单开始
$form=\yii\bootstrap\ActiveForm::begin();
//品牌名称
echo $form->field($model,'name');
//品牌简介
echo $form->field($model,'intro')->textarea();
//隐藏域传地址
echo $form->field($model,'logo')->hiddenInput();
//品牌logo
echo \yii\bootstrap\Html::fileInput('test',null,['id'=>'test','style'=>'display:none']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'width' => 120,
        'height' => 40,
        'onUploadError' => new JsExpression(<<<EOF
function(file, errorCode, errorMsg, errorString) {
    console.log('The file ' + file.name + ' could not be uploaded: ' + errorString + errorCode + errorMsg);
}
EOF
        ),
        'onUploadSuccess' => new JsExpression(<<<EOF
function(file, data, response) {
    data = JSON.parse(data);
    if (data.error) {
        console.log(data.msg);
    } else {
        console.log(data.fileUrl);
        //上传成功，回显图片
        $('#logo_img').attr("src",data.fileUrl).show();
        //保存图片地址
        $('#brand-logo').val(data.fileUrl);
        //console.debug($('#brand-logo').val());
    }
}
EOF
        ),
    ]
]);
//照片回显
if($model->logo){
    echo \yii\bootstrap\Html::img($model->logo,['width'=>80,'height'=>80,'id'=>'logo_img']);
}else{
    echo \yii\bootstrap\Html::img('',['width'=>80,'height'=>80,'style'=>'display:none','id'=>'logo_img']);
}
//品牌排序
echo $form->field($model,'sort');
//品牌状态
echo $form->field($model,'status',['inline'=>true])->radioList(['1'=>'正常','0'=>'隐藏']);
//提交按钮
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-primary']);
//表单结束
\yii\bootstrap\ActiveForm::end();