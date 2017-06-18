<?php
namespace backend\widgets;
use yii\bootstrap\Html;
use yii\bootstrap\Widget;
use yii\bootstrap\NavBar;
use yii\bootstrap\Nav;
use Yii;

class MenuWidgets extends Widget{
    public function init()
    {
        parent::init();
    }
    public function run(){
        NavBar::begin([
            'brandLabel' => 'Saltwater Room',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-inverse navbar-fixed-top',
            ],
        ]);
    $menuItems=[
        ['label' => 'Home', 'url' => ['/user/index']],
    ];
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['user/login']];
    } else {
        $menuItems[]=['label' => 'ATCC', 'url' => ['article_category/index']];
        $menuItems[]=['label' => 'ATC', 'url' => ['article/index']];
        $menuItems[]=['label' => 'BC', 'url' => ['brand/index']];
        $menuItems[]=['label' => 'GCC', 'url' => ['goods_category/index']];
        $menuItems[]=['label' => 'GC', 'url' => ['goods/index']];
        $menuItems[]=['label' => 'RBACP', 'url' => ['rbac/index-permission']];
        $menuItems[]=['label' => 'RBACR', 'url' => ['rbac/index-role']];
        $menuItems[] = '<li>'
            . Html::beginForm(['/user/logout'], 'post')
            . Html::submitButton(
                'Logout (' . Yii::$app->user->identity->username . ')',
                ['class' => 'btn btn-link logout']
            )
            . Html::endForm()
            . '</li>';
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    }

}