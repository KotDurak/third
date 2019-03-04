<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "attributes_values".
 *
 * @property int $id
 * @property int $id_attribute
 * @property int $id_step_clone
 * @property string $value
 *
 * @property StepAttributes $attribute0
 * @property ChainClonesSteps $stepClone
 */
class AttributesValues extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'attributes_values';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_attribute', 'id_step_clone'], 'integer'],
            [['value'], 'string', 'max' => 255],
            [['id_attribute'], 'exist', 'skipOnError' => true, 'targetClass' => StepAttributes::className(), 'targetAttribute' => ['id_attribute' => 'id']],
            [['id_step_clone'], 'exist', 'skipOnError' => true, 'targetClass' => ChainClonesSteps::className(), 'targetAttribute' => ['id_step_clone' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_attribute' => 'Id Attribute',
            'id_step_clone' => 'Id Step Clone',
            'value' => 'Value',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAttribute0()
    {
        return $this->hasOne(StepAttributes::className(), ['id' => 'id_attribute']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getStepClone()
    {
        return $this->hasOne(ChainClonesSteps::className(), ['id' => 'id_step_clone']);
    }
}
