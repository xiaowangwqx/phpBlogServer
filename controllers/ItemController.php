<?php
namespace app\controllers;
use yii\web\Controller;
use app\models\Item;
use yii\rest\ActiveController;
class ItemController extends ActiveController{

    public $modelClass = 'app\models\Item';

    public function actionIndex(){


    }

}