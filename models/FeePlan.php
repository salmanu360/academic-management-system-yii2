<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fee_plan".
 *
 * @property integer $id
 * @property integer $stu_id
 * @property integer $fee_head_id
 * @property integer $discount
 * @property integer $branch_id
 */
class FeePlan extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fee_plan';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stu_id', 'fee_head_id', 'discount', 'branch_id','fk_fee_discounts_type_id'], 'required'],
            [['stu_id', 'fee_head_id', 'discount', 'branch_id','fk_fee_discounts_type_id'], 'integer'],
            [['fk_fee_discounts_type_id'],'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stu_id' => 'Student',
            'fee_head_id' => 'Fee Head',
            'discount' => 'Discount',
            'branch_id' => 'Branch ID',
            'fk_fee_discounts_type_id' => 'Fee Discount Type',
        ];
    }
    public function getHead()
    {
        return $this->hasOne(FeeHead::className(), ['id' => 'fee_head_id']);
    }
    public function getStudent()
    {
        return $this->hasOne(StudentInfo::className(), ['stu_id' => 'stu_id']);
    }
    
}
