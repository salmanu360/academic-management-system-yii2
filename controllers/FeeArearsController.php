<?php

namespace app\controllers;

use Yii;
use app\models\FeeArears;
use app\models\FeeArrearsRcv;
use app\models\search\FeeArearsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use mPDF;
use yii\data\ActiveDataProvider;
class FeeArearsController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                    'bulk-delete' => ['post'],
                    'updateexists-arrears' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {    
        $searchModel = new FeeArearsSearch();
        $searchModel->status=1;
        $searchModel->branch_id = Yii::$app->common->getBranch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=30;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionList() //for update arrears
    {    
        $searchModel = new FeeArearsSearch();
        $searchModel->status=1;
        $searchModel->branch_id = Yii::$app->common->getBranch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=30;
        return $this->render('indexA', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionUpdateArrears()
    {    
        $searchModel = new FeeArearsSearch();
        $inputVal=Yii::$app->request->post('val');
        $studentDetails = FeeArears::find()
            ->select(['fee_arears.*'])
            ->innerJoin('student_info','student_info.stu_id = fee_arears.stu_id')
            ->innerJoin('user','user.id = student_info.user_id')
            ->where(['user.username'=>$inputVal,'fee_arears.branch_id'=> Yii::$app->common->getBranch()])
            ->orWhere(['=','user.first_name',$inputVal])
            ->andWhere(['fee_arears.status'=>1]);
        $dataProvider = new ActiveDataProvider([
                  'query' => $studentDetails,
                  'pagination' => [
                  'pageSize' => 10,
                  ], ]);
        $details= $this->renderAjax('update-arrears', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        return json_encode(['ajaxCrudDatatable'=>$details]);
    }

public function actionUpdateArrearsView($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);       
        return $this->render('update-arrears-view', [
                    'model' => $model,
                ]);
    }

    public function actionUpdateexistsArrears($id){ // for update arrears
        $ids=base64_decode($id);
        $model=FeeArears::findOne(['id'=>$ids]);
        $data=Yii::$app->request->post('FeeArears');
        $model->arears=$data['arears'];
        $model->date=$data['date'];
        $model->save();
        Yii::$app->session->setFlash('success', 'Arrears submitted successfully..!');
        $this->redirect(['list']);
    }
    public function actionSearchArrears()
    {    
        $searchModel = new FeeArearsSearch();
        $inputVal=Yii::$app->request->post('val');
        $studentDetails = FeeArears::find()
            ->select(['fee_arears.*'])
            ->innerJoin('student_info','student_info.stu_id = fee_arears.stu_id')
            ->innerJoin('user','user.id = student_info.user_id')
            ->where(['user.username'=>$inputVal,'fee_arears.branch_id'=> Yii::$app->common->getBranch()])
            ->orWhere(['like','user.first_name',$inputVal])
            ->andWhere(['fee_arears.status'=>1]);
        $dataProvider = new ActiveDataProvider([
                  'query' => $studentDetails,
                  'pagination' => [
                  'pageSize' => 10,
                  ], ]);
        $details= $this->renderAjax('search', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        return json_encode(['ajaxCrudDatatable'=>$details]);
    }

    public function actionDownload(){
        $arrears=\app\models\FeeArears::find()->where(['branch_id'=>yii::$app->common->getBranch(),'status'=>1])->all();
        $donwloads= $this->renderAjax('download', [
            'arrears' => $arrears,
        ]);
        $this->layout = 'pdf';
        $mpdf = new mPDF('','', 0, '', 2, 2, 3, 3, 2, 2, 'A4');
        $mpdf->WriteHTML($donwloads);
        $indexx=$mpdf->Output('all-fee-arrears'.date("d-m-Y").'.pdf', 'D'); 

    }
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "FeeArears #".$id,
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
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new FeeArears(); 
        // $model->scenario = 'create'; 
        if($request->isAjax){
            
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new FeeArears",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                $data=Yii::$app->request->post('FeeArears');
                $stuId=$data['stu_id'];
                $fee_head_id=$data['fee_head_id'];
                $updateStatus     = "UPDATE  fee_arears  SET status = 0 WHERE branch_id = ".Yii::$app->common->getBranch()." and fee_head_id =".$fee_head_id." and stu_id =".$stuId;
                    \Yii::$app->db->createCommand($updateStatus)->execute();
                if($model->save()){}else{print_r($model->getErrors());die;}
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "Create new FeeArears",
                    'content'=>'<span class="text-success">Create FeeArears success</span>',
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])
        
                ];         
            }else{           
                return [
                    'title'=> "Create new FeeArears",
                    'content'=>$this->renderAjax('create', [
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
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }
       
    }
    public function actionUpdate($id)
    {
        $request = Yii::$app->request;
        $model = $this->findModel($id);  
        $fee_arrears_old_amount= $model->arears;

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update FeeArears #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post())){

                /*if($model->arears == 0){
                 $this->findModel($id)->delete();
                 $this->redirect('index');
                }else{*/
                   // echo '<pre>';print_r($_POST);die;
                    if($model->save()){
                        if($model->arears == 0){
                        $this->findModel($id)->delete();
                        $this->redirect('index');
                }
                $model2 = new FeeArrearsRcv();
               $arrayArrears=yii::$app->request->post('FeeArears');
                //echo '<pre>';print_r($arrayArrears);die;

                $model2->class_id=$arrayArrears['class'];
                $model2->group_id=$arrayArrears['group'];
                $model2->section_id=$arrayArrears['section'];
                $model2->stu_id=$arrayArrears['stu_id'];
                $model2->fee_head_id=$arrayArrears['fee_head_id'];
                $model2->created_date=date('Y-m-d');
                $model2->from_date=$arrayArrears['from_date'];
                $model2->amount=$fee_arrears_old_amount-$arrayArrears['arears'];
                $model2->branch_id=yii::$app->common->getBranch();
                if(!$model2->save()){
                    print_r($model2->getErrors());die;
                }
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "FeeArears #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];
               }else{
                return [
                    'title'=> "Update FeeArears #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ]; 
               }
                //} //end of else
                //echo '<pre>';print_r($_POST);die; 
                    
            }else{
                 return [
                    'title'=> "Update FeeArears #".$id,
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
            if ($model->load($request->post())) {
                echo '<pre>';print_r($_POST);die;
                $model->save();
                return $this->redirect(['view', 'id' => $model->id]);
            } else {
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }
    }

    /**
     * Delete an existing FeeArears model.
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
     * Delete multiple existing FeeArears model.
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
     * Finds the FeeArears model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return FeeArears the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = FeeArears::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
