<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "dashboard_setting".
 *
 * @property int $id
 * @property int $fee_all
 * @property string $total_fee_date
 * @property int $fk_branch_id
 */
class DashboardSetting extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'dashboard_setting';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fee_all', 'fk_branch_id'], 'integer'],
            [['fk_branch_id'], 'required'],
            [['parent_portal_exam_result'], 'string'],
            [['total_fee_date'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fee_all' => 'Fee All',
            'parent_portal_exam_result' => 'Parent Portal Exam Result',
            'total_fee_date' => 'Total Fee Date',
            'fk_branch_id' => 'Fk Branch ID',
        ];
    }
}
