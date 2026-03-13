<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "receivable_category".
 *
 * @property integer $id
 * @property string $title
 * @property integer $branch_id
 * @property string $created_date
 */
class ReceivableCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'receivable_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'branch_id', 'created_date'], 'required'],
            [['branch_id'], 'integer'],
            [['created_date'], 'safe'],
            [['title'], 'string', 'max' => 555],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'branch_id' => 'Branch ID',
            'created_date' => 'Created Date',
        ];
    }
}
