<div style="clear:both;"></div>

<?php
/**
 * @var $this Yii\web\view
 */
$this->registerJsFile('@web/js/cart2.js',['depends'=>\yii\web\JqueryAsset::className()]);
$this->registerCssFile('@web/style/fillin.css');
$this->registerCssFile('@web/style/base.css');


?>

<!-- 页面头部 start -->
<div class="header w990 bc mt15">
    <div class="logo w990">
        <h2 class="fl"><a href="index.html"><?=\yii\helpers\Html::img('@web/images/logo.png')?></a></h2>
        <div class="flow fr flow2">
            <ul>
                <li>1.我的购物车</li>
                <li class="cur">2.填写核对订单信息</li>
                <li>3.成功提交订单</li>
            </ul>
        </div>
    </div>
</div>
<!-- 页面头部 end -->

<div style="clear:both;"></div>

<!-- 主体部分 start -->
<div class="fillin w990 bc mt15">
    <div class="fillin_hd">
        <h2>填写并核对订单信息</h2>
    </div>

    <div class="fillin_bd">
        <!-- 收货人信息  start-->
        <div class="address">
            <h3>收货人信息</h3>
            <div class="address_info">
                <p>
                    <?php foreach($address as $k=>$area):?>
                    <tr class="<?=$k==0?'cur':""?>">
                    <input type="radio" name="address_id" value="<?=$area->id?>"/><?=$area->name.$area->phone.'   '.$area->province->name.$area->city->name.$area->area->name.$area->detail_address ?>
                </p>
                    <?php endforeach;?>
            </div>


        </div>
        <!-- 收货人信息  end-->

        <!-- 配送方式 start -->
        <div class="delivery">
            <h3>送货方式 </h3>


            <div class="delivery_select">
                <table>
                    <thead>
                    <tr>
                        <th class="col1">送货方式</th>
                        <th class="col2">运费</th>
                        <th class="col3">运费标准</th>
                    </tr>
                    </thead>
                    <tbody>
                    <li v-for="(delivery,index) in DeliveryList">

                    </li>

                    <?php foreach(\frontend\models\Order::$delivery_goods as $k=>$delivery ):?>

                    <tr class="<?=$k==0?'cur':""?>" >
                        <td class="yunfei">
                            <input type="radio" name="delivery" <?=$k==0?'checked':""?> value="<?=$delivery['delivery_id']?>"/>
                            <?=$delivery['delivery_name'];?>
                        </td>
                        <td class="yf_money"><?=$delivery['delivery_price']?></td>
                        <td>每张订单不满499.00元,运费加5.00元</td>

                    </tr>
                    <?php endforeach;?>

                    </tbody>
                </table>

            </div>
        </div>
        <!-- 配送方式 end -->

        <!-- 支付方式  start-->
        <div class="pay">
            <h3>支付方式 </h3>


            <div class="pay_select">
                <table>
                    <?php foreach(\frontend\models\Order::$payment_goods as $k=>$payment):?>
                    <tr class="<?=$k==0?'cur':''?>">
                        <td class="col1"><input type="radio" name="pay" value="<?=$payment['payment_id']?>"/><?=$payment['payment_name']?></td>
                        <td class="col2">送货上门后再收款，支持现金、POS机刷卡、支票支付</td>
                    </tr>
                    <?php endforeach;?>
                </table>

            </div>
        </div>
        <!-- 支付方式  end-->

        <!-- 发票信息 start-->
        <div class="receipt none">
            <h3>发票信息 </h3>


            <div class="receipt_select ">
                <form action="">
                    <ul>
                        <li>
                            <label for="">发票抬头：</label>
                            <input type="radio" name="type" checked="checked" class="personal" />个人
                            <input type="radio" name="type" class="company"/>单位
                            <input type="text" class="txt company_input" disabled="disabled" />
                        </li>
                        <li>
                            <label for="">发票内容：</label>
                            <input type="radio" name="content" checked="checked" />明细
                            <input type="radio" name="content" />办公用品
                            <input type="radio" name="content" />体育休闲
                            <input type="radio" name="content" />耗材
                        </li>
                    </ul>
                </form>

            </div>
        </div>
        <!-- 发票信息 end-->

        <!-- 商品清单 start -->
        <div class="goods">
            <h3>商品清单</h3>
            <table>
                <thead>
                <tr>
                    <th class="col1">商品</th>
                    <th class="col3">价格</th>
                    <th class="col4">数量</th>
                    <th class="col5">小计</th>
                </tr>
                </thead>
                <tbody>

                <?php foreach ($models as $model) :
                    ?>
                <tr   class="goods_total_money" data-total_money="<?=$model['shop_price']*$model['amount']?>">
                    <td class="col1"><a href=""><?=\yii\helpers\Html::img(Yii::$app->params['imageDomain'].$model['logo'])?>
                        </a>  <strong><a href="">
                    <?=$model['name']?></a></strong></td>

                    <td class="col3"><?=$model['shop_price']?></td>


                    <td class="col4"><?=$model['amount']?></td>


                    <td class="col5"><span><?=$model['shop_price']*$model['amount']?></span></td>
                </tr>
                <?php endforeach;?>

                </tbody>
                <tfoot>
                <tr>
                    <td colspan="5">
                        <ul>
                            <li>

                                <span>4 件商品，总金额：</span>
                                ￥<em  class="goods_count_money"></em>

                            </li>
                            <li>
                                <span>运费：</span>
                                <em class="yf"></em>
                            </li>
                        </ul>
                    </td>
                </tr>
                </tfoot>
            </table>
        </div>
        <!-- 商品清单 end -->

    </div>

    <div class="fillin_ft">
        <a href="javascript:;" class="submit_order"></a>
        <p>应付总额：￥<strong class="count"></strong></p>
    </div>
</div>
<!-- 主体部分 end -->
<?php
/**
  * var @this yii\web\view
 *
  */
$token = Yii::$app->request->csrfToken;
$url =\yii\helpers\Url::to(['member/submit_order']);
$this->registerJs(
    <<<JS

        //给运费的选项框td绑定一个change事件
          var yf;
        $('.yunfei').change(function(){
            //当运费选项变化时 向上找到当前运费框的金额
                var yf_money= $(this).closest('tr').find('.yf_money').text();
                  //把运费赋值给运费框
                  $('.yf').text(yf_money);
                   yf=parseInt(yf_money.substring(1,yf_money.length));

                   //应付总额 找到商品总金额和运费  加起来  赋值给应付总额框
                $(function(){
                    var goods_money =  parseInt($('.goods_count_money').text());
                   var count = parseInt(yf+goods_money);
                   $('.count').text(count);
                    })

        });

        //刷新页面自动获取默认选中的运费

        $(function(){
            var yf_money= $('input[name="delivery"]:checked').closest('tr').find('.yf_money').text();
             $('.yf').text(yf_money);
             yf=parseInt(yf_money.substring(1,yf_money.length));
        });




        $(function(){
                var count = 0;
                //遍历每个tr
                $('.goods_total_money').each(function(i,v){
                //找到每个tr的小计金额  转成整形 加起来
                count+=parseInt($(v).attr('data-total_money'));

             });
                     //把商品总金额赋值给商品总金额框
                $('.goods_count_money').text(count);
        });

            //应付总额 找到商品总金额和运费  加起来  赋值给应付总额框
            $(function(){
                var goods_money =  parseInt($('.goods_count_money').text());
               var count = parseInt(yf+goods_money);
               $('.count').text(count);
                });





                 $('.submit_order').click(function(){
                     //获取运费
                     var delivery = $('.delivery_select input:checked').val();
                       //获取支付方式
                       var payment = $('.pay_select input:checked').val();
                       //获取收货信息
                       var address = $('.address_info input:checked').val();
                       //获取总金额
                       var total= $('.count').text();
                       //console.debug(total);
                       // console.debug(address);
                       //console.debug(delivery);
                       //console.debug(payment);
                       //return;

                         //ajax 发送请求和数据到后台

                          $.post("$url",{delivery:delivery,payment:payment,address:address,total:total,"_csrf-frontend":"$token"});



                });

JS


);

