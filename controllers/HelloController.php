<?php
namespace app\controllers;
use yii\web\Controller;
use app\models\Item;
class HelloController extends Controller{

    public function actionIndex(){

        $request = \YII::$app->request;
//        echo $request->get('id',10);
//        if($request->isGet){
//            echo 'get';
//        }
//        if($request->isPost){
//            echo 'post';
//        }
//        echo '<br/>';
//        echo $request->userIP.'<br/>';

        $response = \YII::$app->response;
        $response->statusCode = '200';

//        $response->sendFile('./robots.txt');

        $session = \YII::$app->session;
//        if($session->isActive){
//            echo 'session is active';
//        }else{
//            echo 'session is unactive';
//        }

        $session->set('username','haha');


    }

    public function actionGet_session(){

        $session = \YII::$app->session;
        echo $session->get('username');


    }

    public function actionGet_itemslist(){

        //$items = array();
        \Yii::$app->response->format=\yii\web\Response::FORMAT_JSON;

        $sql = 'select * from Item';
        $items = Item::findBySql($sql)->asArray()->all();
        echo json_encode($items);
        //print_r($items);
    }

    public function actionShowview(){

        $hello_str = 'Hello God';

        $items = array();
        $items['apple'] = '10';
        $items['banana'] = '20';
        $items['orange'] = '30';

        $data = array();
        $data['hello_str'] = $hello_str;

        return $this->renderPartial('index',$data);
    }

    public function actionDeletebyname(){

        $request = \Yii::$app->request;
        $name = $request->get('name');

//        $result = Item::find()->where(['title' => $name])->all();
//        $result[0]->delete();
        Item::deleteAll('title=:name',array('name'=>$name));

    }

    public function actionAdditem(){
        $item = new Item;
        $item->title = 'peer';
        $item->price = 200;
        $item->save();
        echo '保存成功';
    }

    public function actionUpdateitem(){
        $request = \Yii::$app->request;
        $id = $request->get('id');
        $price = $request->get('price');

        $item = Item::find()->where(['id'=>$id])->one();
        $item->price = $price;
        $item->save();

    }

}