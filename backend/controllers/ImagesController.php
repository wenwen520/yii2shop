<?php

namespace backend\controllers;

use backend\models\Goods;
use backend\models\Images;
use yii\data\Pagination;
use yii\web\UploadedFile;

class ImagesController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //从数据库读取所有的图片
        $imagesPage = Images::find();
        //获取总记录条数
        $total=$imagesPage->count();
        //配置分页
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>8,
        ]);
        //读取每页对应的记录
        $images=$imagesPage->offset($page->offset)->limit($page->limit)->all();
        //渲染首页视图
        return $this->render('index',['images'=>$images,'page'=>$page]);
    }
    //新建添加图片的方法
    public function actionAdd(){
        //新建图片模型对象
        $model=new Images();
        if($model->load(\Yii::$app->request->post())){
            //在验证之前,实例化文件上传对象
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate()){
                if($model->imgFile){
                    //定义图片路径
                    $fileName='/images/'.uniqid().'.'.$model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    //图片地址赋值
                    $model->image=$fileName;
                }
                //提示信息
                \Yii::$app->session->setFlash('success','添加图片成功');
                //保存
                $model->save();
                //返回首页
                return $this->redirect(['images/index']);
            }

        }
        //渲染添加视图
        $goods=Goods::find()->all();
        return $this->render('add',['model'=>$model,'goods'=>$goods]);
    }
    //新建修改图片的方法
    public function actionUpdate($id){
        //根据ID查找对应记录
        $model=Images::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())){
            //在验证之前,实例化文件上传对象
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate()){
                if($model->imgFile){
                    //定义图片路径
                    $fileName='/images/'.uniqid().'.'.$model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                    //图片地址赋值
                    $model->image=$fileName;
                }
                //提示信息
                \Yii::$app->session->setFlash('success','图片更新成功');
                //保存
                $model->save();
                //返回首页
                return $this->redirect(['images/index']);
            }

        }
        //渲染更新视图
        $goods=Goods::find()->all();
        return $this->render('add',['model'=>$model,'goods'=>$goods]);
    }
    //新建删除图片的方法
    public function actionDel($id){
        //根据ID查找对应记录
        $model=Images::findOne(['id'=>$id]);
        //删除对应记录
        $model->delete();
        //返回首页
        return $this->redirect(['images/index']);
    }
}
