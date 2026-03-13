<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fine_class_wise".
 *
 * @property integer $id
 * @property integer $class_id
 * @property integer $amount
 * @property string $status
 * @property string $created_at
 */
class FineClassWise extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fine_class_wise';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_id', 'amount', 'status'], 'required'],
            [['class_id', 'amount'], 'integer'],
            [['status'], 'string'],
            [['created_at'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_id' => 'Class',
            'amount' => 'Amount',
            'status' => 'Status',
            'created_at' => 'Created At',
        ];
    }
}
