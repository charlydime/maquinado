<?php

namespace frontend\models\ete;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\ete\empleado;

/**
 * empleadoSerarh represents the model behind the search form about `frontend\models\ete\empleado`.
 */
class empleadoSerarh extends empleado
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['nomina', 'idEmpleado', 'idturno'], 'integer'],
            [['fecha', 'area', 'periodicidad'], 'safe'],
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
        $query = empleado::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($this->load($params) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'nomina' => $this->nomina,
            'idEmpleado' => $this->idEmpleado,
            'idturno' => $this->idturno,
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['like', 'area', $this->area])
            ->andFilterWhere(['like', 'periodicidad', $this->periodicidad]);

        return $dataProvider;
    }
}
