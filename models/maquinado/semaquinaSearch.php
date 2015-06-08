<?php

namespace frontend\models\maquinado;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\maquinado\PdpMaquinaBl;

/**
 * semaquinaSearch represents the model behind the search form about `frontend\models\maquinado\PdpMaquinaBl`.
 */
class semaquinaSearch extends PdpMaquinaBl
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pieza', 'fecha'], 'safe'],
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
        $query = PdpMaquinaBl::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if ($this->load($params) && !$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'fecha' => $this->fecha,
        ]);

        $query->andFilterWhere(['like', 'pieza', $this->pieza]);

        return $dataProvider;
    }
}
