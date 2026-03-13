<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "student_outside".
 *
 * @property integer $id
 * @property string $name
 * @property integer $class_id
 * @property integer $group_id
 * @property integer $section_id
 * @property integer $parent_name
 * @property string $regesteration_date
 * @property integer $contact_no
 * @property integer $address
 * @property string $status
 * @property integer $branch_id
 */
class StudentOutside extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'student_outside';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'class_id', 'section_id', 'parent_name', 'regesteration_date', 'contact_no', 'address', 'branch_id'], 'required'],
            [['class_id', 'group_id', 'section_id', 'branch_id'], 'integer'],
            [['regesteration_date','organization','parent_contact'], 'safe'],
            [['address'], 'string'], 
            [['name','parent_name','contact_no','organization'], 'string', 'max' => 555],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'class_id' => 'Class',
            'group_id' => 'Group',
            'section_id' => 'Section',
            'parent_name' => 'Parent Name',
            'regesteration_date' => 'Regesteration Date',
            'contact_no' => 'Contact No',
            'address' => 'Address',
            'branch_id' => 'Branch ID',
        ];
    }
    public function getClass()
    {
        return $this->hasOne(RefClass::className(), ['class_id' => 'class_id']);
    }
     public function getGroup()
    {
        return $this->hasOne(RefGroup::className(), ['group_id' => 'group_id']);
    }
     public function getSection()
    {
        return $this->hasOne(RefSection::className(), ['section_id' => 'section_id']);
    }
}
