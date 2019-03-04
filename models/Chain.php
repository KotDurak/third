<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "chain".
 *
 * @property int $id
 * @property string $name
 *
 * @property ChainClones[] $chainClones
 * @property Steps[] $steps
 */
class Chain extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chain';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getChainClones()
    {
        return $this->hasMany(ChainClones::className(), ['id_chain' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSteps()
    {
        return $this->hasMany(Steps::className(), ['id_chain' => 'id']);
    }
}
