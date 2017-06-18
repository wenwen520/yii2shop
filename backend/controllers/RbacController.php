<?php

namespace backend\controllers;

use backend\models\PermissionForm;
use backend\models\RoleForm;
use yii\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;

class RbacController extends \yii\web\Controller
{
    //权限首页
    public function actionIndexPermission()
    {
        //读取所有权限
        $permissions=\Yii::$app->authManager->getPermissions();
        return $this->render('index-permission',['permissions'=>$permissions]);
    }
    //新建添加权限的操作
    public function actionAddPermission(){
        //新建表单模型
        $model=new PermissionForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addPermission()){
                //提示信息
                \Yii::$app->session->setFlash('success','添加权限成功');
                //返回首页
                return $this->redirect(['rbac/index-permission']);
            }
        }
        //渲染添加视图
        return $this->render('add-permission',['model'=>$model]);
    }
    //新建更新权限的操作
    public function actionUpdatePermission($name){
        //新建权限表单模型对象
        $model=new PermissionForm();
        $authManager=\Yii::$app->authManager;
        //根据name查找对应的权限
        $permission=$authManager->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('权限不存在');
        }
        //更新页面加载数据
        $model->loadPermission($permission);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updatePermission($name)){
                //提示信息
                \Yii::$app->session->setFlash('success','权限更新成功');
                //返回首页
                return $this->redirect(['rbac/index-permission']);
            }
        }
        //渲染更新权限视图
        return $this->render('add-permission',['model'=>$model]);
    }
    //新建删除权限的操作
    public function actionDelPermission($name){
        $authManager=\Yii::$app->authManager;
        //根据name查找权限
        $permission=$authManager->getPermission($name);
        if($permission==null){
            throw new NotFoundHttpException('该权限不存在');
        }
        //删除权限
        $authManager->remove($permission);
        //提示信息
        \Yii::$app->session->setFlash('success','删除权限成功');
        //跳转回首页
        return $this->redirect(['rbac/index-permission']);

    }
    //新建角色首页
    public function actionIndexRole(){
        //查询所有角色
        $roles=\Yii::$app->authManager->getRoles();
        //渲染角色首页
        return $this->render('index-role',['roles'=>$roles]);
    }
    //新增角色操作
    public function actionAddRole(){
        //新建角色表单模型对象
        $model=new RoleForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->addRole()){
                //提示信息
                \Yii::$app->session->setFlash('success','添加角色成功');
                //返回首页
                return $this->redirect(['rbac/index-role']);
            }
        }
        //渲染新建角色首页视图
        return $this->render('add-role',['model'=>$model]);
    }
    //更新角色的操作
    public function actionUpdateRole($name){
        $model=new RoleForm();
        $authManager=\Yii::$app->authManager;
        $role=$authManager->getRole($name);
        if($role==null){
            throw  new NotFoundHttpException('该角色不存在');
        }
        //加载数据
        $model->loadRole($role);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            if($model->updateRole($name,$role)){
                //提示信息
                \Yii::$app->session->setFlash('success','更新角色成功');
                //返回首页
                return $this->redirect(['rbac/index-role']);
            }
        }
        //渲染更新首页
        return $this->render('add-role',['model'=>$model]);
    }
    //删除角色的操作
    public function actionDelRole($name){
        //查找角色
        $role=\Yii::$app->authManager->getRole($name);
        if($role==null){
            throw new NotFoundHttpException('该角色不存在');
        }
        //删除角色
        \Yii::$app->authManager->remove($role);
        //提示信息
        \Yii::$app->session->setFlash('success','删除角色成功');
        //返回首页
        return $this->redirect(['rbac/index-role']);
    }

}
