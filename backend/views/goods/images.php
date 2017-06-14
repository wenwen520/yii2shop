<table class=" table table-bordered table-responsive">
    <tr>
        <th>ID</th>
        <th>图片</th>
    </tr>
    <?php foreach($images as $image):?>
        <tr>
            <td><?=$image->id?></td>
            <td><?=\yii\bootstrap\Html::img($image->image,['width'=>80,'height'=>50])?></td>
        </tr>
    <?php endforeach;?>
</table>