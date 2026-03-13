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
class ApiController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actionLogin()
{
    \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
    $users = \app\models\User::find()->all();
    echo  '<pre>';print_r($users);
}
}
