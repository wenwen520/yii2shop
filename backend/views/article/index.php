<table class="table table-hover table-bordered table-striped">
    <tr>
        <th>ID</th>
        <th>名称</th>
        <th>简介</th>
        <th>文章分类ID</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach($articles as $article):?>
        <tr>
            <td><?=$article->id?></td>
            <td><?=$article->name?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->article_category->name?></td>
            <td><?=$article->sort?></td>
            <td>
                <?php
                if($article->status==1){
                    echo '正常';
                }elseif($article->status==0){
                    echo '隐藏';
                }else{
                    echo '删除';
                }
                ?>
            </td>
            <td><?=date('Y-m-d G:i:s',$article->create_time)?></td>
            <td>
                <?php echo \yii\bootstrap\Html::a('删除',['article/del','id'=>$article->id],['class'=>'btn btn-danger btn-sm'])?>
                <?php echo \yii\bootstrap\Html::a('更新',['article/update','id'=>$article->id],['class'=>'btn btn-warning btn-sm'])?>
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
echo \yii\bootstrap\Html::a('添加文章',['article/add'],['class'=>'btn btn-info']);
?>