<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Seguidores;

/**
 * SeguidoresSearch represents the model behind the search form of `app\models\Seguidores`.
 */
class SeguidoresSearch extends Seguidores
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'seguidor_id', 'seguido_id'], 'integer'],
            [['created_at'], 'safe'],
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
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Seguidores::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 5]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'created_at' => $this->created_at,
            'seguidor_id' => $this->seguidor_id,
            'seguido_id' => $this->seguido_id,
        ])->andWhere([
            'ended_at' => null,
            'blocked_at' => null
        ]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param $params
     *
     * @return ActiveDataProvider
     */
    public function searchBlocked($params)
    {
        $query = Seguidores::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => ['pageSize' => 5]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query
            ->andFilterWhere([
                'id' => $this->id,
                'created_at' => $this->created_at,
                'seguidor_id' => $this->seguidor_id,
                'seguido_id' => $this->seguido_id,
            ])
            ->andWhere(['ended_at' => null,])
            ->andWhere(['not', ['blocked_at' => null]]);

        return $dataProvider;
    }
}
