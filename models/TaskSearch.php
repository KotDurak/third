<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Task;

/**
 * TaskSearch represents the model behind the search form of `app\models\Task`.
 */
class TaskSearch extends Task
{
    public $chain;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status', 'id_user', 'id_manager', 'id_project'], 'integer'],
            [['name', 'description', 'deadline', 'created', 'chain', 'stage'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Task::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        $query->joinWith('chain');

        $dataProvider->sort->attributes['chain'] = [
            'asc' => ['chain.name' => SORT_ASC],
            'desc' => ['chain.name' => SORT_DESC],
        ];

        $query->andFilterWhere(['stage' => $this->stage]);

       $query->andFilterWhere(['like', 'chain.name', $this->chain]);
        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'id_user' => $this->id_user,
            'id_manager' => $this->id_manager,
            'id_project' => $this->id_project,
            'deadline' => $this->deadline,
            'DATE(created)' => $this->created,
        ]);

        $query->andFilterWhere(['like', Task::tableName().'.name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }
}
