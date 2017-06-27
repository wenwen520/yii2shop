<table class="table table-bordered table-responsive">
    <thead>
    <tr>
        <th>ID</th>
        <th>用户ID</th>
        <th>用户名</th>
        <th>所在省份</th>
        <th>所在城市</th>
        <th>所在区县</th>
        <th>详细地址</th>
        <th>联系电话</th>
        <th>快递ID</th>
        <th>快递名称</th>
        <th>快递价格</th>
        <th>支付ID</th>
        <th>支付方式</th>
        <th>货款小计</th>
        <th>发货状态</th>
        <th>第三方支付交易号</th>
        <th>订单生成时间</th>
        <th>操作</th>
    </tr>
    </thead>
    <?php foreach($models as $model):?>
        <tbody>
    <tr>
        <td><?=$model->id?></td>
        <td><?=$model->member_id?></td>
        <td><?=$model->name?></td>
        <td><?=$model->province?></td>
        <td><?=$model->city?></td>
        <td><?=$model->area?></td>
        <td><?=$model->address?></td>
        <td><?=$model->tel?></td>
        <td><?=$model->delivery_id?></td>
        <td><?=$model->delivery_name?></td>
        <td><?=$model->delivery_price?></td>
        <td><?=$model->payment_id?></td>
        <td><?=$model->payment_name?></td>
        <td><?=$model->total?></td>
        <td><?=\backend\models\Order::$status[$model->status]?></td>
        <td><?=$model->trade_no?></td>
        <td><?=date('Y-m-d G:i:s',$model->create_time)?></td>
        <td><?=
            (\backend\models\Order::$status[$model->status])==0?' ':\yii\bootstrap\Html::a('发货',['order/deliver_goods','id'=>$model->id],['class'=>'btn btn-info btn-sm'])?>

        </td>
    </tr>
        </tbody>
    <?php endforeach;?>




</table>
<?php
/**
 * @var $this \yii\web\View
 */
$this->registerCssFile('//cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css');
$this->registerJsFile('//cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerJs('$(".table").DataTable({

});');