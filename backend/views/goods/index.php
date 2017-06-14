<?php
echo \yii\bootstrap\Html::a('新增商品',['goods/add'],['class'=>'btn btn-info']);
//表单开始
$form=\yii\bootstrap\ActiveForm::begin();
echo '<div style="width:300px">'.$form->field($model,'condition').'</div>';
echo \yii\bootstrap\Html::submitButton('搜索',['class'=>'btn btn-primary']);
\yii\bootstrap\ActiveForm::end();
?>
   <!-- <div class="row"><div class="col-md-1">{image}</div><div class="col-md-2">{input}</div></div>-->
<table class="table table-responsive table-bordered">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>货号</th>
        <th>LOGO</th>
        <th>分类</th>
        <th>品牌</th>
        <th>市场价</th>
        <th>商品价</th>
        <th>库存</th>
        <th>在售</th>
        <th>状态</th>
        <th>排序</th>
        <th>上架时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($goods as $good):?>
        <tr>
            <td><?=$good->id?></td>
            <td><?=$good->name?></td>
            <td><?=$good->sn?></td>
            <td><?php echo \yii\bootstrap\Html::img($good->logo,['widht'=>80,'height'=>50])?></td>
            <td><?=$good->cate->name?></td>
            <td><?=$good->brand->name?></td>
            <td><?=$good->market_price?></td>
            <td><?=$good->shop_price?></td>
            <td><?=$good->stock?></td>
            <td><?=\backend\models\Goods::$is_on_sale_options[$good->is_on_sale]?></td>
            <td><?=\backend\models\Goods::$status_options[$good->status]?></td>
            <td><?=$good->sort?></td>
            <td><?=date('Y-m-d G:i:s',$good->create_time)?></td>
            <td>
                <?php echo \yii\bootstrap\Html::a('更新',['goods/update','id'=>$good->id],['class'=>'btn btn-warning btn-sm'])?>
                <?php echo \yii\bootstrap\Html::a('详情',['goods/detail','id'=>$good->id],['class'=>'btn btn-primary btn-sm'])?>
                <?php echo \yii\bootstrap\Html::a('删除',['goods/del','id'=>$good->id],['class'=>'btn btn-danger btn-sm'])?>
                <?php echo \yii\bootstrap\Html::a('相册',['goods/image','id'=>$good->id],['class'=>'btn btn-success btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//显示分页
echo \yii\widgets\LinkPager::widget([
        'pagination'=>$page,
]);