<?php
namespace app\controllers;
use app\models\Article;
use app\models\Comment;
use yii\web\Controller;
use app\models\User;
use yii\rest\ActiveController;
include_once 'D:\wamp64\www\basic\Utils\TextScanRequest.php';
class CommentController extends Controller{

    public $enableCsrfValidation = false;

    public function actionAdd_comment(){
        $data = \Yii::$app->request->post();
        $article_id = $data['article_id'];
        $content = $data['content'];
        $commenter_name = $data['commenter_name'];
        $email = $data['email'];
        $url = $data['url'];
        $createtime =  time();

        $testResult = array('suggestion' =>'pass');
        //if($testResult['suggestion'] == 'pass') {
            $comment = new Comment();
            $comment->article_id = $article_id;
            $comment->content = $content;
            $comment->createtime = $createtime;
            $comment->commenter_name = $commenter_name;
            $comment->email = $email;
            $comment->url = $url;
            $comment->save();
       // }
        return json_encode($testResult);
    }

    public function actionGet_comment(){
        $request = \Yii::$app->request;
        $article_id = $request->get('article_id');
        $sql = "select * from comment where article_id = :1";
        $comments = Comment::findBySql($sql,array(':1'=>$article_id))->asArray()->all();
        return json_encode($comments);

    }


    public function behaviors()
    {
        $behaviors = parent::behaviors();

        // remove authentication filter
        //$auth = $behaviors['authenticator'];
        //unset($behaviors['authenticator']);

        // add CORS filter
        $behaviors['corsFilter'] = [
            'class' => \yii\filters\Cors::className(),
        ];

        // re-add authentication filter
        //$behaviors['authenticator'] = $auth;
        // avoid authentication on CORS-pre-flight requests (HTTP OPTIONS method)
        // $behaviors['authenticator']['except'] = ['options'];

        return $behaviors;
    }

}