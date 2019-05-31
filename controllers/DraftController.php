<?php
namespace app\controllers;
use app\models\Article;
use app\models\Draft;
use yii\web\Controller;
use yii\filters\Cors;
use app\models\UploadForm;
use yii\web\UploadedFile;
use yii\helpers\Url;
class DraftController extends Controller{
    public $enableCsrfValidation = false;

    public function actionAdd_article(){
        $data = \Yii::$app->request->post();
        $title = $data['title'];
        $content = $data['content'];
        $content_md = $data['content_md'];
        $type = $data['type'];
        $createtime =  time();
        $author = $data['author'];
        $article = new Draft();
        $article->title = $title;
        $article->content = $content;
        $article->createtime = $createtime;
        $article->content_md = $content_md;
        $article->type = $type;
        $article->author = $author;
        $article->save();
        return 'ok';
    }

    public function actionUpdate_article(){
        $data = \Yii::$app->request->post();
        $id = $data['id'];
        $title = $data['title'];
        $content = $data['content'];
        $content_md = $data['content_md'];
        $type = $data['type'];
        $article = Draft::findOne($id);
        $article->title = $title;
        $article->content = $content;
        $article->content_md = $content_md;
        $article->type = $type;
        $article->save();
        return 'ok';
    }

    public function actionGet_articles(){
        $data = \Yii::$app->request->get();
        $author = $data['author'];
        $articles = Draft::findBySql("select * from draft where author = :1",array(':1'=>$author))->asArray()->all();
        return json_encode($articles);
    }

    public function actionGet_article_by_id(){
        $request = \Yii::$app->request;
        $id = $request->get('id');
        $sql = 'select * from draft where id = :1';
        $articles = Draft::findBySql($sql,array(':1'=>$id))->asArray()->all();
        return json_encode($articles);
    }

    public function actionUpload()
    {
        $targetFolder = \Yii::$app->basePath.'/web/Uploads/'.date('Y/md');
        $file = new \yii\helpers\FileHelper();
        $file->createDirectory($targetFolder);
        if (!empty($_FILES)) {
            $tempFile = $_FILES['imageFile']['tmp_name'];
            $fileParts = pathinfo($_FILES['imageFile']['name']);
            $extension = $fileParts['extension'];
            $random = time() . rand(1000, 9999);
            $randName = $random . "." . $extension;
            $targetFile = rtrim($targetFolder,'/') . '/' . $randName;
            $fileTypes = array('jpg','jpeg','gif','png');
            $uploadfile_path = 'Uploads/'.date('Y/md').'/'.$randName;
            $callback['url'] = $uploadfile_path;
            $callback['filename'] = $fileParts['filename'];
            $callback['randName'] = $random;
            if (in_array($fileParts['extension'],$fileTypes)) {
                move_uploaded_file($tempFile,$targetFile);
                echo json_encode($callback);
            } else {
                echo '不能上传后缀为'.$fileParts['extension'].'文件';
            }
        }else{
            echo "没有上传文件";
        }

    }

    public function actionGet_imageurl(){
        echo Url::to('@web/Uploads/2018/0620/15295093503133.png');
    }

    public function actionDelete_article()
    {
        $request = \Yii::$app->request;
        $id = $request->get('id');
        Draft::findOne($id)->delete();
        return 'ok';
    }


    public function actionAdd_likes(){
        $request = \Yii::$app->request;
        $article_id = $request->get('article_id');
        $article = Draft::findOne($article_id);
        $article->likes = $article->likes + 1;
        $article->save();
        return ($article->likes);
    }

    public function actionGettypes(){
        return json_encode(Draft::findBySql('select distinct type from draft')->asArray()->all());
    }

    public function actionGet_article_by_type(){
        $request = \Yii::$app->request;
        $type = $request->get('type');
        $sql = 'select * from draft where type=:1 order by createtime desc';
        return json_encode(Draft::findBySql($sql,array(':1'=>$type))->asArray()->all());
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