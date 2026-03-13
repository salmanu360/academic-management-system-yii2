<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "signature".
 *
 * @property integer $id
 * @property integer $category
 * @property string $image
 * @property integer $user_id
 * @property integer $branch_id
 */
class Signature extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'signature';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['category', 'image', 'user_id', 'branch_id'], 'required'],
            [['category', 'user_id', 'branch_id'], 'integer'],
            [['image'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category' => Yii::t('app', 'Category'),
            'image' => Yii::t('app', 'Image'),
            'user_id' => Yii::t('app', 'User ID'),
            'branch_id' => Yii::t('app', 'Branch ID'),
        ];
    }
}
