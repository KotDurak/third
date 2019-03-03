<?php

namespace app\models;

use Yii;
use moonland\phpexcel\Excel;
/**
 * This is the model class for table "project".
 *
 * @property int $id
 * @property string $name
 * @property string $url
 */
class Project extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'project';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name', 'url'], 'string', 'max' => 255],
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
            'url' => 'Url',
        ];
    }

    public static function getRowTasks($fileName)
    {
        $rows_excell = Excel::widget([
            'mode'  => 'import',
            'fileName'  => $fileName,
            'setFirstRecordAsKeys' => false,
            'setIndexSheetByName' => true,
        ]);
        $tasks = [];
        $table = [];
        $count = 0;
        $task_name = '';
        foreach ((array)$rows_excell as $num => $row){
            $row = (array)$row;
            if(!empty($row['A']) && !empty($row['E']) && $row['E'] > 0){
                $table[] = $row;
            } else if(!empty($row['A']) && (empty($row['E']) || $row['E'] < 1)){
                $task_name = $row['A'];
                continue;
            } else if(empty($row['E']) && empty($row['F']) && !empty($table)){
                $tasks[] = [
                    'name'  => $task_name,
                    'table' => $table
                ];
                $table = [];
                $count++;
            }
        }
        return $tasks;
    }
}
