<?php
namespace app\controllers;
use app\models\StudentInfo;
use Yii;
use app\models\FeePlan;
use app\models\search\FeePlan as FeePlanSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
use yii\data\ActiveDataProvider;
class FeePlanController extends Controller
{   
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
    public function actionIndex()
    {    
        $searchModel = new FeePlanSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=10;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
                    'title'=> "FeePlan #".$id,
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
        $model = new FeePlan(); 
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new Fee Discount",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($request->post('FeePlan')){
                $array=$request->post('FeePlan');
                $discount=$array['discount'];
                $stu_id=$array['stu_id'];
                $stu_id = StudentInfo::find()->select(['stu_id'])->where(['user_id'=>$request->post('FeePlan')['stu_id']])->one();
                $update_fee_discount_rec     = "UPDATE  fee_plan  SET status = 0 WHERE branch_id = ".Yii::$app->common->getBranch()." and stu_id =".$stu_id->stu_id." and fee_head_id=".$request->post('FeePlan')['fee_head_id'];
                 \Yii::$app->db->createCommand($update_fee_discount_rec)->execute();
                $model->branch_id= \Yii::$app->common->getBranch();
                $model->stu_id= $stu_id->stu_id;
                $model->fee_head_id = $request->post('FeePlan')['fee_head_id'];
                $model->fk_fee_discounts_type_id = $request->post('FeePlan')['fk_fee_discounts_type_id'];
                $model->discount = $request->post('FeePlan')['discount'];
                if($model->save()){
                if($model->discount == 0){
                 $deleteId=$this->findModel($model->id)->delete();
            }
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Create new Fee Discount",
                        'content'=>'<span class="text-success">Create Fee Discount success</span>',
                        'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

                    ];
                }else{
                    return [
                        'title'=> "Create new Fee Discount",
                        'content'=>$this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])

                    ];
                }

            }else{           
                return [
                    'title'=> "Create new Fee Discount",
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
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Update FeePlan #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "FeePlan #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update FeePlan #".$id,
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
    }
    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $this->findModel($id)->delete();
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
    }
    public function actionBulkDelete()
    {        
        $request = Yii::$app->request;
        $pks = explode(',', $request->post( 'pks' )); // Array or selected records primary keys
        foreach ( $pks as $pk ) {
            $model = $this->findModel($pk);
            $model->delete();
        }
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ['forceClose'=>true,'forceReload'=>'#crud-datatable-pjax'];
        }else{
            return $this->redirect(['index']);
        }
       
    }
    public function actionSearchDiscount()
    {    
        $searchModel = new FeePlanSearch();
        $inputVal=Yii::$app->request->post('val');
        $studentDetails = FeePlan::find()
            ->select(['fee_plan.*'])
            ->innerJoin('student_info','student_info.stu_id = fee_plan.stu_id')
            ->innerJoin('user','user.id = student_info.user_id')
            ->where(['user.username'=>$inputVal])
            ->orWhere(['like','user.first_name',$inputVal])
            ->andWhere(['fee_plan.status'=>1]);
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
    protected function findModel($id)
    {
        if (($model = FeePlan::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}