<?php

namespace app\controllers;

use Yii;
use app\models\Receivable;
use app\models\search\ReceivableSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use mPDF;

/**
 * ReceivableController implements the CRUD actions for Receivable model.
 */
class ReceivableController extends Controller
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
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Receivable models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new ReceivableSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Receivable model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Receivable #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $this->findModel($id),
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
        }else{
            return $this->render('view', [
                'model' => $this->findModel($id),
            ]);
        }
    }

    /**
     * Creates a new Receivable model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Receivable();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Receivable",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Receivable",
                    'content'=>'<span class="text-success">Create Receivable success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Receivable",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing Receivable model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update Receivable #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Receivable #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Receivable #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            /*
            *   Process for non-ajax request
            */
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing Receivable model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }


    }

     /**
     * Delete multiple existing Receivable model.
     * For ajax request will return json object
     * and for non-ajax request if deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            /*
            *   Process for non-ajax request
            */
            return $this->redirect(['index']);
        }
       
    }

    /**
     * Finds the Receivable model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Receivable the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Receivable::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
    public function actionReceivableReport(){
     $todayRecievalble=Receivable::find()->where(['branch_id'=>yii::$app->common->getBranch(),'date(created_date
      )'=>date('Y-m-d')])->all();
     $sql = 'SELECT * FROM receivable WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) and branch_id="'.yii::$app->common->getBranch().'"';
     $currentMonth = Receivable::findBySql($sql)->all();
     return $this->render('report/receivable-report',[
        'todayRecievalble'=>$todayRecievalble,
        'currentMonth'=>$currentMonth
    ]);
    }
    public function actionTodayReceivablePdf(){
     $todayRecievalble=Receivable::find()->where(['branch_id'=>yii::$app->common->getBranch(),'date(created_date
      )'=>date('Y-m-d')])->all();
     $donwloads=$this->renderAjax('report/today-receivable-pdf',['todayRecievalble'=>$todayRecievalble]);
     $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($donwloads);
        $indexx=$mpdf->Output('Today Receivable Report'.date("d-m-Y").'.pdf', 'D'); 
    }
    public function actionCurrentmonthReceivablePdf(){
      $sql = 'SELECT * FROM receivable WHERE MONTH(created_date) = MONTH(CURRENT_DATE()) and branch_id="'.yii::$app->common->getBranch().'"';
     $currentMonth = Receivable::findBySql($sql)->all();
     $currentMonthView=$this->renderAjax('report/month-receivable-pdf',['currentMonth'=>$currentMonth]);
     //echo $currentMonthView;die;
    $this->layout = 'pdf';
    $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
    $mpdf->WriteHTML($currentMonthView);
    $indexx=$mpdf->Output('current Month Receivable Report'.date("d-m-Y").'.pdf', 'D'); 
    }

    public function actionReceivableDateReport(){
        if(!isset($_GET['startcnvrt'])){
        $start= Yii::$app->request->post('start');
        $end = Yii::$app->request->post('end');
        $startcnvrt=date('Y-m-d',strtotime($start));
        $endcnvrt=date('Y-m-d',strtotime($end));
        $where = "branch_id='".yii::$app->common->getBranch()."' and created_date BETWEEN '".$startcnvrt."' and '".$endcnvrt."'";
        $dateRecievable=Receivable::find()->where($where)->all();
        if(count($dateRecievable)>0){
        $showDateReceivable=$this->renderAjax('report/date-receivable',['dateRecievable'=>$dateRecievable,'startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt]);
        }else{
            $showDateReceivable='<div class="alert alert-danger">No Record Found..!</div>';
        }
         return json_encode(['showDateReceivable'=>$showDateReceivable]);
        }else{
        $startcnvrt= Yii::$app->request->get('startcnvrt');
        $endcnvrt = Yii::$app->request->get('endcnvrt');
        $where = "branch_id='".yii::$app->common->getBranch()."' and created_date BETWEEN '".$startcnvrt."' and '".$endcnvrt."'";
        $dateRecievable=Receivable::find()->where($where)->all();
        $showDateReceivable=$this->renderAjax('report/date-receivable',['dateRecievable'=>$dateRecievable,'startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($showDateReceivable);
        $indexx=$mpdf->Output('date wise Receivable Report'.date("d-m-Y").'.pdf', 'D'); 
        }  
    }
} //end of class
