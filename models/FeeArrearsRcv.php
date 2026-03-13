<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fee_arrears_rcv".
 *
 * @property integer $id
 * @property integer $class_id
 * @property integer $group_id
 * @property integer $section_id
 * @property integer $stu_id
 * @property integer $fee_head_id
 * @property integer $amount
 * @property string $created_date
 * @property integer $branch_id
 */
class FeeArrearsRcv extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'fee_arrears_rcv';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['class_id', 'section_id', 'stu_id', 'fee_head_id', 'amount', 'created_date', 'branch_id'], 'required'],
            [['class_id', 'group_id', 'section_id', 'stu_id', 'fee_head_id', 'amount', 'branch_id'], 'integer'],
            [['created_date'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'class_id' => 'Class ID',
            'group_id' => 'Group ID',
            'section_id' => 'Section ID',
            'stu_id' => 'Stu ID',
            'fee_head_id' => 'Fee Head ID',
            'amount' => 'Amount',
            'created_date' => 'Created Date',
            'branch_id' => 'Branch ID',
        ];
    }
}
