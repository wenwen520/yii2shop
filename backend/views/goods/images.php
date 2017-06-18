<div>
    <ul class="breadcrumb">
        <li class="active">相册</li>
        <li class="active">Photo</li>
    </ul>
</div>
<?php
use yii\web\JsExpression;
echo \yii\bootstrap\Html::fileInput('test', NULL, ['id' => 'test','style'=>'display:none']);
echo \xj\uploadify\Uploadify::widget([
    'url' => yii\helpers\Url::to(['s-upload']),
    'id' => 'test',
    'csrf' => true,
    'renderTag' => false,
    'jsOptions' => [
        'formData'=>['goods_id'=>$model->id],  //上传文件的同时将goods的ID上传
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
        console.debug(data);
        var html='<tr data-id="'+data.id+'" id="image_'+data.id+'">';
        html += '<td><img src="'+data.fileUrl+'" /></td>';
        html += '<td><button class="del_btn btn btn-danger btn-sm"><span class="glyphicon glyphicon-trash">删除</span></button></td>';
        html += '</tr>';
        $("table").append(html);
    }
}
EOF
        ),
    ]
]);
?>
<table class=" table table-bordered table-responsive">
    <tr>
        <th>图片</th>
        <th>操作</th>
    </tr>
    <?php foreach($images as $image):?>
        <tr id="image_<?=$image->id?>" data-id="<?=$image->id?>">
            <td><?=\yii\bootstrap\Html::img($image->image)?></td>
            <td><?=\yii\bootstrap\Html::button('<span class="glyphicon glyphicon-trash"></span>删除',['class'=>'del_btn btn btn-danger btn-sm'])?></td>
        </tr>
    <?php endforeach;?>
</table>
<?php
//给a标签委托绑定事件，将记录追加到table中
$this->registerJS(new JsExpression(
        <<<JS
        $("table").on('click','.del_btn',function(){
           if(confirm('确定删除图片？')){
               var id=$(this).closest("tr").attr("data-id");
               var data={
                   'id':id,
               }
               $.post('delete',data,function(response){
                   if(response=='success'){
                       //console.debug($('#image_'+id));
                       $('#image_'+id).remove();
                   }
                   alert(response);
               })
           } 
        });
JS

));