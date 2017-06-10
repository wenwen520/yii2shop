<?php
echo \yii\bootstrap\Html::a('添加文章分类',['article_category/add'],['class'=>'btn btn-info']);
?>
<table class="table table-hover table-bordered table-striped">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>排序</th>
        <th>状态</th>
        <th>类型</th>
        <th>操作</th>
    </tr>
    <?php foreach($article_categorys as $article_category):?>
        <tr>
            <td><?=$article_category->id?></td>
            <td><?=$article_category->name?></td>
            <td><?=$article_category->intro?></td>
            <td><?=$article_category->sort?></td>
            <td>
                <?php
                if($article_category->status==1){
                    echo '正常';
                }elseif($article_category->status==0){
                    echo '隐藏';
                }else{
                    echo '删除';
                }
                ?>
            </td>
            <td>
                <?php
                    if($article_category->is_help==1){
                        echo '是';
                    }else{
                        echo '否';
                    }
                ?>
            </td>
            <td>
                <?php echo \yii\bootstrap\Html::a('删除',['article_category/del','id'=>$article_category->id],['class'=>'btn btn-danger btn-sm'])?>
                <?php echo \yii\bootstrap\Html::a('更新',['article_category/update','id'=>$article_category->id],['class'=>'btn btn-warning btn-sm'])?>
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
