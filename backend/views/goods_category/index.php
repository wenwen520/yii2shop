<div>
    <ul class="breadcrumb">
        <li class="active">首页</li>
        <li class="active">GoodsCategory</li>
    </ul>
</div>
<?php
echo \yii\bootstrap\Html::a('<span class="glyphicon glyphicon-plus-sign"></span>添加',['goods_category/add'],['class'=>'btn btn-info']);
?>
<table class="cate table table-bordered table-responsive" style="margin-top: 10px">
    <tr>
        <th>ID</th>
        <!--<th>树ID</th>
        <th>左值</th>
        <th>右值</th>
        <th>层级</th>-->
        <th>简介</th>
        <th>上级分类</th>
        <th>名称</th>
        <th>操作</th>
    </tr>
    <?php foreach($categorys as $category):?>
        <tr data-tree="<?=$category->tree?>" data-lft="<?=$category->lft?>" data-rgt="<?=$category->rgt?>">
            <td><?=$category->id?></td>
            <!--<td><?/*=$category->tree*/?></td>
            <td><?/*=$category->lft*/?></td>
            <td><?/*=$category->rgt*/?></td>
            <td><?/*=$category->depth*/?></td>-->
            <td><?=$category->intro?></td>
            <td><?=$category->parent_id?></td>
            <td><?=str_repeat('－',$category->depth).$category->name?><span class="menu glyphicon glyphicon-triangle-bottom" style="float:right;"></span></td>
            <td>
                <?=\yii\bootstrap\Html::a('修改',['goods_category/update','id'=>$category->id],['class'=>'btn btn-warning btn-sm'])?>
                <?=\yii\bootstrap\Html::a('删除',['goods_category/del','id'=>$category->id],['class'=>'btn btn-danger btn-sm'])?>
            </td>
        </tr>
    <?php endforeach;?>
</table>
<?php
$js=new \yii\web\JsExpression(
        <<<JS
    $('.menu').on('click',function(){
        //找到当前行tr
        var tr=$(this).closest('tr');
        //获取当前行的tree
        var tree=parseInt(tr.attr('data-tree'));
        //获取当前行的lft
        var lft=parseInt(tr.attr('data-lft'));
        //获取当前行的右值
        var rgt=parseInt(tr.attr('data-rgt'));
        //显示还是隐藏
        var show=$(this).hasClass('glyphicon-triangle-top');
        //切换图标
        $(this).toggleClass('glyphicon-triangle-top');
        $(this).toggleClass('glyphicon-triangle-bottom');
        //遍历找出子类
        $('.cate tr').each(function(){
            //console.debug($(this));
            //console.debug($(this).attr('data-tree'));
            if(parseInt($(this).attr('data-tree'))==tree && parseInt($(this).attr('data-lft'))>lft && parseInt($(this).attr('data-rgt'))<rgt){
                show?$(this).show():$(this).hide();
            }
        })
    });
JS

);
//加载JS
$this->registerJS($js);