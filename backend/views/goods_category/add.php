<div>
    <ul class="breadcrumb">
        <li class="active">ADD</li>
        <li class="active">UPDATE</li>
    </ul>
</div>
<?php
//表单开始
$form = \yii\bootstrap\ActiveForm::begin();
//名称
echo $form->field($model,'name');
//上级分类ID
echo $form->field($model,'parent_id')->hiddenInput();
//用ztree展示分类
echo '<ul id="treeDemo" class="ztree"></ul>';
//简介
echo $form->field($model,'intro')->textarea();
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>'btn btn-info']);
//表单结束
\yii\bootstrap\ActiveForm::end();
//加载静态资源
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
//将数据转换成json 格式
$zNodes = \yii\helpers\Json::encode($categorys);
$js = new \yii\web\JsExpression(
    <<<JS
var zTreeObj;
    // zTree 的参数配置，深入使用请参考 API 文档（setting 配置详解）
    var setting = {
        data: {
            simpleData: {
                enable: true,
                idKey: "id",
                pIdKey: "parent_id",
                rootPId: 0
            }
        },
        callback: {
		    onClick: function(event, treeId, treeNode) {
                //console.log(treeNode.id);
                //将选中节点的id赋值给表单parent_id
                $("#goodscategory-parent_id").val(treeNode.id);
            }
	    }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = {$zNodes};
    
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    //展开所有的节点
    zTreeObj.expandAll(true);
    //获取当前节点的父节点
    var node = zTreeObj.getNodeByParam("id", $("#goodscategory-parent_id").val(), null);
    //选中当前节点
    zTreeObj.selectNode(node);
JS
);
$this->registerJS($js);
?>