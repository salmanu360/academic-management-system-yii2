<?php
namespace app\controllers;
use Yii;
use app\models\RefClass;
use app\models\search\RefClassSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;
class ClassController extends Controller
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
        $searchModel = new RefClassSearch();
        $searchModel->fk_branch_id = Yii::$app->common->getBranch();
        $searchModel->status = 'active';
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination  = false;

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
                    'title'=> "RefClass #".$id,
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
        $model = new RefClass();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                    'title'=> "Create new RefClass",
                    'content'=>$this->renderAjax('create', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
        
                ];         
            }else if($model->load($request->post())){
                $model->fk_branch_id = Yii::$app->common->getBranch();
                if($model->save()){
                    return [
                        'forceReload'=>'#crud-datatable-pjax',
                        'title'=> "Create new RefClass",
                        'content'=>'<span class="text-success">Create RefClass success</span>',
                        'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

                    ];
                }else{
                    return [
                        'title'=> "Create new RefClass",
                        'content'=>$this->renderAjax('create', [
                            'model' => $model,
                        ]),
                        'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])

                    ];
                }
            }else{
                return [
                    'title'=> "Create new RefClass",
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
                $model->fk_branch_id = Yii::$app->common->getBranch();
                if($model->save()){
                    return $this->redirect(['view', 'id' => $model->class_id]);
                }else {
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
       
    }
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
                    'title'=> "Update RefClass #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                    'forceReload'=>'#crud-datatable-pjax',
                    'title'=> "RefClass #".$id,
                    'content'=>$this->renderAjax('view', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                            Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
                 return [
                    'title'=> "Update RefClass #".$id,
                    'content'=>$this->renderAjax('update', [
                        'model' => $model,
                    ]),
                    'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];        
            }
        }else{
           
            if ($model->load($request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->class_id]);
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
        $model= $this->findModel($id);
        $model->delete();
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
    protected function findModel($id)
    {
        if (($model = RefClass::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /** * start of classes bulk insertion*/
    
    public function actionBulkClasses()
    {
        $model = new RefClass();
      $class_array=Yii::$app->request->post('RefClass');

        return $this->render('bulk-classes', [
                'model' => $model,
             ]);
   }
            
    public function actionBulkClassesInsert()
    {
      $class_array=Yii::$app->request->post('RefClass');
       $cl=$class_array['title'];
       foreach($cl as $days){
                        $mdl=new RefClass();
                        $mdl->title=$days;
                        $mdl->fk_branch_id=yii::$app->common->getBranch();
                        $mdl->fk_session_id=$class_array['fk_session_id'];
                        if($mdl->save()){
                         $this->redirect(['index']);
                         Yii::$app->session->setFlash('success','Students Shuffle Successfully');
                        }else{
                            print_r($mdl->getErrors());die;
                        }
                     }
    }
    /** * end of classes bulk insertion*/
} // end of main class