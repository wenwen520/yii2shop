<?php
namespace backend\widgets;
use backend\models\Menu;
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
    /*$menuItems=[
        ['label' => 'Home', 'url' => ['/user/index']],
    ];*/
    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['user/login']];
    } else {
        //获取所有顶级菜单
        $menus=Menu::findAll(['parent_id'=>0]);
        foreach($menus as $menu){
            $item=['label' =>$menu->label, 'items' => []];
            foreach($menu->children as $child){
                //判断是否有操作权限
                if(Yii::$app->user->can($child->url)){
                    $item['items'][]=['label'=>$child->label,'url'=>[$child->url]];
                }
            }
            //如果一级菜单没有子菜单就不显示
            if(!empty($item['items'])){
                $menuItems[] =$item;
            }
        }
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