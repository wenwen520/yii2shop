<?php

namespace frontend\controllers;

use backend\models\Goods;
use backend\models\GoodsCategory;
use frontend\components\SphinxClient;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Locations;
use frontend\models\LoginForm;
use frontend\models\Member;
use frontend\models\Order;
use frontend\models\Order_goods;
use yii\data\Pagination;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class MemberController extends \yii\web\Controller
{
    //加载布局文件
    public $layout;

    //注册
    public function actionRegister()
    {
        $this->layout = 'login';
        $model = new Member(['scenario' => Member::SCENARIO_REGISTER]);
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                //生成注册时间
                $model->created_at = time();
                //密码加密
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password);
                //保存默认状态
                $model->status = 1;
                //保存
                $model->save(false);
                //跳转到登录界面
                return $this->redirect(['member/login']);
            } else {
                var_dump($model->getErrors());
                exit;
            }

        }
        return $this->render('register', ['model' => $model]);
    }

    public function actionIndex()
    {
        $this->layout = 'index';
        return $this->render('index');
    }

    //登录
    public function actionLogin()
    {
        $this->layout = 'login';
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {

            //根据用户名查找对应的用户
            $member = Member::findOne(['username' => $model->username]);
            //生成最后登录时间
            $member->last_login_time = time();
            //生成最后登录IP
            $member->last_login_ip = \Yii::$app->request->getUserIP();
            //保存
            $member->save(false);

            //登录后
            //获取cookie里的数据
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            //判断cookie中有没有数据
            if ($cookie == null) {
                //没有数据 就重置空购物车
                $cart = [];
            } else {
                //有数据  就读出cookie中的value并反序列化
                $cart = unserialize($cookie->value);
            }

            //遍历出cookie里的数据
            foreach ($cart as $goods_id => $amount) {
                //获取登录的用户的id
                $member_id = \Yii::$app->user->identity->getId();
                //根据用户id和商品id找到对应商品
                $cart = Cart::findOne(['member_id' => $member_id, 'goods_id' => $goods_id]);
//                var_dump($cart);exit;
                //判断该用户数据表中是否有同样字段  有使用cookie的数量
                if ($cart) {
                    $cart->amount = $amount;
                    $cart->save();
                } else {
                    $models = new Cart();
                    //数据库没有该条数据  新增
                    $models->member_id = $member_id;
                    $models->goods_id = $goods_id;
                    $models->amount = $amount;
                    $models->save();
                }
                $cookies = \Yii::$app->response->cookies;
                //删除cookie
                $cookies->remove('cart');

            }

            //跳转到用户界面
            return $this->redirect(['member/index']);
        }
        return $this->render('login', ['model' => $model]);
    }

    //退出登录
    public function actionLogout()
    {
        //退出
        \Yii::$app->user->logout();
        //跳转到首页界面
        return $this->redirect(['member/index']);
    }

    //收货地址
    public function actionAddress()
    {
        $this->layout = 'index';
        //新建地址模型对象
        $model = new Address();
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //关联用户
            $model->member_id = \Yii::$app->user->identity->id;
            //保存
            $model->save();
            //返回地址页
            return $this->redirect(['member/address']);
        }
        //查询数据库的所有地址
        $addresses = Address::find()->where(['member_id' => \Yii::$app->user->id])->orderBy('status DESC')->all();
        return $this->render('address', ['addresses' => $addresses, 'model' => $model]);
    }

    //更新地址
    public function actionUpdateAddress($id)
    {
        $this->layout = 'index';
        //通过ID查找对应地址
        $model = Address::findOne(['id' => $id]);
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            //保存
            $model->save();
            //返回地址页
            return $this->redirect(['member/address']);
        }
        //查询数据库的当前登录用户的所有地址

        $addresses = Address::find()->where(['member_id' => \Yii::$app->user->id])->orderBy('status DESC')->all();
        return $this->render('address', ['addresses' => $addresses, 'model' => $model]);

    }

    //删除地址
    public function actionDelAddress($id)
    {
        $this->layout = 'index';
        //通过ID查找对应的地址
        $model = Address::findOne(['id' => $id]);
        //删除
        $model->delete();
        //返回地址页
        return $this->redirect(['member/address']);
    }

    //设置默认地址
    public function actionDefaultAddress($id)
    {
        $this->layout = 'index';
        //通过ID查找对应的地址
        //var_dump($id);exit;
        $model = Address::findOne(['id' => $id]);
        //判断当前登录用户是否已经有默认地址
        $default = Address::findOne(['status' => 1, 'member_id' => \Yii::$app->user->id]);
        if ($default) {
            $default->status = 0;
            $default->save();
        }
        $model->status = 1;
        $model->save();
        return $this->redirect(['member/address']);

    }

    //ajax读取省市区
    public function actionRead()
    {
        $id = \Yii::$app->request->get('id');
        $locations = Locations::find()->where(['parent_id' => $id])->asArray()->all();
        return json_encode($locations);
    }

    //商品列表
    public function actionList($cate_id,$keyword)
    {
        $this->layout = 'goods';


        //新建商品模型对象
        $goods_page = Goods::find();


        $total = $goods_page->count();
        //配置分页
        $page = new Pagination([
            'totalCount' => $total,
            'defaultPageSize' => 8,
        ]);
        //根据ID查找分类对象
        $category = GoodsCategory::findOne(['id' => $cate_id]);
        //获取该分类对象下面的所有子分类
        $categories = GoodsCategory::find()->where(['>', 'lft', $category->lft])->andWhere(['<', 'rgt', $category->rgt])->andWhere(['tree' => $category->tree])->all();
        //获取分类的ID
        $cateIds = ArrayHelper::map($categories, 'id', 'id');
        //查询数据
        $goods = $goods_page->offset($page->offset)->where(['in', 'goods_category_id', $cateIds])->limit($page->limit)->all();
        /*foreach ($categories as $cate){
            //获取每页对应的商品
            $goods[]=$goods_page->offset($page->offset)->where(['goods_category_id'=>$cate->id])->limit($page->limit)->all();
        }*/
        //var_dump($goods);exit;
        return $this->render('list', ['goods' => $goods, 'page' => $page]);
    }

    //商品详情
    public function actionGoods($goods_id)
    {
        $this->layout = 'goods';
        //通过goods_id查找对应的商品
        $goods = Goods::findOne(['id' => $goods_id]);
        return $this->render('goods', ['goods' => $goods]);
    }

    //发送短信验证码
    public function actionSendSms()
    {
        //确保上一次发送短信间隔超过1分钟
        $tel = \Yii::$app->request->post('tel');
        if (!preg_match('/^1[34578]\d{9}$/', $tel)) {
            echo '电话号码不正确';
            exit;
        }
        $code = rand(10000, 99999);
        $result = \Yii::$app->sms->setNum($tel)->setParam(['code' => $code])->send();
        //$result = 1;
        if ($result) {
            //保存当前验证码 session  mysql  redis  不能保存到cookie
            //\Yii::$app->session->set('code',$code);
            //\Yii::$app->session->set('tel_'.$tel,$code);
            \Yii::$app->cache->set('tel_' . $tel, $code, 5 * 60);
            echo 'success' . $code;
        } else {
            echo '发送失败';
        }
    }

    //发送邮件
    public function actionSendEmail()
    {
        //通过邮箱重设密码
        $result = \Yii::$app->mailer->compose()
            ->setFrom('slfstefan@qq.com')//谁的邮箱发出的邮件
            ->setTo('slfstefan@qq.com')//发给谁
            ->setSubject('岁月如歌')//邮件的主题
            //->setTextBody('Plain text content')//邮件的内容text格式
            ->setHtmlBody('<b style="color: lightseagreen">岁月如歌，一曲终了，总有人不愿散场</b>')//邮件的内容 html格式
            ->send();
        var_dump($result);
    }

    //购物车列表
    public function actionCart()
    {
        $this->layout = 'cart';

        if (\Yii::$app->user->isGuest) {
            //游客 购物车商品保存在cookie中
            //找到cookie信息
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            //判断cookie中有无购物车数据
            if ($cookie == null) {
                $cart = [];
            } else {
                //反序列化购物车里的数据
                $cart = unserialize($cookie->value);
            }

            //定义一个空数组保存购物车数据
            $models = [];

            //遍历购物车数据  通过键值对方式  得到id 和 数量
            foreach ($cart as $goods_id => $amount) {
                //通过id去商品表找到该商品
                $goods = Goods::findOne(['id' => $goods_id])->attributes;
                //并获取商品的数量
                $goods['amount'] = $amount;
                //把所有商品放进空数组
                $models[] = $goods;
            }

        } else {
            //根据用户找到他的数据
            $member_id = \Yii::$app->user->identity->getId();
            //根据用户id找到他所有的购物车信息
            $cart = Cart::find()->where(['member_id' => $member_id])->asArray()->all();

            //先定义一个空数组保存
            $models = [];
            //遍历所有的购物车信息
            foreach ($cart as $goods_id => $amount) {
//                var_dump($amount['goods_id']);exit;
                //通过商品id找到商品
                $goods = Goods::findOne(['id' => $amount['goods_id']])->attributes;
//                var_dump($goods);exit;
                //并获取商品的数量
                $goods['amount'] = $amount['amount'];
                //把所有商品放进空数组
                $models[] = $goods;
            }
        }
        return $this->render('cart', ['models' => $models]);//把数量和商品传到视图显示
    }

    //添加商品到购物车
    public function actionAdd_cart()
    {
        $this->layout = 'cart';
        $model = new Cart();
        //接收商品页面发送的post请求的数据  商品id和数量
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        //判断商品是否存在
        $goods = Goods::findOne(['id' => $goods_id]);
        if ($goods == null) {
            throw new NotFoundHttpException('商品不存在');
        }

        //判断用户有无登录
        if (\Yii::$app->user->isGuest) {
            //用户未登录 把购物车的信息 存放在cookie中
            //实例化一个只读cookie
            $cookies = \Yii::$app->request->cookies;
            //获取购物车的数据
            $cookie = $cookies->get('cart');
            //判断cookie中有没有购物车数据
            if ($cookie == null) {
                //购物车没有数据 就初始化
                $cart = [];
            } else {
                //有数据就反序列化后保存到一个变量中
                $cart = unserialize($cookie->value);
            }


            //实例化一个可写cookie将商品id和数量保存到cookie中
            $cookies = \Yii::$app->response->cookies;
            //检查购物车是否有数据  有就累加 没就添加
            if (key_exists($goods->id, $cart)) {
                $cart[$goods_id] += $amount;
            } else {
                $cart[$goods_id] = $amount;
            }

            //new一个cookie组件  把数据保存到cookie里面
            $cookie = new Cookie([
                'name' => 'cart', 'value' => serialize($cart),
//                    'expire'=>time()+3600,//给购物车中的cookie设置有效时间

            ]);
            //将数据添加到cookie中
            $cookies->add($cookie);


        } else {
            //用户已登录  存到数据表中
            $models = new Cart();
            //找到该用户
            $member_id = \Yii::$app->user->identity->getId();
            //找到该商品
            $cart = Cart::findOne(['member_id' => $member_id, 'goods_id' => $goods_id]);
            if ($cart) {//有数量直接修改数量
                $cart->amount += $amount;
                $cart->save();
            } else {
                $models->member_id = $member_id;
                $models->goods_id = $goods_id;
                $models->amount = $amount;
                $models->save();
            }

        }

        return $this->redirect(['member/cart']);

    }

    //修改购物车商品数量
    public function actionEditCart()
    {


        //接收ajax请求发送的数据
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount1 = \Yii::$app->request->post('amount');

        //根据商品id去查数据
        $goods = Goods::findOne(['id' => $goods_id]);
//                var_dump($goods['id']);             var_dump($amount1);exit;


        //判断商品存不存在
        if ($goods == null) {
            throw new NotFoundHttpException('没有该商品');
        }
        //判断是游客还是用户登录


        if (\Yii::$app->user->isGuest) {
            //修改cookie中的数据
            //实例化cookie
            $cookies = \Yii::$app->request->cookies;
            $cookie = $cookies->get('cart');
            //购物车
            $cart = [];
            //判断cookie里有没有商品的id
            if ($cookie == null) {
                //没有id就初始化一个购物车
                $cart = [];
            } else {
                //cookie里有id  将cookie里的id和数量反序列化
                $cart = unserialize($cookie->value);
            }

//            //检测商品的id有没有在购物车里面
//            if(key_exists($goods->id,$cart)){
//                //直接更新该条数据的数量
//                $cart[$goods_id]=$amount1;
//
//            }else{
//                //购物车没有数据就添加一条新的数据
//                $cart[$goods_id]=$amount1;
//            }
            //检查商品数量
            if ($amount1) {//有数量直接修改数量
                $cart[$goods_id] = $amount1;
            } else {
                //没数量的话 检测到该条购物车数据 并删除
                if (key_exists($goods['id'], $cart)) unset($cart[$goods_id]);
            }

            //保存到cookie
            $cookies = \Yii::$app->response->cookies;
            $cookie = new Cookie([
                //将购物车序列化
                'name' => 'cart', 'value' => serialize($cart)]);
            $cookies->add($cookie);


            //

        } else {
            //添加数据之前就查出该用户购物车的商品
            $member = \Yii::$app->user->identity->getId();
            $car = Cart::findOne(['member_id' => $member, 'goods_id' => $goods_id]);
            //检测商品的id有没有在购物车里面
            //检查商品数量
            if ($amount1) {//有数量直接修改数量
                $car->amount = $amount1;
                $car->save();
            } else {
                //没数量的话 检测到该条购物车数据 并删除
                $car->delete();
            }


        }
        return $this->render('cart');
    }

    //订单
    public function actionOrder(){

        $this->layout = 'cart';
        //$this->layout = false;
        $order_goods = new Order_goods();
        $model = new Order();

        //判断有无登录用户  登录才能结算
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['member/login']);
        }else{
            //显示该用户购物车里的商品

            //获取用户id
            $member_id= \Yii::$app->user->identity->getId();
            //根据用户的id去购物车查所有数据
            $cart = Cart::find()->where(['member_id'=>$member_id])->asArray()->all();
            $models = [];
            //遍历每一条购物车信息

            foreach($cart as $car){
                //再根据购物车商品的id去goods表查询该商品的详情
                $goods = Goods::findOne(['id'=>$car['goods_id']])->attributes;
                //把购物车的数量放进goods里

                $goods['amount']= $car['amount'];
                //把所有商品放进一个数组里面
                $models[]=$goods;
            }
//            var_dump($car);exit;


            //收货人信息  根据用户id查地址表
            $address = Address::findAll(['member_id'=>$member_id]);
//            var_dump($address);exit;



            return $this->render('order',['models'=>$models,'address'=>$address]);
        }


    }

    //提交订单
    public function actionSubmit_order(){

        $this->layout='cart';
        $order = new Order();


        $member_id = \Yii::$app->user->identity->getId();
        //接收订单页面传输的地址数据、运费数据、总金额和收货信息
        $detail = \Yii::$app->request->post();


        //遍历支付方式
        $payment_detail = '';
        foreach (Order::$payment_goods as $payment) {
            //如果传过来的支付id和静态变量里某条id一致 就把那条数据赋值给一个变量保存
            if ($payment['payment_id'] == $detail['payment']) {
                $payment_detail = $payment;

            }
        }

        $delivery_detail='';
        //遍历送货方式
        foreach(Order::$delivery_goods as $delivery) {
            //如果传过来的送货id和静态变量里某条id一致 就把那条数据赋值给一个变量保存
            if ($delivery['delivery_id'] == $detail['delivery']) {
                $delivery_detail = $delivery;
            }
        }




        $cart = Cart::find()->where(['member_id'=>$member_id])->all();
        //开启事务
        $transaction = \Yii::$app->db->beginTransaction();
        //捕获异常
        try {
            //根据地址id去查询地址详情 操作订单主表
            $address =Address::findOne(['id'=>$detail['address']]);
            $order->member_id =$member_id;
            $order->name=$address->name;
            $order->province=$address->province_id;
            $order->city=$address->city_id;
            $order->area=$address->area_id;
            $order->address=$address->detail_address;
            $order->tel=$address->phone;
            $order->create_time = time();
            $order->status = 1;
            $order->total = $detail['total'];
            $order->payment_id = $payment_detail['payment_id'];
            $order->payment_name = $payment_detail['payment_name'];
            $order->delivery_id = $delivery_detail['delivery_id'];
            $order->delivery_name = $delivery_detail['delivery_name'];
            $order->delivery_price = $delivery_detail['delivery_price'];
            if(!$order->save()){
                throw new Exception('订单主表保存失败!');
            }
            //操作订单商品表
            foreach ($cart as $car) {
                $sp = Goods::findOne(['id' => $car['goods_id']]);
                $order_goods = new Order_goods();
                //判断商品库存
                if ($sp->stock < $car['amount']) {
                    throw new Exception('库存不足');
                }
                $order_id = $order->id;
                $order_goods->order_id = $order_id;
                $order_goods->goods_name = $sp->name;
                $order_goods->goods_id = $sp->id;
                $order_goods->logo = $sp->logo;
                $order_goods->price = $sp->shop_price;
                $order_goods->amount = $car['amount'];
                $order_goods->total = $car['amount'] * $sp->shop_price;
                $sp->stock -= $car['amount'];
                if(!$sp->save() || !$order_goods->save()){
                    throw new Exception('订单商品表操作失败或商品库存保存失败！');
                }
            }
            //提交事务
            $transaction->commit();
            //执行成功 清除购物车
            foreach($cart as $car){
                $deleteCart = Cart::deleteAll(['member_id'=>$member_id,'goods_id'=>$car['goods_id']]);
                if(!$deleteCart){
                    throw new Exception('购物车清空失败');
                }
            }


        }catch(Exception $e){
            $transaction->rollBack();
        }
        return $this->render('submit');
    }

    //测试分词搜索
    public function actionTest()
    {
//        $cl = new SphinxClient();
//        $cl->SetServer ( '127.0.0.1', 9312);
//        $cl->SetConnectTimeout ( 10 );
//        $cl->SetArrayResult ( true );
//        $cl->SetMatchMode ( SPH_MATCH_ALL);
//        $cl->SetLimits(0, 1000);
//        $info = '索尼电视';
//        $res = $cl->Query($info, 'goods');//shopstore_search
//
//        var_dump($res);
    }


        public function actionSearch(){

            $this->layout='goods';
            $model = Goods::find();

            //分词搜索
        if($keyword= \Yii::$app->request->post('keyword')){
//            var_dump($keyword);exit;
            $cl = new SphinxClient();
            $cl->SetServer ( '127.0.0.1', 9312);
            $cl->SetConnectTimeout ( 10 );
            $cl->SetArrayResult ( true );
            $cl->SetMatchMode ( SPH_MATCH_ALL);
            $cl->SetLimits(0, 1000);
            $res = $cl->Query($keyword, 'goods');
//            var_dump($res);exit;
            if(!isset($res['matches'])){
                $model->where(['id'=>0]);

            }else{
                //获取商品id

                $ids = ArrayHelper::map($res['matches'],'id','id');
                $model->where(['in','id',$ids]);
            }


        }


            $page = new Pagination([
                'totalCount'=>$model->count(),
                'defaultPageSize'=>5
            ]);

            $goods = $model->offset($page->offset)->limit($page->limit)->all();
            $keyword=array_keys($res['words']);
            $options = array(
                'before_match'=>'<span style="color:red;">',
                'after_match'=>'</span>',
                'chunk_separator'=>'...',
                'limit'=>80,
            );
            foreach($goods as $index=>$item){
                $name = $cl->BuildExcerpts([$item->name],'goods',implode(',',$keyword),$options);
                $goods[$index]->name = $name[0];
            }



            return $this->render('list',['goods'=>$goods,'page'=>$page]);
        }







}
