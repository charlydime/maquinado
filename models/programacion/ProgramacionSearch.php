<?php

namespace frontend\models\programacion;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\programacion\programacion;

/**
 * ProgramacionesSearch represents the model behind the search form about `frontend\models\programacion\programaciones`.
 */
class ProgramacionSearch extends programacion
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdProgramacion', 'IdPedido', 'IdUsuario', 'IdProgramacionEstatus', 'IdProducto', 'Programadas', 'Hechas'], 'integer'],
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
        $query = programaciones::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'IdProgramacion' => $this->IdProgramacion,
            'IdPedido' => $this->IdPedido,
            'IdUsuario' => $this->IdUsuario,
            'IdProgramacionEstatus' => $this->IdProgramacionEstatus,
            'IdProducto' => $this->IdProducto,
            'Programadas' => $this->Programadas,
            'Hechas' => $this->Hechas,
        ]);

        return $dataProvider;
    }
}
