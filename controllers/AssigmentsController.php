<?php

namespace app\controllers;

use Yii;
use app\models\Assigments;
use app\models\search\AssigmentsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * AssigmentsController implements the CRUD actions for Assigments model.
 */
class AssigmentsController extends Controller
{
    /**
     * @inheritdoc
     */
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

    /**
     * Lists all Assigments models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AssigmentsSearch();
        $id= yii::$app->user->identity->id;
        $student=\app\models\StudentInfo::find()->where([
            'user_id'=>$id,
            ])->one();
        $searchModel->class_id=$student->class_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionDisplayAss()
    {
        $searchModel = new AssigmentsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('display-ass', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Assigments model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Assigments model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Assigments();

        if ($model->load(Yii::$app->request->post())) {
//echo '<pre>';print_r($_FILES);die;
            if(!empty($_FILES['Assigments']['name']['image'])){
           $file =$model->image= UploadedFile::getInstance($model, 'image');
           $model->image=$file; 
                if(!empty($file)){
                 $file->saveAs(\Yii::$app->basePath . '/web/uploads/assigments/'.$file);
                }
                }
         if($model->save()){
            Yii::$app->session->setFlash('success', "Successfully Assign");
            return $this->redirect(['display-ass']);
            
         }else{
            print_r($model->getErrors());die;
         }


        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Assigments model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {

             $old_image=$model->image;
             if(!empty($_FILES['Assigments']['name']['image'])){
                //$models->password_hash=$loginUser->password_hash;
                 $file =UploadedFile::getInstance($model, 'image');
                $pth= Yii::$app->basePath . '/web/uploads/assigments/'.$old_image;
                 $file->saveAs(\Yii::$app->basePath . '/web/uploads/assigments/'.$file);
                 unlink($pth);
                 $model->image=$file;
                }else{
                    $model->image=$old_image;
                }
             $model->save();
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Assigments model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Assigments model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Assigments the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Assigments::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
