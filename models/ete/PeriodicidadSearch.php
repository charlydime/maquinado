<?php

namespace frontend\models\ete;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\ete\periodicidad;

/**
 * PeriodicidadSearch represents the model behind the search form about `frontend\models\ete\periodicidad`.
 */
class PeriodicidadSearch extends periodicidad
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'hrs', 'min_sem', 'min_dia'], 'integer'],
            [['periodo', 'lun', 'mar', 'mie', 'jue', 'vie', 'sab', 'dom'], 'safe'],
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
        $query = periodicidad::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($this->load($params) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'hrs' => $this->hrs,
            'min_sem' => $this->min_sem,
            'min_dia' => $this->min_dia,
        ]);

        $query->andFilterWhere(['like', 'periodo', $this->periodo])
            ->andFilterWhere(['like', 'lun', $this->lun])
            ->andFilterWhere(['like', 'mar', $this->mar])
            ->andFilterWhere(['like', 'mie', $this->mie])
            ->andFilterWhere(['like', 'jue', $this->jue])
            ->andFilterWhere(['like', 'vie', $this->vie])
            ->andFilterWhere(['like', 'sab', $this->sab])
            ->andFilterWhere(['like', 'dom', $this->dom]);

        return $dataProvider;
    }
}
