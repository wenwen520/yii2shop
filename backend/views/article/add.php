<div>
    <ul class="breadcrumb">
        <li class="active">ADD</li>
        <li class="active">UPDATE</li>
    </ul>
</div>
<?php
//表单开始
$form=\yii\bootstrap\ActiveForm::begin();
//文章名称
echo $form->field($model,'name');
//文章简介
echo $form->field($model,'intro')->textarea();
//文章详情
echo $form->field($detail, 'content')->widget(\crazyfd\ueditor\Ueditor::className(),[]) ;
//文章分类
echo $form->field($model,'article_category_id')->dropDownList(\yii\helpers\ArrayHelper::map(\backend\models\ArticleCategory::find()->all(),'id', 'name'),['prompt'=>'请选择分类']);
//文章排序
echo $form->field($model,'sort');
//文章状态
echo $form->field($model,'status',['inline'=>true])->radioList(['1'=>'正常','0'=>'隐藏']);
//提交按钮
echo \yii\bootstrap\Html::submitButton('添加',['class'=>'btn btn-primary']);
//表单结束
\yii\bootstrap\ActiveForm::end();