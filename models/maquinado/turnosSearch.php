<?php

namespace frontend\models\maquinado;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\maquinado\Turnos;

/**
 * turnosSearch represents the model behind the search form about `frontend\models\maquinado\Turnos`.
 */
class turnosSearch extends Turnos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['idturno'], 'integer'],
            [['Turno', 'hinicio', 'htermino', 'area', 'ncorto'], 'safe'],
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
        $query = Turnos::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($this->load($params) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'idturno' => $this->idturno,
            'hinicio' => $this->hinicio,
            'htermino' => $this->htermino,
        ]);

        $query->andFilterWhere(['like', 'Turno', $this->Turno])
            ->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'ncorto', $this->ncorto]);

        return $dataProvider;
    }
}
