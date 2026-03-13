<?php

namespace app\controllers;

use Yii;
use app\models\Expenses;
use app\models\search\ExpensesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use mPDF;

/**
 * ExpensesController implements the CRUD actions for Expenses model.
 */
class ExpensesController extends Controller
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
    public function actionShabo(){
      echo '<h1>This is shabo page</h1>';
    }

    /**
     * Lists all Expenses models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new ExpensesSearch();
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Displays a single Expenses model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "Expenses #".$id,
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
     * Creates a new Expenses model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new Expenses();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Expenses",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new Expenses",
                    'content'=>'<span class="text-success">Create Expenses success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new Expenses",
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
     * Updates an existing Expenses model.
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
                    'title'=> "Update Expenses #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Expenses #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update Expenses #".$id,
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
     * Delete an existing Expenses model.
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
     * Delete multiple existing Expenses model.
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

    public function actionExpenseReport(){
     $todayExpense=Expenses::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'date(date)'=>date('Y-m-d')])->all();
     $sql = 'SELECT * FROM expenses WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE()) and fk_branch_id="'.yii::$app->common->getBranch().'"';
     $currentMonth = Expenses::findBySql($sql)->all();
     return $this->render('report/expense-report',['todayExpense'=>$todayExpense,'currentMonth'=>$currentMonth]);
    }
      public function actionTodayExpense(){
        $todayExpense=Expenses::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'date(date)'=>date('Y-m-d')])->all();
        return $todayFeeView = $this->render('report/today-expense',['todayExpense'=>$todayExpense]);
      }

    public function actionTodayExpensePdf(){
     $todayExpense=Expenses::find()->where(['fk_branch_id'=>yii::$app->common->getBranch(),'date(date)'=>date('Y-m-d')])->all();
     $donwloads=$this->renderAjax('report/today-expense-pdf',['todayExpense'=>$todayExpense]);
     $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($donwloads);
        $indexx=$mpdf->Output('Today Expense Report'.date("d-m-Y").'.pdf', 'D'); 
    }
    public function actionMonthExpensePdf(){
     $sql = 'SELECT * FROM expenses WHERE MONTH(date) = MONTH(CURRENT_DATE()) AND YEAR(date) = YEAR(CURRENT_DATE()) and fk_branch_id="'.yii::$app->common->getBranch().'"';
     $currentMonth = Expenses::findBySql($sql)->all();
     $donwloads=$this->renderAjax('report/month-expense-pdf',['currentMonth'=>$currentMonth]);
     $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($donwloads);
        $indexx=$mpdf->Output('Month Expense Report'.date("d-m-Y").'.pdf', 'D'); 
    }

    public function actionExpenseDateReport(){
        $start= Yii::$app->request->post('start');
        $end = Yii::$app->request->post('end');
        $startcnvrt=date('Y-m-d',strtotime($start));
        $endcnvrt=date('Y-m-d',strtotime($end));
        $where = "fk_branch_id='".yii::$app->common->getBranch()."' and date BETWEEN '".$startcnvrt."' and '".$endcnvrt."'";
        $dateExpense=Expenses::find()->where($where)->all();
        $showDateExpense=$this->renderAjax('report/date-expense',['dateExpense'=>$dateExpense,'startcnvrt'=>$startcnvrt,'endcnvrt'=>$endcnvrt]);
         return json_encode(['showDateExpense'=>$showDateExpense]);   
    }
     public function actionDateExpensePdf(){
         $start= Yii::$app->request->get('start');
         $end = Yii::$app->request->get('end');
        $where = "fk_branch_id='".yii::$app->common->getBranch()."' and date BETWEEN '".$start."' and '".$end."'";
        $dateExpense=Expenses::find()->where($where)->all();
        $showDateExpense=$this->renderAjax('report/date-expense-pdf',['dateExpense'=>$dateExpense,'startcnvrt'=>$start,'endcnvrt'=>$end]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($showDateExpense);
        $indexx=$mpdf->Output('date Expense Report'.date("d-m-Y").'.pdf', 'D');     
    }

    /**
     * Finds the Expenses model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Expenses the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Expenses::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
