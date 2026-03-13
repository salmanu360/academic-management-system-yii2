<?php

namespace app\controllers;

use Yii;
use app\models\MesagesOther;
use app\models\MesagesOtherSend;
use app\models\search\MesagesOtherSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use \yii\web\Response;
use yii\helpers\Html;

/**
 * MesagesOtherController implements the CRUD actions for MesagesOther model.
 */
class MesagesOtherController extends Controller
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
    /**
     * Lists all MesagesOther models.
     * @return mixed
     */
    public function actionIndex()
    {    
        $searchModel = new MesagesOtherSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            ]);
    }

    public function actionContacts()
    {    
        $model = new MesagesOtherSend();
        if ($model->load(Yii::$app->request->post())) {
       $array=Yii::$app->request->post('MesagesOtherSend');
       $getNumber=MesagesOther::find()->where(['id'=>$array['person_id']])->all(); 
       $smsActive=\app\models\SmsSettings::find()->where(['fk_branch_id'=>yii::$app->common->getBranch()])->one();
       $person_id=$array['person_id']; 
       $message=$array['message'];
        foreach ($getNumber as $key=> $query){ 
        $model = new MesagesOtherSend();
            $cntact=$model->contact=$query->contact;
            $model->person_id=$query->id;
            $model->message=$message;
            $model->date=date('Y-m-d H:i:s');
           if($model->save()){
              if($smsActive->status == 1){
            Yii::$app->common->SendSmsSimple($cntact,$message);
        }
            Yii::$app->session->setFlash('success', "Message send successfully");
        }else{echo print_r($model->getErrors());die;}   
              //Yii::$app->common->SendSms($contct,$textArea,$stu_id);
        } 
        }
         
        return $this->render('getContactPersons', [
            'model' => $model,
            ]);
    
    }


    /**
     * Displays a single MesagesOther model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {   
        $request = Yii::$app->request;
        if($request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            return [
            'title'=> "MesagesOther #".$id,
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
     * Creates a new MesagesOther model.
     * For ajax request will return json object
     * and for non-ajax request if creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new MesagesOther();  

        if($request->isAjax){
            /*
            *   Process for ajax request
            */
            Yii::$app->response->format = Response::FORMAT_JSON;
            if($request->isGet){
                return [
                'title'=> "Create new MesagesOther",
                'content'=>$this->renderAjax('create', [
                    'model' => $model,
                    ]),
                'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])

                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                'forceReload'=>'#crud-datatable-pjax',
                'title'=> "Create new MesagesOther",
                'content'=>'<span class="text-success">Create MesagesOther success</span>',
                'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                Html::a('Create More',['create'],['class'=>'btn btn-primary','role'=>'modal-remote'])

                ];         
            }else{           
                return [
                'title'=> "Create new MesagesOther",
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
     * Updates an existing MesagesOther model.
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
                'title'=> "Update MesagesOther #".$id,
                'content'=>$this->renderAjax('update', [
                    'model' => $model,
                    ]),
                'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                Html::button('Save',['class'=>'btn btn-primary','type'=>"submit"])
                ];         
            }else if($model->load($request->post()) && $model->save()){
                return [
                'forceReload'=>'#crud-datatable-pjax',
                'title'=> "MesagesOther #".$id,
                'content'=>$this->renderAjax('view', [
                    'model' => $model,
                    ]),
                'footer'=> Html::button('Close',['class'=>'btn btn-default pull-left','data-dismiss'=>"modal"]).
                Html::a('Edit',['update','id'=>$id],['class'=>'btn btn-primary','role'=>'modal-remote'])
                ];    
            }else{
               return [
               'title'=> "Update MesagesOther #".$id,
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
     * Delete an existing MesagesOther model.
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
     * Delete multiple existing MesagesOther model.
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
     * Finds the MesagesOther model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return MesagesOther the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MesagesOther::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
