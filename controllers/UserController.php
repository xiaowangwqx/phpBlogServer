<?php
namespace app\controllers;
use yii\web\Controller;
use app\models\User;
use yii\rest\ActiveController;
class UserController extends Controller{

    public $enableCsrfValidation = false;

    public function actionLogin(){

        $request = \YII::$app->request;

        $username = $request->post('username');
        $password = $request->post('password');
        $sql = "select * from user where username = :1";
        $user = User::findBySql($sql,array(':1'=>$username))->all();
        $result = array();
        $result['user'] = $user;
        $result['username'] = $username;
        $result['status'] = -1;
        if(count($user)==0){
            $result['status'] = 3;
        }else{
            if($user[0]['password']==$password){
                $result['status'] = 1;
            }else{
                $result['status'] = 2;
            }
        }
        echo json_encode($result);
    }

    public function actionRegisted(){
        $request = \YII::$app->request;
        $username = $request->post('username');
        $password = $request->post('password');
        $email = $request->post('email');
        $result = array();
        //判断用户是否已存在,0 表示注册成功，1表示用户已存在
        $sql = "select * from user where username = :1";
        $user = User::findBySql($sql,array(':1'=>$username))->all();
        if(count($user)>0){
            $result['status'] = 1;
        }else{
            $user = new User();
            $user->username = $username;
            $user->password = $password;
            $user->email = $email;
            $user->save();
            $result['status'] = 0;
        }
        
        echo json_encode($result);
    }

    //解决跨域访问问题
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