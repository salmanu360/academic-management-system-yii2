<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "sms".
 *
 * @property int $id
 * @property int $price
 * @property string $description
 * @property string $validity
 * @property string $total_sms
 * @property int $status
 * @property string $purchased
 * @property string $purchased_request_date
 * @property string $purchased_date
 * @property string $date_created
 */
class Sms extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sms';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['price', 'description', 'validity', 'total_sms'], 'required'],
            [['price', 'status'], 'integer'],
            [['description'], 'string'],
            [['date_created'], 'safe'],
            [['validity', 'total_sms', 'purchased', 'purchased_request_date', 'purchased_date'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'price' => 'Price',
            'description' => 'Description',
            'validity' => 'Validity',
            'total_sms' => 'Total Sms',
            'status' => 'Status',
            'purchased' => 'Purchased',
            'purchased_request_date' => 'Purchased Request Date',
            'purchased_date' => 'Purchased Date',
            'date_created' => 'Date Created',
        ];
    }
}
