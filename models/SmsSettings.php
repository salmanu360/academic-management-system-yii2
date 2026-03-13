<?php
namespace app\models;
use Yii;
class SmsSettings extends \yii\db\ActiveRecord
{
   
    public static function tableName()
    {
        return 'sms_settings';
    }

    public function rules()
    {
        return [
            [['status', 'date', 'sms_expiry_date', 'fk_branch_id','school_name'], 'required'],
            [['status'], 'string'],
            [['date', 'sms_expiry_date','mask','school_name'], 'safe'],
            [['fk_branch_id'], 'integer'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'date' => 'Date',
            'sms_expiry_date' => 'Sms Expiry Date',
            'fk_branch_id' => 'Fk Branch ID',
        ];
    }
}