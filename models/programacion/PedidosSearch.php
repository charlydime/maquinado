<?php

namespace frontend\models\programacion;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use frontend\models\programacion\Pedidos;

/**
 * PedidosSearch represents the model behind the search form about `app\models\programacion\Pedidos`.
 */
class PedidosSearch extends Pedidos
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdPedido', 'IdAlmacen', 'IdProducto', 'Codigo', 'Numero', 'Estatus', 'NivelRiesgo'], 'integer'],
            [['Fecha', 'Cliente', 'OrdenCompra', 'FechaEmbarque', 'Observaciones'], 'safe'],
            [['Cantidad', 'SaldoCantidad', 'TotalProgramado'], 'number'],
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
        $query = Pedidos::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'IdPedido' => $this->IdPedido,
            'IdAlmacen' => $this->IdAlmacen,
            'IdProducto' => $this->IdProducto,
            'Codigo' => $this->Codigo,
            'Numero' => $this->Numero,
            'Fecha' => $this->Fecha,
            'Estatus' => $this->Estatus,
            'Cantidad' => $this->Cantidad,
            'SaldoCantidad' => $this->SaldoCantidad,
            'FechaEmbarque' => $this->FechaEmbarque,
            'NivelRiesgo' => $this->NivelRiesgo,
            'TotalProgramado' => $this->TotalProgramado,
        ]);

        $query->andFilterWhere(['like', 'Cliente', $this->Cliente])
            ->andFilterWhere(['like', 'OrdenCompra', $this->OrdenCompra])
            ->andFilterWhere(['like', 'Observaciones', $this->Observaciones]);

        return $dataProvider;
    }
}
