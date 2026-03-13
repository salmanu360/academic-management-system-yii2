<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "addlibrary_category".
 *
 * @property integer $id
 * @property string $category_name
 * @property string $section_code
 * @property integer $fk_branch_id
 * @property string $status
 */
class AddlibraryCategory extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'addlibrary_category';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category_name', 'fk_branch_id', 'status'], 'required'],
            [['fk_branch_id'], 'integer'],
            [['status'], 'string'],
            [['category_name', 'section_code'], 'string', 'max' => 555],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'category_name' => 'Category Name',
            'section_code' => 'Section Code',
            'fk_branch_id' => 'Fk Branch ID',
            'status' => 'Status',
        ];
    }
}
