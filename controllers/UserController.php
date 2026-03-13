<?php
namespace app\controllers;
use Yii;
use app\models\User;
use app\models\search\UserSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
class UserController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionProfile(){
        $model=new User();
        $id=yii::$app->user->identity->id;
        $loginUser=User::find()->where(['id'=>$id])->one();
         $models = $this->findModel($id);
        if ($models->load(Yii::$app->request->post())) {
           // print_r($_FILES);die;
            $array=yii::$app->request->post('User');
            $old_image=$loginUser->Image;
            if(!empty($_FILES['User']['name']['Image'])){
                //$models->password_hash=$loginUser->password_hash;
                 $file =UploadedFile::getInstance($models, 'Image');
                $pth= Yii::$app->basePath . '/web/uploads/'.$old_image;
                //echo $old_image;die;
                 $file->saveAs(\Yii::$app->basePath . '/web/uploads/'.$file,false);
                 if(!empty($old_image)){
                 unlink($pth);
             }
                 $models->Image=$file;
                }else{
                    $models->Image=$old_image;
                }
            //echo '<pre>';print_r($array);die;
             if($models->save()){
                Yii::$app->session->setFlash('success', "Profile Successfully Updated");
                return $this->redirect(['profile']);

             }else{print_r($models->getErrors());die;};
        }else{
        return $this->render('profile',[
            'loginUser'=>$loginUser,
            'model'=>$model,
            ]);
    }
       
    }
    
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionUpdateStatus1($id){

        $model= StudentInfo::findOne($id);
        $model->is_active = 'inactive';
        $model->save();  // equivalent to $model->update();
        return $this->redirect(['student']);
    }

    public function actionCreate()
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}