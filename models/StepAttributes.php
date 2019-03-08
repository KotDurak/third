<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "step_attributes".
 *
 * @property int $id
 * @property int $id_step
 * @property string $name
 * @property string $index
 * @property string $def_value
 *
 * @property AttributesValues[] $attributesValues
 * @property Steps $step
 */
class StepAttributes extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'step_attributes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_step'], 'integer'],
            [['name', 'index', 'def_value'], 'string', 'max' => 255],
            [['index'], 'unique'],
            [['index', 'name'], 'required'],
            [['index'], 'match', 'pattern' => '/^[a-zA-Z0-9]+$/'],
            [['id_step'], 'exist', 'skipOnError' => true, 'targetClass' => Steps::className(), 'targetAttribute' => ['id_step' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_step' => 'Id Step',
            'name' => 'Name',
            'index' => 'Index',
            'def_value' => 'Def Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttributesValues()
    {
        return $this->hasMany(AttributesValues::className(), ['id_attribute' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStep()
    {
        return $this->hasOne(Steps::className(), ['id' => 'id_step']);
    }
}
