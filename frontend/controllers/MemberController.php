<?php

namespace frontend\controllers;

use frontend\models\LoginForm;
use frontend\models\Member;

class MemberController extends \yii\web\Controller
{
    //加载布局文件
    public $layout;
    //注册
    public function actionRegister(){
        $this->layout='login';
        $model=new Member();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                //生成注册时间
                $model->created_at=time();
                //密码加密
                $model->password_hash=\Yii::$app->security->generatePasswordHash($model->password);
                //保存默认状态
                $model->status=1;
                //保存
                $model->save(false);
                //跳转到登录界面
                return $this->redirect(['member/login']);
            }else{
                var_dump($model->getErrors());
                exit;
            }

        }
        return $this->render('register',['model'=>$model]);
    }
    public function actionIndex()
    {
        $this->layout='index';
        return $this->render('index');
    }
    //登录
    public function actionLogin(){
        $this->layout='login';
        $model=new LoginForm();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //根据用户名查找对应的用户
            $member=Member::findOne(['username'=>$model->username]);
            //生成最后登录时间
            $member->last_login_time=time();
            //生成最后登录IP
            $member->last_login_ip=\Yii::$app->request->getUserIP();
            //保存
            $member->save(false);
            //跳转到用户界面
            return $this->redirect(['member/index']);
        }
        return $this->render('login',['model'=>$model]);
    }
    //退出登录
    public function actionLogout(){
        //退出
        \Yii::$app->user->logout();
        //跳转到首页界面
        return $this->redirect(['member/index']);
    }
    //收货地址
    public function actionAddress(){
        $this->layout='index';
        return $this->render('address');
    }

}
