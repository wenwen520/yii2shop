<?php

namespace backend\controllers;

use backend\filters\AccessFilter;
use backend\models\Menu;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class MenuController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //从数据库读取所有的菜单
        $menu_page=Menu::find();
        //读取总记录条数
        $total=$menu_page->count();
        //配置分页
        $page=new Pagination([
           'totalCount'=>$total,
            'defaultPageSize'=>10,
        ]);
        //查询每页对应的记录
        $menus=$menu_page->offset($page->offset)->orderBy('sort')->limit($page->limit)->all();
        return $this->render('index',['menus'=>$menus,'page'=>$page]);
    }
    //新增添加菜单的操作
    public function actionAdd(){
        //新建模型对象
        $model=new Menu();
        if($model->load(\Yii::$app->request->post())){
            if( $model->validate()){
                $model->save();
                //提示信息
                \Yii::$app->session->setFlash('success','添加菜单成功');
                //返回首页
                return $this->redirect(['menu/index']);
            }else{
                var_dump($model->getErrors());
                exit;
            }

        }
        //渲染添加视图
        return $this->render('add',['model'=>$model]);
    }
    //新建更新菜单的操作
    public function actionUpdate($id){
        //根据id查找对应的菜单
        $model=Menu::findOne(['id'=>$id]);
        if($model->load(\Yii::$app->request->post())){
            if( $model->validate()){
                $model->save();
                //提示信息
                \Yii::$app->session->setFlash('success','更新菜单成功');
                //返回首页
                return $this->redirect(['menu/index']);
            }else{
                var_dump($model->getErrors());
                exit;
            }

        }
        //渲染更新视图
        return $this->render('add',['model'=>$model]);
    }
    //新增删除菜单的操作
    public function actionDel($id){
        //根据id查找对应的菜单
        $model=Menu::findOne(['id'=>$id]);
        //删除
        $model->delete();
        //提示信息
        \Yii::$app->session->setFlash('success','删除菜单成功');
        //返回首页
        return $this->redirect(['menu/index']);
    }
    //RBAC授权
    public function behaviors(){
        return [
            'accessFilter'=>[
                'class'=>AccessFilter::className(),
            ]
        ];
    }

}
