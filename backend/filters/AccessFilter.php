<?php
namespace backend\filters;
use yii\base\ActionFilter;
use yii\web\HttpException;

class AccessFilter extends ActionFilter {

    public function beforeAction($action)
    {
        //当前路由（操作）  $action->uniqueId;
        //判断当前用户是否拥有该操作权限
        if(!\Yii::$app->user->can($action->uniqueId)){
            //如果未登录，则跳转到登录页面
            if(\Yii::$app->user->isGuest){
                return $action->controller->redirect(\Yii::$app->user->loginUrl);
            }
            //没有权限，抛出403异常
            throw new HttpException(403,'对不起，您没有权限访问该页面');
        }
        return parent::beforeAction($action);
    }
}