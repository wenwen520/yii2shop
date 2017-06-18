<?php

namespace backend\controllers;

use backend\filters\AccessFilter;
use backend\models\Brand;
use backend\models\Goods;
use backend\models\GoodsCategory;
use backend\models\GoodsDayCount;
use backend\models\GoodsIntro;
use backend\models\Images;
use yii\data\Pagination;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model=new Goods();
        //从数据库读取所有的商品
        if($model->load(\Yii::$app->request->post())) {
            $goodsPage = Goods::find()->where(['like', 'name', $model->condition]);
        }else{
            $goodsPage = Goods::find();
        }
        //获取总记录条数
        $total=$goodsPage->count();
        //配置分页
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>3,
        ]);
        //读取每页对应的记录
        $goods=$goodsPage->offset($page->offset)->orderBy('sort DESC')->limit($page->limit)->all();
        //渲染首页视图
        return $this->render('index',['goods'=>$goods,'page'=>$page,'model'=>$model]);
    }
    //新建添加商品的方法
    public function actionAdd(){
        //新建商品模型对象
        $model=new Goods();
        //新建商品详情模型对象
        $detail=new GoodsIntro();
        //验证
        if($model->load(\Yii::$app->request->post()) && $detail->load(\Yii::$app->request->post())){
            //在验证之前,实例化文件上传对象
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate() && $detail->validate()){
                //定义图片路径
                $fileName='/images/'.uniqid().'.'.$model->imgFile->extension;
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                //图片地址赋值
                $model->logo=$fileName;
                //生成添加时间
                $model->create_time=time();
                //查询当前为第几条添加
                $dayAdd=GoodsDayCount::findOne(['day'=>date('Y-m-d')]);
                if($dayAdd){
                    //添加数量加一
                    $dayAdd->count=$dayAdd->count+1;
                    //保存
                    $dayAdd->save();
                }else{
                    $dayAdd=new GoodsDayCount();
                    $dayAdd->day=date('Y-m-d');
                    $dayAdd->count=1;
                    $dayAdd->save();
                }
                //计算商品数量的位数
                $number=sprintf("%04d",$dayAdd->count);
                //生成货号
                $model->sn=date('Ymd').$number;
                //保存
                $model->save();
                //将商品ID赋给商品详情
                $detail->goods_id=$model->id;
                //保存
                $detail->save();
                //提示信息
                \Yii::$app->session->setFlash('success','添加商品成功');
                //跳转回首页
                return $this->redirect(['goods/index']);
            }else{
                //打印错误信息
                var_dump($model->getErrors());
                var_dump($detail->getErrors());
                exit;
            }
        }
        //查出所有的品牌
        $brands=Brand::find()->asArray()->all();
        //查出所有的商品分类
        $categorys=GoodsCategory::find()->asArray()->all();
        //渲染添加首页
        return $this->render('add',['model'=>$model,'categorys'=>$categorys,'brands'=>$brands,'detail'=>$detail]);
    }
    //ueditor插件和uploadify插件
    public function actions()
    {
        return [

            'ueditor' => [
                'class' => 'crazyfd\ueditor\Upload',
                'config'=>[
                    'uploadDir'=>date('Y/m/d')
                ]

            ],
            's-upload' => [
                'class' => UploadAction::className(),
                'basePath' => '@webroot/upload',
                'baseUrl' => '@web/upload',
                'enableCsrf' => true, // default
                'postFieldName' => 'Filedata', // default
                //BEGIN METHOD
                'format' => [$this, 'methodName'],
                //END METHOD
                //BEGIN CLOSURE BY-HASH
                'overwriteIfExist' => true,
                /*'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filename = sha1_file($action->uploadfile->tempName);
                    return "{$filename}.{$fileext}";
                },*/
                //END CLOSURE BY-HASH
                //BEGIN CLOSURE BY TIME
                'format' => function (UploadAction $action) {
                    $fileext = $action->uploadfile->getExtension();
                    $filehash = sha1(uniqid() . time());
                    $p1 = substr($filehash, 0, 2);
                    $p2 = substr($filehash, 2, 2);
                    return "{$p1}/{$p2}/{$filehash}.{$fileext}";
                },
                //END CLOSURE BY TIME
                'validateOptions' => [
                    'extensions' => ['jpg', 'png'],
                    'maxSize' => 1 * 1024 * 1024, //file size
                ],
                'beforeValidate' => function (UploadAction $action) {
                    //throw new Exception('test error');
                },
                'afterValidate' => function (UploadAction $action) {},
                'beforeSave' => function (UploadAction $action) {},
                'afterSave' => function (UploadAction $action) {
                    //图片上传成功的同时，将图片和商品关联起来
                    $model=new Images();
                    $model->goods_id=\Yii::$app->request->post('goods_id');
                    $model->image=$action->getWebUrl();
                    $model->save();
                    $action->output['fileUrl'] = $model->image;
                    $action->output['id']=$model->id; //回调一个ID
                    /*$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"*/
                },
            ],
        ];
    }
    //新建修改商品的方法
    public function actionUpdate($id){
        //根据ID查找记录对象
        $model=Goods::findOne(['id'=>$id]);
        //新建商品详情模型对象
        $detail=GoodsIntro::findOne(['goods_id'=>$id]);
        //验证
        if($model->load(\Yii::$app->request->post()) && $detail->load(\Yii::$app->request->post())){
            //在验证之前,实例化文件上传对象
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate() && $detail->validate()){
                if($model->imgFile){
                    //定义图片路径
                    $fileName='/images/'.uniqid().'.'.$model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    //图片地址赋值
                    $model->logo=$fileName;
                }
                //保存
                $model->save();
                //保存
                $detail->save();
                //提示信息
                \Yii::$app->session->setFlash('success','更新商品成功');
                //跳转回首页
                return $this->redirect(['goods/index']);
            }else{
                //打印错误信息
                var_dump($model->getErrors());
                var_dump($detail->getErrors());
                exit;
            }
        }
        //查出所有的品牌
        $brands=Brand::find()->asArray()->all();
        //查出所有的商品分类
        $categorys=GoodsCategory::find()->asArray()->all();
        //渲染更新首页
        return $this->render('add',['model'=>$model,'categorys'=>$categorys,'brands'=>$brands,'detail'=>$detail]);
    }
    //新建删除商品的方法
    public function actionDel($id){
        //根据ID查找对应记录
        $model=Goods::findOne(['id'=>$id]);
        //修改商品的状态
        $model->status=0;
        //保存
        $model->save();
        //返回首页
        return $this->redirect(['goods/index']);
    }
    //新建显示商品详情的方法
    public function actionDetail($id){
        //根据ID查找对应的记录
        $model=Goods::findOne(['id'=>$id]);
        //渲染详情视图
        return $this->render('detail',['model'=>$model]);
    }
    //新建显示相册的方法
    public function actionImage($id){
        //根据ID查找对应的商品记录
        $model=Goods::findOne(['id'=>$id]);
        //找出所有的图片
        $images=$model->getImages()->all();
        //显示相册
        return $this->render('images',['images'=>$images,'model'=>$model]);

    }
    //新建AJAX删除相册图片的方法
    public function actionDelete(){
        $id=\Yii::$app->request->post('id');
        $model=Images::findOne(['id'=>$id]);
        //删除成功返回true，失败返回false
        if($model){
            $model->delete();
            return 'success';
        }else{
            return 'failed';
        }
    }
    //RBAC授权
    public function behaviors(){
        return [
            'accessFilter'=>[
                'class'=>AccessFilter::className(),
                'only'=>['add','index','update','del','detail','image'],
            ]
        ];
    }



}
