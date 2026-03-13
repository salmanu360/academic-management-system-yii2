<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fee_head".
 *
 * @property integer $id
 * @property string $title
 * @property integer $extra_head
 * @property integer $one_time_payment
 * @property string $date
 * @property integer $branch_id
 */
class FeeHead extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fee_head';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'date', 'branch_id'], 'required'],
            [['extra_head', 'one_time_payment','promotion_head', 'branch_id'], 'integer'],
            [['date'], 'safe'],
            [['title'], 'string', 'max' => 555],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'extra_head' => 'Extra Head',
            'one_time_payment' => 'One Time Payment',
            'date' => 'Date',
            'branch_id' => 'Branch ID',
        ];
    }
}
