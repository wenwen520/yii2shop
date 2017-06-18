<?php

namespace backend\controllers;

use backend\filters\AccessFilter;
use backend\models\LoginForm;
use backend\models\PasswordForm;
use backend\models\User;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\Request;

class UserController extends \yii\web\Controller
{
    public function actionIndex()
    {
        //新建一个管理员模型对象
        $user_page=User::find();
        //查询总记录条数
        $total=$user_page->count();
        //配置分页
        $page=new Pagination([
            'totalCount'=>$total,
            'defaultPageSize'=>6,
        ]);
        //查询每页对应的记录
        $users=$user_page->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',['users'=>$users,'page'=>$page]);
    }
    //新建添加管理员的方法
    public function actionAdd(){
        //新建管理员数据库模型对象
        $model=new User();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                //生成注册时间
                $model->created_at=time();
                //密码加盐加密
                $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
                //保存默认状态
                $model->status=0;
                //保存
                $model->save();
                if($model->addRole($model->id)){
                    //提示信息
                    \Yii::$app->session->setFlash('success','注册成功');
                    //返回首页
                    return $this->redirect(['user/index']);
                }
            }else{
                var_dump($model->getErrors());
                exit;
            }

        }
        //渲染添加视图
        return $this->render('add',['model'=>$model]);
    }
    //新增编辑管理员的方法
    public function actionUpdate($id)
    {
        //根据ID查找对应的记录
        $model = User::findOne(['id' => $id]);
        //加载角色
        $model->loadRole($id);
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->validate()) {
                //保存
                $model->save();
                if($model->updateRole($model->id)){
                    //提示信息
                    \Yii::$app->session->setFlash('success', '更新成功');
                    //返回首页
                    return $this->redirect(['user/index']);
                }
            } else {
                var_dump($model->getErrors());
                exit;
            }
        }
        //渲染更新视图
        return $this->render('add',['model'=>$model]);
    }
    //新增删除管理员的方法
    public function actionDel($id){
        //根据ID查找对应的记录
        $model=User::findOne(['id'=>$id]);
        //删除对应记录
        $model->delete();
        //跳转回首页
        return $this->redirect(['user/index']);
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
    //登录
    public function actionLogin(){
        //新建管理员模型对象
        $model=new LoginForm();
        if($model->load(\Yii::$app->request->post())){
            if($model->validate()){
                //查询数据库
                $user=User::findOne(['username'=>$model->username]);
                //修改状态为在线
                $user->status=1;
                //提示信息
                \Yii::$app->session->setFlash('success','登录成功');
                //保存
                $user->save();
                //跳转回首页
                return $this->redirect(['user/index']);
            }
        }
        //渲染登录界面
        return $this->render('login',['model'=>$model]);
    }
    //新建退出方法
    public function actionLogout(){
        //获取当前登录状态为在线的用户
        $model=User::findOne(['status'=>1]);
        //修改登录状态
        $model->status=0;
        //获取最后登录时间
        $model->last_login=time();
        //获取最后登录IP
        $model->last_ip=\Yii::$app->request->userIP;
        //提示信息
        \Yii::$app->session->setFlash('success','退出成功');
        //退出
        \Yii::$app->user->logout();
        //保存
        $model->save();
        //跳转到登录界面
        return $this->redirect(['user/login']);
    }
    //修改密码
    public function actionEdit($id){
        //新建修改密码表单对象
        $model=new PasswordForm();
        //新建request模型对象
        $request = new Request();

        if($request->isPost){
            //加载数据
            $model->load($request->post());
            //验证数据
            if($model->validate()){
                //验证旧密码
                $user=User::findOne(['id'=>$id]);
                if(!\Yii::$app->security->validatePassword($model->old_password,$user->password_hash)){
                    $model->addError('old_password','密码错误');
                }else{
                    //验证两次输入的密码是否一致
                    if($model->password!=$model->re_password){
                        $model->addError('password','两次输入的密码不一致');
                    }else{
                        //修改密码
                        $model->password=\Yii::$app->security->generatePasswordHash($model->password);
                        $user->password_hash=$model->password;
                        \Yii::$app->session->setFlash('success','修改成功');    //添加成功
                        //保存
                        $user->save();
                        //跳转回首页
                        return $this->redirect(['user/index']);
                    }
                }
            }
        }
        //渲染修改密码视图
        return $this->render('edit',['model'=>$model]);
    }
    //ACF授权
    /*public function behaviors()
    {
        return [
            'acf'=>[
                'class'=>AccessControl::className(),
                'only'=>['add','update','del','index'],
                'rules'=>[//登录用户才允许增删改查
                    [
                        'allow'=>true,
                        'actions'=>['add','update','edit','del','index'],
                        'roles'=>['@']
                    ],
                ],
            ],
        ];
    }*/
    //RBAC授权
    public function behaviors(){
        return [
            'accessFilter'=>[
                'class'=>AccessFilter::className(),
                'only'=>['add','index','update','del','edit'],
            ]
        ];
    }
}
