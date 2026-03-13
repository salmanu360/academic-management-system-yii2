<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fee_submission".
 *
 * @property integer $id
 * @property integer $stu_id
 * @property integer $fee_head_id
 * @property integer $head_recv_amount
 * @property string $from_date
 * @property string $to_date
 * @property string $recv_date
 * @property integer $fee_status
 * @property integer $branch_id
 */
class FeeSubmission extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $class;
    public $group;
    public $section;
    public static function tableName()
    {
        return 'fee_submission';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stu_id', 'fee_head_id', 'head_recv_amount', 'from_date', 'to_date', 'recv_date', 'branch_id'], 'required'],
            [['stu_id', 'fee_head_id', 'head_recv_amount', 'fee_status', 'branch_id'], 'integer'],
            [['recv_date'], 'safe'],
            [['from_date', 'to_date'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'stu_id' => 'Stu ID',
            'fee_head_id' => 'Fee Head ID',
            'head_recv_amount' => 'Head Recv Amount',
            'from_date' => 'From Date',
            'to_date' => 'To Date',
            'recv_date' => 'Recv Date',
            'fee_status' => 'Fee Status',
            'branch_id' => 'Branch ID',
        ];
    }

    public static function getTotal($provider, $fieldName)
{
    $total = 0;
    foreach ($provider as $item) {
        $total += $item[$fieldName];
    }
    return 'Rs. '.$total;
}
}
