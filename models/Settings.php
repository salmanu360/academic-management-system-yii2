<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "settings".
 *
 * @property integer $id
 * @property integer $fk_branch_id
 * @property string $school_time_in
 * @property string $student_reg_type
 * @property string $school_time_out
 * @property string $fee_bank_name
 * @property string $fee_bank_account
 * @property string $salary_bank_name
 * @property string $salary_bank_account
 * @property string $sibling_discount
 * @property string $sibling_no_childs
 * @property string $theme_color
 * @property string $challan_copies
 *
 * @property Branch $fkBranch
 */
class Settings extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'settings';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['school_time_in', 'school_time_out','absent_fine','current_session_start','current_session_end'], 'required'],
            [['fk_branch_id'], 'integer'],
            [['fk_branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['fk_branch_id' => 'id']],
            [['school_time_in', 'school_time_out','sibling_discount','absent_fine','current_session_start','current_session_end','transport_on_off','fee_sms_on_off','employee_sms_on_off'], 'safe'],
            [['theme_color','sibling_no_childs','student_reg_type','challan_copies','failed_paper'], 'string'],
            [['fee_bank_name', 'salary_bank_name'], 'string', 'max' => 255],
            [['fee_bank_account', 'salary_bank_account'], 'string', 'max' => 100],
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
            'school_time_in' => 'School Time In', 
            'school_time_out' => 'School Time Out',
            'school_time_in' => 'School Time In',
            'school_time_out' => 'School Time Out',
            'fee_bank_name' => 'Fee Bank Name',
            'fee_bank_account' => 'Fee Bank Account',
            'salary_bank_name' => 'Salary Bank Name',
            'salary_bank_account' => 'Salary Bank Account',
            'theme_color' => 'Theme Color',
            'sibling_no_childs'=>'Sibling Number of Child\'s',
            'sibling_discount'=>'Sibling Discount', 
            'student_reg_type'=>'Student Registration Type',
            'challan_copies'=>'Challan Copies',

        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'fk_branch_id']);
    }

    /*public function beforeSave($insert)
    {
        if (parent::beforeSave($insert))
        {
            // Place your custom code here
            if($this->isNewRecord)
            {
                $this->fk_branch_id = Yii::$app->common->getBranch();
            }
            elseif(!$this->isNewRecord)
            {
                //$this->updated_at = new \yii\db\Expression('NOW()');
            }
            return true;
        }
        else
        {
            return false;
        }
    }*/
}
