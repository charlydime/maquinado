<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\maquinado\pdpmaquina;

/**
 * maquinaSearch represents the model behind the search form about `frontend\models\maquinado\pdpmaquina`.
 */
class maquinaSearch extends pdpmaquina
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['Maquina', 'Descripcion', 'Area', 'activa'], 'safe'],
            [['TiempoDisponible'], 'number'],
            [['id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
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
        $query = pdpmaquina::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($this->load($params) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'TiempoDisponible' => $this->TiempoDisponible,
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'Maquina', $this->Maquina])
            ->andFilterWhere(['like', 'Descripcion', $this->Descripcion])
            ->andFilterWhere(['like', 'Area', $this->Area])
            ->andFilterWhere(['like', 'activa', $this->activa]);

        return $dataProvider;
    }
}
