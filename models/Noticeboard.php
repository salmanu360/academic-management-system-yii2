<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "noticeboard".
 *
 * @property integer $id
 * @property integer $fk_branch_id
 * @property string $title
 * @property string $notice
 * @property string $date
   @property string $end_date 
 *
 * @property Branch $fkBranch
 */
class Noticeboard extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'noticeboard';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['fk_branch_id', 'title', 'date'], 'required'],
            [['fk_branch_id'], 'integer'],
            [['title', 'notice'], 'string', 'max' => 555],
            [['date','end_date'], 'string', 'max' => 255],
            [['fk_branch_id'], 'exist', 'skipOnError' => true, 'targetClass' => Branch::className(), 'targetAttribute' => ['fk_branch_id' => 'id']],
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
            'title' => 'Title',
            'notice' => 'Notice',
            'date' => 'Date',
            'end_date' => 'End Date',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFkBranch()
    {
        return $this->hasOne(Branch::className(), ['id' => 'fk_branch_id']);
    }
}
