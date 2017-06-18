<div>
    <ul class="breadcrumb">
        <li class="active">首页</li>
        <li class="active">Brand</li>
    </ul>
</div>
<?php
//echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-plus-sign"></span>添加品牌',['brand/add'],['class'=>'btn btn-info']);
?>
<table class="table table-hover table-bordered table-striped" style="margin-top: 10px">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>LOGO</th>
        <th>排序</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    <?php foreach($brands as $brand):?>
        <tr>
            <td><?=$brand->id?></td>
            <td><?=$brand->name?></td>
            <td><?=$brand->intro?></td>
            <td><?php echo \yii\bootstrap\Html::img($brand->logo,['width'=>80,'height'=>50])?></td>
            <td><?=$brand->sort?></td>
            <td>
                <?php
                    if($brand->status==1){
                        echo '正常';
                    }elseif($brand->status==0){
                        echo '隐藏';
                    }else{
                        echo '删除';
                    }
                ?>
            </td>
            <td>
                <?php if(Yii::$app->user->can('brand/del')){echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-trash"></span>删除',['brand/del','id'=>$brand->id],['class'=>'btn btn-danger btn-sm']);}?>
                <?php if(Yii::$app->user->can('brand/update')){echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-edit"></span>更新',['brand/update','id'=>$brand->id],['class'=>'btn btn-warning btn-sm']);}?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//分页工具条
echo '<div>';
echo \yii\widgets\LinkPager::widget([
    'pagination'=>$page
]);
echo '</div>';

?>
