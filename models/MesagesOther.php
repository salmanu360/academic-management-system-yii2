<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "mesages_other".
 *
 * @property integer $id
 * @property string $name
 * @property string $designation
 * @property string $organization
 * @property string $date
 * @property string $address
 * @property integer $fk_branch_id
 */
class MesagesOther extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mesages_other';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'date', 'fk_branch_id','contact'], 'required'],
            [['date'], 'safe'],
            [['fk_branch_id'], 'integer'],
            [['name', 'designation', 'organization'], 'string', 'max' => 555],
            [['address'], 'string', 'max' => 855],
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
            'designation' => 'Designation',
            'organization' => 'Organization',
            'date' => 'Date',
            'address' => 'Address',
            'fk_branch_id' => 'Fk Branch ID',
        ];
    }
}
