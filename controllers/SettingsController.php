<?php

namespace app\controllers;

use app\models\FeeHead;
use Yii;
use app\models\Settings;
use yii\helpers\ArrayHelper;
use app\models\search\SettingsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Signature;
use app\models\DashboardSetting;
use yii\data\ActiveDataProvider;

/**
 * SettingsController implements the CRUD actions for Settings model.
 */
class SettingsController extends Controller
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
     * Lists all Settings models.
     * @return mixed
     */
    public function actionIndex()
    {
        /*$searchModel = new SettingsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);*/ 
        $model =  Settings::find()->where(['fk_branch_id'=> Yii::$app->common->getBranch()])->One();
        $modelFeeHeads = FeeHead::find()->where(['branch_id'=>Yii::$app->common->getBranch()])->all();
        $arrayFeeHead = ArrayHelper::map($modelFeeHeads,'id','title');


        if(count($model) == 0){
            $model = new Settings();
        }
        if ($model->load(Yii::$app->request->post())) {
            $data=Yii::$app->request->post('Settings');
            $startDate=$data['current_session_start'];
            $model->current_session_end= $futureDate=date('Y-m-d', strtotime('+1 year', strtotime($startDate)) );
            $model->fk_branch_id=Yii::$app->common->getBranch();
            if($model->save()){
                \Yii::$app->getSession()->setFlash('success', 'Settings has been updated.');
                return $this->render('settings', [
                    'model' => $model,
                    'modelHead' => $arrayFeeHead,
                    'modelFeeHeads' => $modelFeeHeads,
                ]);
            }
        } else {
            return $this->render('settings', [
                'model' => $model,
                'modelHead' => $arrayFeeHead,
                'modelFeeHeads' => $modelFeeHeads,
            ]);
        }
    }

    public function actionDashSetting(){
        $model=DashboardSetting::find()->one();
        if(count($model) == 0){
            $model = new DashboardSetting();
        }
        if ($model->load(Yii::$app->request->post())) {
           $data=Yii::$app->request->post('DashboardSetting');
            if(!empty($data['fee_all']) && !empty($data['total_fee_date'])){
             Yii::$app->session->setFlash('error', "Select only one");
             return $this->redirect(['dash-setting']);
            }
            $model->fk_branch_id=Yii::$app->common->getBranch();
            $model->save();
            Yii::$app->session->setFlash('success', "Setting successfully saved");
        }
        return $this->render('dashboard_setting',['model'=>$model]);
    }

    /**
     * Displays a single Settings model.
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
     * Creates a new Settings model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Settings();

        if ($model->load(Yii::$app->request->post())) {
            $model->fk_branch_id = yii::$app->common->getBranch();
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                return $this->render('create', [
                    'model' => $model,

                ]);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Settings model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {

        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            if($model->save()){
                return $this->redirect(['view', 'id' => $model->id]);
            }else{
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Settings model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    

    /**
     * Finds the Settings model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Settings the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Settings::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    public function actionSignature()
    {
        $model=new Signature();
        $signatureData=Signature::find()->all();
        $query1=Signature::find()->where(['branch_id'=>Yii::$app->common->getBranch()]);
            $provider = new ActiveDataProvider([
                'query' => $query1,
            ]);
        return $this->render('signature',['model'=>$model,'provider'=>$provider]);
    }
    public function actionSaveSign()
    {
        $data=Yii::$app->request->post();
        $exist=Signature::find()->where(['branch_id'=>Yii::$app->common->getBranch(),'category'=>$data['categoryId']])->one();
        if(count($exist)>0){
            $result= Yii::$app->session->setFlash('warning', "Signature already exist against this category");
            echo json_encode($result);
        }else{
        $result = array();
        $imagedata = base64_decode($data['img_data']);
        $filename = md5(date("dmYhisA"));
        $filePath= Yii::$app->basePath . '/web/uploads/doc_signs/';
        $file_name = $filePath.$filename.'.png';
        $model=new Signature();
        $model->image=$filename;
        $model->user_id=Yii::$app->user->id;
        $model->category=$data['categoryId'];
        $model->branch_id=Yii::$app->common->getBranch();
        $model->save();
        file_put_contents($file_name,$imagedata);
        $result['status'] = 1;
        $result['file_name'] = $file_name;
        echo json_encode($result);
    }
    }
    public function actionDelete($id)
    {
        $query=Signature::find()->where(['branch_id'=>Yii::$app->common->getBranch(),'id'=>$id])->one();
        $filePath= Yii::$app->basePath . '/web/uploads/doc_signs/'.$query->image.'.png';
        unlink($filePath);
        Signature::findone($id)->delete();
        return $this->redirect(['signature']);
    }
}
