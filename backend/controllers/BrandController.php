<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;
use xj\uploadify\UploadAction;


class BrandController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //从数据库查询所有品牌
        $brand_page=Brand::find();
        //获取总记录条数
        $total=$brand_page->count();
        //配置分页
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>3,
        ]);
        //查询每页对应的记录
        $brands=$brand_page->offset($page->offset)->orderBy('sort DESC')->limit($page->limit)->all();
        //渲染首页视图
        return $this->render('index',['brands'=>$brands,'page'=>$page]);
    }
    //新建添加品牌的方法
    public function actionAdd(){
        //新建品牌数据库模型对象
        $model=new Brand();
        //新建request模型对象
        $request=new Request();
        //判断是否是post
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //在数据验证之前，实例化文件上传对象
            //$model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate()){
                /*//定义图片路径
                $fileName='/images/'.uniqid().'.'.$model->imgFile->extension;
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                //图片地址赋值
                $model->logo=$fileName;*/
                //保存
                $model->save(false);
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加品牌成功');
                //跳转到首页
                return $this->redirect(['brand/index']);
            }else{
                //打印错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        //渲染添加首页
        return $this->render('add',['model'=>$model]);
    }
    //新建修改品牌的方法
    public function actionUpdate($id){
        //通过ID查找记录
        $model=Brand::findOne(['id'=>$id]);
        //$model->scenario=Brand::SCENARIO_UPDATE;
        //新建request模型对象
        $request=new Request();
        //判断是否是post
        if($request->isPost) {
            //加载数据
            $model->load($request->post());
            //在数据验证之前，实例化文件上传对象
            //$model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            //验证数据
            if ($model->validate()) {
                /*if($model->imgFile){
                    //定义图片路径
                    $fileName = '/images/' . uniqid() . '.' . $model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    //图片地址赋值
                    $model->logo = $fileName;
                }*/
                //提示信息
                \Yii::$app->session->setFlash('success','更新成功');
                //保存
                $model->save(false);
                //跳转到首页
                return $this->redirect(['brand/index']);
            }else{
                //打印错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        //渲染更新视图
        return $this->render('add',['model'=>$model]);
    }
    //新建删除方法
    public function actionDel($id){
        //通过ID查找记录
        $model=Brand::findOne(['id'=>$id]);
        //修改状态
        $model->status=-1;
        //保存
        $model->save(false);
        //返回首页
        return $this->redirect(['brand/index']);
    }
    //uploadify 插件
    public function actions() {
        return [
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
                    //调用七牛云组件，将图片上传到七牛云
                    $qiniu=\Yii::$app->qiniu;
                    //获取文件路径
                    //$fileName=$action->getWebUrl();
                    //将图片上传到七牛云
                    $qiniu->uploadFile($action->getSavePath(),$action->getWebUrl());
                    //获取图片地址
                    $logo=$qiniu->getLink($action->getWebUrl());
                    $action->output['fileUrl'] = $logo;
                    /*$action->getFilename(); // "image/yyyymmddtimerand.jpg"
                    $action->getWebUrl(); //  "baseUrl + filename, /upload/image/yyyymmddtimerand.jpg"
                    $action->getSavePath(); // "/var/www/htdocs/upload/image/yyyymmddtimerand.jpg"*/
                },
            ],
        ];
    }
    //七牛云插件
    public function actionTest(){
        $ak = 'LWIojI6DlaJ6lOBoeErN_z6wDhqMRFF6F7S2S7yp';
        $sk = 'Rbp45nOdM6jIXDTUb-aTxWFwluZQUPUc4g1bAtP2';
        $domain = 'http://or9solen4.bkt.clouddn.com/';
        $bucket = 'stefanshop';
        $qiniu = new Qiniu($ak, $sk,$domain, $bucket);
        //上传测试图片
        $fileName=\Yii::getAlias('@webroot').'/upload/girl.png';
        $key = time();
        $qiniu->uploadFile($fileName,$key);
        $url = $qiniu->getLink($key);
        var_dump($url);
    }
}
