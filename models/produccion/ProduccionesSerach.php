<?php

namespace frontend\models\produccion;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\produccion\Producciones;

/**
 * ProduccionesSerach represents the model behind the search form about `frontend\models\produccion\Producciones`.
 */
class ProduccionesSerach extends Producciones
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdProduccion', 'IdCentroTrabajo', 'IdMaquina', 'IdUsuario', 'IdProduccionEstatus', 'IdProceso'], 'integer'],
            [['Fecha'], 'safe'],
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
        $query = Producciones::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'IdProduccion' => $this->IdProduccion,
            'IdCentroTrabajo' => $this->IdCentroTrabajo,
            'IdMaquina' => $this->IdMaquina,
            'IdUsuario' => $this->IdUsuario,
            'IdProduccionEstatus' => $this->IdProduccionEstatus,
            'Fecha' => $this->Fecha,
            'IdProceso' => $this->IdProceso,
        ]);

        return $dataProvider;
    }
}
