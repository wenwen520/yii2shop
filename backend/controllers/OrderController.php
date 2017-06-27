<?php
namespace backend\controllers;

use backend\models\Order;
use yii\web\Controller;

class OrderController extends Controller{


        public function actionIndex(){
            $models = Order::find()->all();

            return $this->render('index',['models'=>$models]);
        }

    //发货

    public function actionDeliver_goods($id){
        $order = new Order();
        $order = Order::findOne(['id'=>$id]);
        $order->status = 2;
        $order->save();
        \Yii::$app->session->setFlash('success','发货成功');
        return $this->redirect(['order/index']);

    }
}