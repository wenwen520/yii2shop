<?php
//表单开始
$form=\yii\bootstrap\ActiveForm::begin();
//商品名称
echo $form->field($model,'name');
//logo
echo $form->field($model,'imgFile')->fileInput();
//图片回显
if($model->logo){echo \yii\bootstrap\Html::img($model->logo,['width'=>80,'height'=>50]);}
//隐藏域传商品的分类ID
echo $form->field($model,'goods_category_id')->hiddenInput();
//商品分类
echo '<ul id="treeDemo" class="ztree"></ul>';
//品牌分类
echo $form->field($model,'brand_id')->dropDownList(\yii\helpers\ArrayHelper::map($brands,'id','name'),['prompt'=>'请选择品牌']);
//市场价格
echo $form->field($model,'market_price');
//商品价格
echo $form->field($model,'shop_price');
//库存
echo $form->field($model,'stock');
//在售
echo $form->field($model,'is_on_sale',['inline'=>true])->radioList(\backend\models\Goods::$is_on_sale_options);
//状态
echo $form->field($model,'status',['inline'=>true])->radioList(\backend\models\Goods::$status_options);
//排序
echo $form->field($model,'sort');
//商品详情
echo $form->field($detail, 'content')->widget(\crazyfd\ueditor\Ueditor::className(),[]) ;
//提交按钮
echo \yii\bootstrap\Html::submitButton('提交',['class'=>"btn btn-info"]);
//表单结束
\yii\bootstrap\ActiveForm::end();
//加载静态文件
$this->registerCssFile('@web/zTree/css/zTreeStyle/zTreeStyle.css');
$this->registerJsFile('@web/zTree/js/jquery.ztree.core.js',['depends'=>\yii\web\JqueryAsset::className()]);
//将数据装换成json格式
$zNodes=\yii\helpers\Json::encode($categorys);
$js=new \yii\web\JsExpression(
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
                //将选中节点的id赋值给表单goods_category_id
                $("#goods-goods_category_id").val(treeNode.id);
            }
	    }
    };
    // zTree 的数据属性，深入使用请参考 API 文档（zTreeNode 节点数据详解）
    var zNodes = {$zNodes};
    
    zTreeObj = $.fn.zTree.init($("#treeDemo"), setting, zNodes);
    //展开所有的节点
    zTreeObj.expandAll(true);
    //获取当前节点
    var node = zTreeObj.getNodeByParam("id", $("#goods-goods_category_id").val(), null);
    //选中当前节点的父节点
    zTreeObj.selectNode(node);
JS

);
//加载js
$this->registerJS($js);
?>