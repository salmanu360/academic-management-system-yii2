<?php

namespace app\controllers;

use Yii;
use app\models\FeeSubmission;
use app\models\search\FeeSubmissionSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
/**
 * FeeSubmissionController implements the CRUD actions for FeeSubmission model.
 */
class FeeSubmissionController extends Controller
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
     * Lists all FeeSubmission models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new FeeSubmissionSearch();
        $inputVal=Yii::$app->request->post('val');
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         $dataProvider->pagination->pageSize=25;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionTranpostEditArears()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => FeeSubmission::find()->select(['id','fee_status','transport_arrears','stu_id'])->where(['fee_status'=>1])
            ->andWhere(['>','transport_arrears',0]),
            'pagination' => [
                  'pageSize' => 50,
                  ],
        ]);

        return $this->render('transport_arear_update', [
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionTransportEditForm($id)
    {
      $model =FeeSubmission::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
         $model->transport_arrears=$_POST['FeeSubmission']['transport_arrears'];
          $model->save();
            return $this->redirect(['tranpost-edit-arears']);
        }
        return $this->render('transport_edit_form', [
            'model' => $model,
        ]);
    }
    public function actionSearchGrid()
    {    
        $searchModel = new FeeSubmissionSearch();
        $inputVal=Yii::$app->request->post('val');
        $studentDetails = FeeSubmission::find()
              ->select(['fee_submission.*','student_info.*'])
              ->innerJoin('student_info','student_info.stu_id = fee_submission.stu_id')
              ->innerJoin('user','user.id = student_info.user_id')
              ->where(['user.first_name'=>$inputVal,'student_info.fk_branch_id'=>yii::$app->common->getBranch(),'student_info.is_active'=>1,'fee_submission.fee_status'=>1])
              ->orWhere(['=','user.username',$inputVal])
              ->orderBy(['fee_submission.id'=>SORT_DESC]);
        $dataProvider = new ActiveDataProvider([
                  'query' => $studentDetails,
                  'pagination' => [
                  'pageSize' => 25,
                  ], ]);
        $details= $this->renderAjax('search-index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        return json_encode(['ajaxCrudDatatable'=>$details]);
    }

    /**
     * Displays a single FeeSubmission model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "FeeSubmission #".$id,
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
     * Creates a new FeeSubmission model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new FeeSubmission();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new FeeSubmission",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post()) ){ 
                $feeSubmissionData = $request->post();   
                $class= $feeSubmissionData['FeeSubmission']['class'];
                $group= $feeSubmissionData['FeeSubmission']['group'];
                $section= $feeSubmissionData['FeeSubmission']['section'];
                $fee_group = \app\models\FeeGroup::find()->where(['fk_class_id'=>$class,'fk_group_id'=>($group!=null)?$group:null,'fk_branch_id'=>Yii::$app->common->getBranch(),'fk_fee_head_id'=>$model->fee_head_id])->one();
                if(count($fee_group)>0){
                    $total_head_amount = $fee_group->amount;
                } 
                $remaining_amount = $total_head_amount - $model->head_recv_amount; 
                if($remaining_amount < 0){ 
                    $model->addError('head_recv_amount','Amount must be less than total amount : '.$total_head_amount);
                    return [
                        'title'=> "Create new FeeSubmission",
                        'content'=>$this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                    ];  
                }else{
                    if($remaining_amount >0){
                        $update_fee_arears_rec     = "UPDATE  fee_arears  SET status = 0 WHERE branch_id = ".Yii::$app->common->getBranch()." and stu_id =".$model->stu_id; 
                        \Yii::$app->db->createCommand($update_fee_arears_rec)->execute();
                        $arrears = new \app\models\FeeArears();
                        $arrears->stu_id        = $model->stu_id;
                        $arrears->branch_id     = Yii::$app->common->getBranch();
                        $arrears->fee_head_id   = $model->fee_head_id;
                        $arrears->arears        = $remaining_amount;
                        $arrears->date          = date('Y-m-d');
                        $arrears->status        = 1;
                        $arrears->save();
                    } 
                    $model->year_month_interval = date('Y-m');
                    if($model->save()){

                        return [
                            'forceReload'=>'#crud-datatable-pjax',
                            'title'=> "Create new FeeSubmission",
                            'content'=>'<span class="text-success">Create FeeSubmission success</span>',
                            'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                    Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
                        ];  
                    }else{
                         return [
                            'title'=> "Create new FeeSubmission",
                            'content'=>$this->renderAjax('create', [
                                'model' => $model,
                            ]),
                            'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                        Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"]) ];  
                    }
                } 
                       
            }else{           
                return [
                    'title'=> "Create new FeeSubmission",
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
            if ($model->load($request->post())) {
                $feeSubmissionData = $request->post();   
                $totalAmount=$_POST['total_amount'];
                $class= $feeSubmissionData['FeeSubmission']['class'];
                $group= $feeSubmissionData['FeeSubmission']['group'];
                $section= $feeSubmissionData['FeeSubmission']['section'];
                $fee_group = \app\models\FeeGroup::find()->where(['fk_class_id'=>$class,'fk_group_id'=>($group!=null)?$group:null,'fk_branch_id'=>Yii::$app->common->getBranch(),'fk_fee_head_id'=>$model->fee_head_id])->one();
               
                

                $FeeSubmissionExsists = FeeSubmission::find()->where(['stu_id'=>$model->stu_id,'fee_head_id'=>$model->fee_head_id,'fee_status'=>1])->count();
                if($FeeSubmissionExsists > 0){
                    /*add flash message for unique fee admission entery*/
                        Yii::$app->session->setFlash('error', "This has already been taken.");
                        return $this->render('create', [
                                'model' => $model,
                            ]);  
                }else{
                    if(count($fee_group)>0){
                    $fee_plan = \app\models\FeePlan::find()->where(['fee_head_id'=>$model->fee_head_id,'stu_id'=>$model->stu_id])->one();#
                    if(count($fee_plan)>0){
                    $total_head_amount = $fee_group->amount - $fee_plan->discount;
                    }else{
                    $total_head_amount = $fee_group->amount;
                    }
                }else{
                    Yii::$app->session->setFlash('error', 'This Head is not assign to this class,please assign first');
                     return $this->redirect(['create']); 
               
                }
                    $remaining_amount = $totalAmount - $model->head_recv_amount; 
                    if($remaining_amount < 0){ 

                        /*$model->addError('head_recv_amount','Amount must be less than total amount : '.$total_head_amount);*/
                         Yii::$app->session->setFlash('error', 'Amount must be less than total amount : '.$total_head_amount);
                        return $this->render('create', [
                                'model' => $model,
                            ]);  
                    }else{
                        if($remaining_amount >0){
                           /* $update_fee_arears_rec     = "UPDATE  fee_arears  SET status = 0 WHERE branch_id = ".Yii::$app->common->getBranch()." and fee_head_id=".$model->fee_head_id." and stu_id =".$model->stu_id; 
                            \Yii::$app->db->createCommand($update_fee_arears_rec)->execute();*/
                            $arrears = new \app\models\FeeArears();
                            $arrears->stu_id        = $model->stu_id;
                            $arrears->branch_id     = Yii::$app->common->getBranch();
                            $arrears->fee_head_id   = $model->fee_head_id;
                            $arrears->arears        = $remaining_amount;
                            $arrears->date          = date('Y-m-d');
                            $arrears->from_date          = date('Y-m-d');
                            $arrears->status        = 1;
                            if($arrears->save()){}else{print_r($arrears->getErrors());die;};
                        }   
                        $model->year_month_interval = date('Y-m');
                        if($model->save()){ 
                            /*add session message and redirect to create page again.*/
                             Yii::$app->session->setFlash('success', 'Fee submitted successfully..!');
                            return $this->redirect(['create']); 
                        }else{
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    } 
                }
                
            } else {
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }

    /**
     * Updates an existing FeeSubmission model.
     * For ajax request will return json object
     * and for non-ajax request if update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdateFee($id){
       $model=FeeSubmission::findOne($id);
       $data=Yii::$app->request->post("FeeSubmission");
       $newFee=Yii::$app->request->post('newFee');
       $oldFee=$data['head_recv_amount'];
       $model->head_recv_amount=$oldFee+$newFee;
       $fee_head_id=$data['fee_head_id'];
       $stu_id=$data['stu_id'];
       $model->recv_date=date('Y-m-d');
       $feeArrears=\app\models\FeeArears::findOne(['stu_id'=>$stu_id,'fee_head_id'=>$fee_head_id,'status'=>1]);
       $arears=$feeArrears->arears;
       if($newFee > $arears){
        Yii::$app->session->setFlash('warning', "Amount should not be greater then current Arears");
        return $this->redirect(['update','id'=>$id]);
        }else{
       $totalArears=$arears-$newFee;
       $feeArrears->arears=$totalArears;
       $feeArrears->date=date('Y-m-d');
       $feeArrears->save();
       $model->save();
       Yii::$app->session->setFlash('success', "Fee updated");
       return $this->redirect(['index']);
   }
    }

    public function actionUpdateTransportArear($id){
       $model=FeeSubmission::findOne($id);
       $data=Yii::$app->request->post("FeeSubmission");
       $model->transport_amount=$data['transport_amount'];
       $model->transport_arrears=$data['transport_arrears'];
       $model->recv_date=date('Y-m-d');
       $model->save();
       Yii::$app->session->setFlash('success', "Fee updated");
       return $this->redirect(['index']);
    }

    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        return $this->renderAjax('update', [
                    'model' => $model,
                ]);
    }
    public function actionUpdateTransport($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        return $this->renderAjax('update-transport', [
                    'model' => $model,
                ]);
    }
    /*public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       

        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update FeeSubmission #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "FeeSubmission #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update FeeSubmission #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
            
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }*/

    /**
     * Delete an existing FeeSubmission model.
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
     * Delete multiple existing FeeSubmission model.
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
     * Finds the FeeSubmission model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FeeSubmission the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FeeSubmission::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    } // end of function

    public function actionGetStudentFee(){
        $stu_id=yii::$app->request->post('stu_id');
        $fee_head_id=yii::$app->request->post('fee_head_id');
        $class_id=yii::$app->request->post('class_id');
        $group=yii::$app->request->post('group');
        $fee_group = \app\models\FeeGroup::find()->where(['fk_class_id'=>$class_id,'fk_group_id'=>($group!=null)?$group:null,'fk_branch_id'=>Yii::$app->common->getBranch(),'fk_fee_head_id'=>$fee_head_id])->one();
        $feePlanQuery=\app\models\FeePlan::find()->where(['stu_id'=>$stu_id,'fee_head_id'=>$fee_head_id,'status'=>1])->one();
        if(count($feePlanQuery)>0){
           $headFee=$fee_group->amount-$feePlanQuery->discount;
        }else{
                $headFee = $fee_group->amount;
        }
        return json_encode(['headFee'=>$headFee]);
    } //end of function
    /*get one time head against class for one time head submission*/
    public function actionClassOneHead(){
        $class_id=yii::$app->request->post('classid');
        $group_id=yii::$app->request->post('groupid');
         $fee_group_query = \app\models\FeeGroup::find()
              ->select(['fee_group.*','fee_head.title'])
              ->innerJoin('fee_head','fee_head.id = fee_group.fk_fee_head_id')
              ->where(['fee_group.fk_class_id'=>$class_id,'fee_group.fk_group_id'=>($group_id)?$group_id:null,'fee_head.one_time_payment'=>1,'fee_head.promotion_head'=>0,'fee_head.extra_head'=>0])->all();
             //echo '<pre>';print_r($fee_group_query);die;
        $options='<option>Select Fee Head</option>';
        foreach ($fee_group_query as $key => $fee_groupValue) {
            $options.='<option value="'.$fee_groupValue->fk_fee_head_id.'">'.$fee_groupValue->fkFeeHead->title.'</option>';
        }
        return json_encode(['options'=>$options]);
    } 

}// end of class
