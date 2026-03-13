<?php

namespace app\controllers;

use Yii;
use app\models\LeaveApplication;
use app\models\search\LeaveApplicationSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * LeaveApplicationController implements the CRUD actions for LeaveApplication model.
 */
class LeaveApplicationController extends Controller
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
     * Lists all LeaveApplication models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LeaveApplicationSearch();
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        $searchModel->login_id = Yii::$app->user->identity->id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionApproval()
    {
        $searchModel = new LeaveApplicationSearch();
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('approval', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single LeaveApplication model.
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
     * Creates a new LeaveApplication model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new LeaveApplication();

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
            Yii::$app->session->setFlash('create', "Application successfully submited, Kindly wait for approval");
            return $this->redirect(['index']);
            }else{print_r($model->getErrors());die;}
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing LeaveApplication model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
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

    /**
     * Deletes an existing LeaveApplication model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionApproved($id){
     // echo $id;die;
        $model= LeaveApplication::findOne($id);
        $model->approval_status = '2';
        $model->save();
        if (!Yii::$app->request->isAjax) {
            Yii::$app->session->setFlash('approved', "Application Approved successfully");
            return $this->redirect(['index']);
        }

    }

    public function actionNotApproved($id){
     // echo $id;die;
        $model= LeaveApplication::findOne($id);
        $model->approval_status = '1';
        $model->save();
        if (!Yii::$app->request->isAjax) {
            Yii::$app->session->setFlash('Warning', "Application Not Approved successfully");
            return $this->redirect(['index']);
        }

    }

    /**
     * Finds the LeaveApplication model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return LeaveApplication the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = LeaveApplication::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
