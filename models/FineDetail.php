<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fine_detail".
 *
 * @property integer $id
 * @property integer $fk_branch_id
 * @property integer $fk_fine_typ_id
 * @property string $remarks
 * @property string $created_date
 * @property string $updated_date
 * @property integer $amount
 * @property string $is_active
 * @property integer $fk_stu_id
 * @property integer $payment_received
 */
class FineDetail extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fine_detail';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_branch_id', 'fk_fine_typ_id', 'created_date', 'amount', 'fk_stu_id'], 'required'],
            [['fk_branch_id', 'fk_fine_typ_id', 'amount', 'fk_stu_id', 'payment_received'], 'integer'],
            [['created_date', 'updated_date'], 'safe'],
            [['is_active'], 'string'],
            [['remarks'], 'string', 'max' => 300],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fk_branch_id' => 'Fk Branch ID',
            'fk_fine_typ_id' => 'Fine Type',
            'remarks' => 'Remarks',
            'created_date' => 'Created Date',
            'updated_date' => 'Updated Date',
            'amount' => 'Amount',
            'is_active' => 'Is Active',
            'fk_stu_id' => 'Fk Stu ID',
            'payment_received' => 'Payment Received',
        ];
    }

    public function getFkStudent()
    {
        return $this->hasOne(StudentInfo::className(), ['user_id' => 'fk_stu_id']);
    }
    public function getFineType()
    {
        return $this->hasOne(FineType::className(), ['id' => 'fk_fine_typ_id']);
    }
}
