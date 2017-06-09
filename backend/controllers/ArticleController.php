<?php

namespace backend\controllers;

use backend\models\Article;
use yii\data\Pagination;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //从数据库查询所有文章
        $article_page=Article::find();
        //获取总记录条数
        $total=$article_page->count();
        //配置分页
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>3,
        ]);
        //查询每页对应的记录
        $articles=$article_page->offset($page->offset)->orderBy('sort DESC')->limit($page->limit)->all();
        //渲染首页视图
        return $this->render('index',['articles'=>$articles,'page'=>$page]);
    }
    //新建添加文章的方法
    public function actionAdd(){
        //新建文章数据库模型对象
        $model=new Article();
        //新建request模型对象
        $request=new Request();
        //判断是否是post
        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //添加创建时间
                $model->create_time=time();
                //保存
                $model->save(false);
                //设置提示信息
                \Yii::$app->session->setFlash('success','添加文章成功');
                //跳转到首页
                return $this->redirect(['article/index']);
            }else{
                //打印错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        //渲染添加首页
        return $this->render('add',['model'=>$model]);
    }
    //新建修改文章的方法
    public function actionUpdate($id)
    {
        //通过ID查找数据库模型对象
        $model = Article::findOne(['id' => $id]);
        //新建request模型对象
        $request = new Request();
        //判断是否是post
        if ($request->isPost) {
            //加载数据
            $model->load($request->post());
            //验证数据
            if ($model->validate()) {
                //保存
                $model->save(false);
                //设置提示信息
                \Yii::$app->session->setFlash('success', '修改文章成功');
                //跳转到首页
                return $this->redirect(['article/index']);
            } else {
                //打印错误信息
                var_dump($model->getErrors());
                exit;
            }
        }
        //渲染修改首页
        return $this->render('add', ['model' => $model]);
    }
    //新建删除文章的方法
    public function actionDel($id){
        //通过ID查找对应的数据库对象
        $model=Article::findOne(['id'=>$id]);
        //更改文章分类的状态
        $model->status=-1;
        //保存
        $model->save();
        //跳转会首页
        return $this->redirect(['article/index']);
    }
}
