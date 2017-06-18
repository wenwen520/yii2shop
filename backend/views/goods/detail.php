<div>
    <ul class="breadcrumb">
        <li class="active">商品详情</li>
        <li class="active">Detail</li>
    </ul>
</div>
<p><?=$model->content->content?></p>
<?php
echo \yii\bootstrap\Html::a('返回首页',['goods/index'],['class'=>'btn btn-info']);
?>