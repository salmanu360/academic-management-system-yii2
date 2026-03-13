<?php

namespace app\controllers;

use Yii;
use app\models\BookIssue;
use app\models\AddBooks;
use app\models\search\BookIssueSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * BookIssueController implements the CRUD actions for BookIssue model.
 */
class BookIssueController extends Controller
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
     * Lists all BookIssue models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new BookIssueSearch();
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        //$searchModel->status = 'issued';
        //$searchModel->status = 'renewal';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionReturnRole()
    {
        $searchModel = new BookIssueSearch();
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        $searchModel->user_id = Yii::$app->user->identity->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('return', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BookIssue model.
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
     * Creates a new BookIssue model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BookIssue(); 

        if ($model->load(Yii::$app->request->post())) {
            $array_bookIssue=yii::$app->request->post('BookIssue');
            $model2=AddBooks::find()->where(['id'=>$model->book_id])->one();
            if($model2->remaining_copies == 0){
            Yii::$app->session->setFlash('Warning', "Sorry! This Book Is no loger available");
            return $this->redirect(['create']);
            die;
            
            }if($model2->remaining_copies > 0){
                
                 $remingingBookstockmius= $model2->remaining_copies-1;
                //echo $model->book_id;
                //die;
                $model2->remaining_copies=$remingingBookstockmius;
                $model2->save();
                //$update_discount_inactive   = "UPDATE add_books  SET remaining_copies= '".$remingingBookstockmius."' WHERE fk_branch_id = ".Yii::$app->common->getBranch()." and id =".$model->book_id;
                //$model2->save();
            }
            //print_r($array_bookIssue);die;
             if(!empty($array_bookIssue['user_ids'])){

            $model->user_id=$array_bookIssue['user_ids'];
             }
            if($model->save()){
                Yii::$app->session->setFlash('success', "Successfully Issued");

            return $this->redirect(['index']);
            }else{
                print_r($model->getErrors());die;
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                //'model2' => $model2,
            ]);
        }
    }

    /**
     * Updates an existing BookIssue model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $model2=AddBooks::find()->where(['id'=>$model->book_id])->one();
            if($model2){
                
                 $remingingBookstockmius= $model2->remaining_copies+1;
                //echo $model->book_id;
                //die;
                $model2->remaining_copies=$remingingBookstockmius;
                $model2->save();
                //$update_discount_inactive   = "UPDATE add_books  SET remaining_copies= '".$remingingBookstockmius."' WHERE fk_branch_id = ".Yii::$app->common->getBranch()." and id =".$model->book_id;
                //$model2->save();
            }
            $model->save();
            Yii::$app->session->setFlash('success', "Successfully Return");
            return $this->redirect(['index']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing BookIssue model.
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
     * Finds the BookIssue model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BookIssue the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BookIssue::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
