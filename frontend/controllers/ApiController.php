<?php
namespace frontend\controllers;

use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\GoodsCategory;
use frontend\models\Address;
use frontend\models\Cart;
use frontend\models\Goods;
use frontend\models\Member;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Cookie;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;

class ApiController extends  Controller
{


    public $enableCsrfValidation = false;

    public function init()
    {
        \Yii::$app->response->format = Response::FORMAT_JSON;
        parent::init();
    }


    //接口测试  用户注册
    public function actionUserRegister()
    {

        $request = \Yii::$app->request;

        if ($request->isPost) {
            $member = new Member();
            $member->scenario=Member::SCENARIO_API_REGISTER;
            $member->username = $request->post('username');
            //密码加密
            $password_hash = \Yii::$app->security->generatePasswordHash($request->post('password'));
            $member->password_hash =$password_hash;
            $member->email = $request->post('email');
            $member->created_at = time();
            $member->status = 1;
            $member->code=$request->post('code');
            if ($member->validate()) {
                $member->save();
                return ['status' => '1', 'msg' => '', 'data' => $member->toArray()];
            }
            //验证失败
            return ['status' => '-1', 'msg' => $member->getErrors()];
        }
        return ['status' => '-1', 'msg' => '请使用post提交'];

    }


    //登录
    public function actionLogin()
    {
       $request = \Yii::$app->request;
        if($request->isPost){
            //获取用户信息
            $user = Member::findOne(['username'=>$request->post('username')]);
            //var_dump($user);exit;
            //验证用户名和密码
            if($user && \Yii::$app->security->validatePassword($request->post('password'),$user->password_hash)){
                //登录

                \Yii::$app->user->login($user);
                return ['status'=>'1','msg'=>'登录成功'];
            }
            return ['status'=>'-1','msg'=>'密码或帐号错误'];
        }
        return ['status'=>'-1','msg'=>'请使用post请求'];
    }



    //获取当前用户登录信息
    public function actionUser()
    {
        if(\Yii::$app->user->isGuest){
            return ['status'=>'-1','msg'=>'请先登录'];
        }
        return ['status'=>'1','msg'=>'','data'=>\Yii::$app->user->identity->toArray()];
    }

    //修改密码
    public function actionPwd()
    {
        $request = \Yii::$app->request;
        if($request->isPost){
            $member = new Member();
            //获取用户信息
            $user = Member::findOne(['username'=>$request->post('username')]);
            //验证用户和密码
            if($user && \Yii::$app->security->validatePassword($request->post('password'),$user->password_hash)){
                //验证两次新密码是否一致
                if(!empty($request->post('new_password')) && !empty($request->post('re_password'))
                    && $request->post('new_password')==$request->post('re_password')
                ){
                    $re_password = $request->post('re_password');
                    //接收用户修改的密码  密码加密
                    $newpassword = \Yii::$app->security->generatePasswordHash($re_password);
                    $member->password_hash = $newpassword;
                    $member->save();
                    return ['status'=>'1','msg'=>'修改密码成功'];
                }
                return ['status'=>'-1','msg'=>'两次新密码不能为空或不一致'];


            }
            return ['status'=>'-1','msg'=>'用户名或密码错误'];
        }
    }

    //添加地址
    public function actionAddAddress(){
        $request = \Yii::$app->request;
        $address = new Address();

        if(!\Yii::$app->user->isGuest){
            if($request->isPost){
                   //不是游客  获取登录用户的id
                $member_id = \Yii::$app->user->identity->getId();
                $address->member_id = $member_id;
                $address->name=$request->post('name');
                $address->province_id = $request->post('province_id');
                $address->city_id = $request->post('city_id');
                $address->area_id = $request->post('area_id');
                $address->detail_address = $request->post('detail_address');
                $address->phone = $request->post('phone');
                $address->status= 0;
                if($address->save()){
                    return ['status'=>'1','msg'=>'添加地址成功'];

                }

                return ['status'=>'-1','msg'=>$address->getErrors()];
            }
            return ['status'=>'-1','msg'=>'请用post传参'];

        }
        return ['status'=>'-1','msg'=>'请先登录'];

    }

    //地址列表
    public function actionIndexAddress(){

        if(!\Yii::$app->user->isGuest){
            $member_id=\Yii::$app->user->identity->getId();
            $address = Address::find()->where(['member_id'=>$member_id])->all();
            return ['status'=>'1','msg'=>'','data'=>$address];
        }
    }



    //修改地址
    public function actionEditAddress(){
        $request=\Yii::$app->request;

            if($request->isPost){
                //接收地址的id  查出该条数据 并修改
                $address = Address::findOne(['id'=>$request->post('id')]);
                if($address){
                    $address->name=$request->post('name');
                    $address->province_id=$request->post('province_id');
                    $address->city_id=$request->post('city_id');
                    $address->area_id=$request->post('area_id');
                    $address->detail_address=$request->post('detail_address');
                    $address->phone=$request->post('phone');
                    $address->status=$request->post('status');
                    if($address->save()){
                        return ['status'=>'1','data'=>'修改地址成功'];
                    }
                    return ['status'=>'-1','data'=>$address->getErrors()];
                }
                return ['status'=>'-1','msg'=>'没有这条地址信息'];
            }
    }


    //删除地址
    public function actionDeleteAddress(){
        $request = \Yii::$app->request;
        if($request->isPost){
            //查出该条id的地址  删除
            $delete  = Address::findOne(['id'=>$request->post('id')])->delete();
            if($delete){
                return ['status'=>'1','msg'=>'删除地址成功'];
            }
        }
        return ['status'=>'-1','msg'=>'请用post传参'];

    }


    //商品分类列表
    public function actionIndexGoodsCategory(){
       $goods_category = GoodsCategory::find()->all();
        return ['status'=>'1','msg'=>'','data'=>$goods_category];

    }

    //商品子分类
    public function actionSonGoodsCategory(){
        $request= \Yii::$app->request;
        if($request->isPost){
            $son = GoodsCategory::find()->where(['parent_id'=>$request->post('id')])->all();
            return ['status'=>'1','data'=>$son];
        }
        return ['status'=>'-1','msg'=>'请用post传参'];
    }


    //商品父分类
    public function actionParentGoodsCategory(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $goods_category = GoodsCategory::findOne(['id'=>$request->post('id')]);
            $parent= GoodsCategory::findOne(['id'=>$goods_category['parent_id']]);
            return ['status'=>'1','data'=>$parent];
        }
        return ['status'=>'-1','msg'=>'请用post传参'];
    }


    //获取某分类下的所有商品
    public function actionCategory_goods(){
        $query = Goods::find();
        $goods_category_id = \Yii::$app->request->get('goods_category_id');
            $cate = GoodsCategory::findOne(['id'=>$goods_category_id]);
        if($cate==null){
            return ['status'=>'-1','msg'=>'该分类不存在'];
        }
        switch ($cate->depth){
            case 2://三级分类
                $query->andWhere(['goods_category_id'=>$goods_category_id]);
                break;
            case 1://二级分类
                $ids = ArrayHelper::map($cate->children,'id','id');
                $query->andWhere(['in','goods_category_id',$ids]);
                break;
            case 0;//一级分类
                $ids = ArrayHelper::map($cate->leaves()->asArray()->all(),'id','id');
                $query->andWhere(['in','goods_category_id',$ids]);
                break;

        }

    }

    //获取某品牌下面的所有商品
    public function actionBrand_goods(){
        $brand_id =\Yii::$app->request->get('brand_id');
        if($brand_id){
            $goods =Goods::find()->where(['brand_id'=>$brand_id])->all();
            return ['status'=>'1','data'=>$goods];
        }
        return ['status'=>'-1','msg'=>'请选择品牌'];

    }

    //文章分类列表
    public function actionArticle_category(){
        return ['status'=>'1','msg'=>'','data'=>ArticleCategory::find()->all()];
    }



    //-获取某分类下面的所有文章
    public function actionArticle(){
        $article_category_id = \Yii::$app->request->get('article_category_id');
        if($article_category_id){
            $article =  Article::findAll(['article_category_id'=>$article_category_id]);
            return ['status'=>'1','msg'=>'','data'=>$article];
        }
       return ['status'=>'-1','msg'=>'请选择文章分类'];

    }


    //-获取某文章所属分类
    public function actionArticle_blong_category(){
        $article_id = \Yii::$app->request->get('article_id');
        if($article_id){
            $article = Article::findOne(['id'=>$article_id]);
            $category = ArticleCategory::findOne(['id'=>$article['article_category_id']]);
            return ['status'=>'1','msg'=>'','data'=>['name'=>$category['name'],'id'=>$category['id']]];
        }
        return ['status'=>'.1','msg'=>'请填写文章id'];
    }


    //-添加商品到购物车
    public function actionAdd_goods_cart(){
        $request = \Yii::$app->request;
        //接收商品页面发送的post请求的数据  商品id和数量
        $goods_id = \Yii::$app->request->post('goods_id');
        $amount = \Yii::$app->request->post('amount');
        //判断商品是否存在
        $goods = Goods::findOne(['id' => $goods_id]);
//        if($goods == null){
//            throw new NotFoundHttpException('没有找到该商品');
//        }

        if(!\Yii::$app->user->isGuest){
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
                $models->member_id = \Yii::$app->user->identity->getId();
                $models->goods_id = $request->post('goods_id');
                $models->amount = $request->post('amount');
                $models->save();
                return ['status'=>'1','msg','data'=>'保存到购物车成功'];
            }


        }else{
            //游客添加商品到cookie中
            //用户未登录 把购物车的信息 存放在cookie中
            //实例化一个只读cookie
            $cookies=\Yii::$app->request->cookies;
            $cookie =$cookies->get('cart');
            if($cookie==null){
                //初始化一个新的购物车
                $cart=[];
            }else{
                //购物车有数据 反序列化购物车里的数据
                $cart = unserialize($cookie->value);
            }

            $cookies = \Yii::$app->response->cookies;
          //实例化一个可写cookie 判断购物车是否有数据
            if(key_exists($goods->id,$cart)){
                    $cart[$goods_id]+=$amount;
            }else{
                $cart[$goods_id]=$amount;
            }

            $cookie =new Cookie([
                'name'=>'cart','value'=>serialize($cart),
            ]);
            $cookies->add($cookie);
            return ['status'=>'1','data'=>$cookie];
        }
    }

    //-删除购物车某商品
    public function actionDelete_cart(){
        if(!\Yii::$app->user->isGuest){
            $member_id = \Yii::$app->user->identity->getId();
            $goods_id = \Yii::$app->request->get('goods_id');
            $delete = Cart::findOne(['member_id'=>$member_id,'goods_id'=>$goods_id])->delete();
            if($delete){
                return ['status'=>'1','msg'=>'','data'=>'删除购物车商品成功'];
            }
        }
        return ['status'=>'-1','msg'=>'请登录'];
    }

    //-获取购物车所有商品
    public function actionCart(){
        if(!\Yii::$app->user->isGuest){
            $member_id = \Yii::$app->user->identity->getId();
            $cart = Cart::findAll(['member_id'=>$member_id]);
            if($cart) {
                return ['status' => '1', 'msg' => '', 'data' => $cart];
            }
        }


        return ['status'=>'-1','msg'=>'请登录'];
    }


        //验证码
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }


        //文件上传
    public function actionUpload(){
        $img = UploadedFile::getInstanceByName('img');
        if($img){
            $fileName='/images/'.uniqid().'.'.$img->extension;
            $result = $img->saveAs(\Yii::getAlias('@webroot').$fileName,0);
            if($result){
                return ['status'=>'1','msg'=>'','data'=>$fileName];
            }
            return  ['status'=>'-1','msg'=>$img->error];
        }
        return ['status'=>'-1','msg'=>'没有文件上传'];
    }


    //分页
    public function actionList(){
        //总条数
        $query = Goods::find();
        $count = $query->count();
       // 带搜索

        $keywords = \Yii::$app->request->get('keywords');
        if($keywords){
            $query->andWhere(['like','name',$keywords]);
        }
        //每页显示条数
        $pre_page= \Yii::$app->request->get('pre_page',2); //给每页显示条数设置一个默认值2条
        //当前页数
        $page = \Yii::$app->request->get('page',1);//默认第一页
        $page = $page<1?1:$page;//防止翻页小于第一页

        //$pre_page*($page-1) 当前页的数据
        $goods = $query->offset($pre_page*($page-1))->limit($pre_page)->asArray()->all();

        return ['status'=>'1','msg'=>'','data'=>[
            'goods'=>$goods,
            'page'=>$page,
            'pre_page'=>$pre_page,
            'count'=>$count,
        ]];
    }




    //-发送手机验证码
    public function actionSendSms()
    {
        //确保上一次发送短信间隔超过1分钟
        $tel = \Yii::$app->request->post('tel');
        if(!preg_match('/^1[34578]\d{9}$/',$tel)){
            return ['status'=>'-1','msg'=>'电话号码不正确'];
        }
        //检查上次发送时间是否超过1分钟
        $value = \Yii::$app->cache->get('time_tel_'.$tel);
        $s = time()-$value;
        if($s <60){
            return ['status'=>'-1','msg'=>'请'.(60-$s).'秒后再试'];
        }

        $code = rand(1000,9999);
        //$result = \Yii::$app->sms->setNum($tel)->setParam(['code' => $code])->send();
        $result = 1;
        if($result){
            //保存当前验证码 session  mysql  redis  不能保存到cookie
//            \Yii::$app->session->set('code',$code);
//            \Yii::$app->session->set('tel_'.$tel,$code);
            \Yii::$app->cache->set('tel_'.$tel,$code,5*60);
            \Yii::$app->cache->set('time_tel_'.$tel,time(),5*60);
            //echo 'success'.$code;
            return ['status'=>'1','msg'=>''];
        }else{
            return ['status'=>'-1','msg'=>'短信发送失败'];
        }
    }

    public function actionAddCart(){
        $request=\Yii::$app->request;
        if($request->isPost){
            $goods_id=$request->post('goods_id');
            $amount=$request->post('amount');
            $goods=Goods::findOne(['id'=>$goods_id]);
            if($goods){
                //读取原购物车中的商品==》读==》request
                if(\Yii::$app->user->isGuest){
                    //实例化cookie
                    $cookies=\Yii::$app->request->cookies;
                    $cookie=$cookies->get('cart');
                    if($cookie==null){
                        $cart=[];
                    }else{
                        $cart=unserialize($cookie->value);
                    }
                    //将新增的商品和购物车中的商品合并==》写==》response
                    $cookies=\Yii::$app->response->cookies;
                    //查购物车中是否有该商品
                    if(key_exists($goods->id,$cart)){
                        $cart['goods_id']+=$amount;
                    }else{
                        $cart['goods_id']=$amount;
                    }
                    $cookie=new Cookie([
                        'name'=>'cart','value'=>serialize($cart)
                    ]);
                    $cookies->add($cookie);
                    return ['status'=>1,'msg'=>'未登录状态添加成功'];

                }else{
                    //判断该登录用户是否购买了该商品
                    $model=Cart::findOne(['goods_id'=>$goods_id,'member_id'=>\Yii::$app->user->id]);
                    if($model){
                        $model->amount=$amount+$model->amount;
                        $model->save();
                    }else{
                        $model=new Cart();
                        $model->goods_id=$goods_id;
                        $model->amount=$amount;
                        $model->member_id=\Yii::$app->user->id;
                        $model->save();
                    }
                    return ['status'=>1,'msg'=>'登录状态添加成功'];
                }
            }
            return ['status'=>-1,'msg'=>'无相关商品'];
        }
        return ['status'=>-1,'msg'=>'请使用post请求'];
    }




}