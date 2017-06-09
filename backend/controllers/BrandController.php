<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\Request;
use yii\web\UploadedFile;

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
        $model=new Brand(['scenario'=>Brand::SCENARIO_ADD]);
        //新建request模型对象
        $request=new Request();
        //判断是否是post
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //在数据验证之前，实例化文件上传对象
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            //验证数据
            if($model->validate()){
                //定义图片路径
                $fileName='/images/'.uniqid().'.'.$model->imgFile->extension;
                $model->imgFile->saveAs(\Yii::getAlias('@webroot').$fileName,false);
                //图片地址赋值
                $model->logo=$fileName;
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
        $model->scenario=Brand::SCENARIO_UPDATE;
        //新建request模型对象
        $request=new Request();
        //判断是否是post
        if($request->isPost) {
            //加载数据
            $model->load($request->post());
            //在数据验证之前，实例化文件上传对象
            $model->imgFile = UploadedFile::getInstance($model, 'imgFile');
            //验证数据
            if ($model->validate()) {
                if($model->imgFile){
                    //定义图片路径
                    $fileName = '/images/' . uniqid() . '.' . $model->imgFile->extension;
                    $model->imgFile->saveAs(\Yii::getAlias('@webroot') . $fileName, false);
                    //图片地址赋值
                    $model->logo = $fileName;
                }
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
    //验证码
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength'=>4,
                'maxLength'=>4,
            ],
        ];
    }
}
