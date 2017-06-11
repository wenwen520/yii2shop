<?php

namespace backend\controllers;

use backend\models\GoodsCategory;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Request;

class Goods_categoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //$this->layout=false;
        //从数据库查询所有文章
        $goods_category_page=GoodsCategory::find();
        //获取总记录条数
        $total=$goods_category_page->count();
        //配置分页
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>3,
        ]);
        //查询每页对应的记录
        $categorys=$goods_category_page->offset($page->offset)->limit($page->limit)->all();
        //渲染首页视图
        return $this->render('index',['categorys'=>$categorys,'page'=>$page]);
    }
    //新建新增分类的方法
    public function actionAdd(){
        //新建数据库模型对象
        $model=new GoodsCategory();
        //新建request模型对象
        $request=new Request();
        //判断是否是post提交
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //判断是否是顶级分类
                if($model->parent_id){
                    //找到父分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    //将子类添加到父类
                    $model->prependTo($parent);
                }else{
                    //新建一个顶级分类
                    $model->makeRoot();
                }
                //提示信息
                \Yii::$app->session->setFlash('success','添加分类成功');
                //返回首页
                return $this->redirect(['goods_category/index']);
            }else{
                //打印错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        //查询分类数据并展示
        $categorys=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        //渲染添加视图
        return $this->render('add',['model'=>$model,'categorys'=>$categorys]);
    }
    //新建更新分类的方法
    public function actionUpdate($id){
        //根据ID查找数据记录
        $model=GoodsCategory::findOne(['id'=>$id]);
        //新建request模型对象
        $request=new Request();
        //判断是否是post提交
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //判断是否是顶级分类
                if($model->parent_id){
                    //找到父分类
                    $parent=GoodsCategory::findOne(['id'=>$model->parent_id]);
                    //将子类添加到父类
                    $model->prependTo($parent);
                }else{
                    //新建一个顶级分类
                    $model->makeRoot();
                }
                //提示信息
                \Yii::$app->session->setFlash('success','更新分类成功');
                //返回首页
                return $this->redirect(['goods_category/index']);
            }else{
                //打印错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        //查询分类数据并展示
        $categorys=ArrayHelper::merge([['id'=>0,'name'=>'顶级分类','parent_id'=>0]],GoodsCategory::find()->asArray()->all());
        //渲染更新视图
        return $this->render('add',['model'=>$model,'categorys'=>$categorys]);
    }
    //新建删除分类的方法
    public function actionDel($id){
        //根据ID查找记录
        $model=GoodsCategory::findOne(['id'=>$id]);
        //判断当前类是否有子类，如果有则不能删除
        $children=GoodsCategory::find()->where(['parent_id'=>$id])->all();
        if($children){
            \Yii::$app->session->setFlash('warning','当前类有子类，不能删除');
        }else{
            //删除当前记录
            $model->delete();
        }
        //跳转灰首页
        return $this->redirect(['goods_category/index']);
    }

}
