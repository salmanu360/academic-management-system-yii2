<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "fee_arears".
 *
 * @property integer $id
 * @property integer $stu_id
 * @property integer $fee_head_id
 * @property integer $arears
 * @property string $date
 * @property integer $status
 * @property integer $branch_id
 */
class FeeArears extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $class;
    public $group;
    public $section;
    public static function tableName()
    {
        return 'fee_arears';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['stu_id', 'fee_head_id', 'arears', 'date', 'status', 'branch_id','from_date'], 'required'],
            // ['stu_id', 'unique','message'=>'This student is already exists', 'targetClass' => '\app\models\FeeArears','on' => 'create'],
            [['stu_id', 'fee_head_id', 'arears', 'status', 'branch_id'], 'integer'],
            [['date','from_date'], 'safe'],
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
            'arears' => 'Arears',
            'date' => 'Date',
            'status' => 'Status',
            'branch_id' => 'Branch ID',
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
