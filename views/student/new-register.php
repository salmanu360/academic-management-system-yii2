<?php

use yii\helpers\Html; 
use yii\grid\GridView; 

/* @var $this yii\web\View */ 
/* @var $dataProvider yii\data\ActiveDataProvider */ 

$this->title = 'Today Registered Students'; 
?> 
<div class="box-primary">
  <div class="box box-body">

    <h1><?= Html::encode($this->title) ?></h1> 

    <?= GridView::widget([ 
        'dataProvider' => $dataProvider, 
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'], 
            'username',
            'first_name',
            'last_name',
            //'email:email',
            //'auth_key',
            //'password_hash',
            //'password_reset_token',
            //'pass',
            //'avatar',
            //'status',
            //'fk_role_id',
            //'last_ip_address',
            //'last_login',
            //'created_at',
            //'updated_at',
            //'name_in_urdu',
            //'Image',

            ['class' => 'yii\grid\ActionColumn',
            'template'=>'{update}',
            'buttons'=>[
                              'update' => function ($url, $model, $key)
                                {
                                   

                        return Html::a('<span class="glyphicon glyphicon-pencil toltip" data-placement="bottom" width="20" title="Update"></span>', ['student/edit','id'=>base64_encode($model->id)]); 
                                },
                            ] 
        ], 
        ], 
    ]); ?> 
</div> 
</div> 