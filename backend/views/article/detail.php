<div>
    <ul class="breadcrumb">
        <li class="active">文章详情</li>
        <li class="active">Detail</li>
    </ul>
</div>
<p><?=$model->article_detail->content?></p>
<?php
echo \yii\bootstrap\Html::a('返回',['article/index'],['class'=>'btn btn-primary']);