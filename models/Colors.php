<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "colors".
 *
 * @property integer $id
 * @property string $headerbackgroud
 * @property string $siderbarbackgroud
 * @property string $headertextcolor
 * @property string $sidebartextcolor
 * @property integer $user_id
 * @property integer $branch_id
 */
class Colors extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'colors';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['headerbackgroud', 'siderbarbackgroud', 'sidebartextcolor', 'user_id', 'branch_id'], 'required'],
            [['user_id', 'branch_id'], 'integer'],
            [['headerbackgroud', 'siderbarbackgroud', 'sidebartextcolor'], 'string', 'max' => 55],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'headerbackgroud' => Yii::t('app', 'Header Background'),
            'siderbarbackgroud' => Yii::t('app', 'Siderbar Background'),
            'sidebartextcolor' => Yii::t('app', 'Text Color'),
            'user_id' => Yii::t('app', 'User ID'),
            'branch_id' => Yii::t('app', 'Branch ID'),
        ];
    }
}
