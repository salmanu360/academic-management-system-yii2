<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mesages_other_send".
 *
 * @property integer $id
 * @property integer $person_id
 * @property string $message
 * @property string $date
 */
class MesagesOtherSend extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mesages_other_send';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['person_id', 'message', 'date'], 'required'],
            [['person_id'], 'integer'],
            [['date'], 'safe'],
            [['message'], 'string', 'max' => 1255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'person_id' => 'Person ID',
            'message' => 'Message',
            'date' => 'Date',
        ];
    }
}
