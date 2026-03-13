<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "transport_allocation".
 *
 * @property integer $id
 * @property integer $stop_id
 * @property integer $zone_id
 * @property integer $route_id
 * @property integer $stu_id
 * @property integer $discount_amount
 * @property integer $status
 * @property string $allotment_date
 * @property string $created_date
 * @property integer $branch_id
 */
class TransportAllocation extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'transport_allocation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_stop_id', 'zone_id', 'route_id', 'stu_id', 'allotment_date', 'branch_id'], 'required'],
            [['fk_stop_id', 'zone_id', 'route_id', 'stu_id', 'status', 'branch_id'], 'integer'],
            //tow fields
            [['stu_id'], 'unique','message'=>'Already allocated Stop to this student','targetAttribute' => ['stu_id']],

            [['allotment_date', 'created_date','discount_amount'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_stop_id' => 'Stop',
            'zone_id' => 'Zone',
            'route_id' => 'Route',
            'stu_id' => 'Student',
            'status' => 'Status',
            'discount_amount' => 'Discount',
            'allotment_date' => 'Allotment Date',
            'created_date' => 'Created Date',
            'branch_id' => 'Branch ID',
        ];
    }

    public function getFkRoute()
    {
        return $this->hasOne(Route::className(), ['id' => 'route_id']);
    }
    public function getzone()
    {
        return $this->hasOne(Zone::className(), ['id' => 'zone_id']);
    }
    public function getstop()
    {
        return $this->hasOne(Stop::className(), ['id' => 'fk_stop_id']);
    }
}
