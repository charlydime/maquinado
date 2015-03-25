<?php

namespace frontend\models\programacion;

use Yii;

/**
 * This is the model class for table "v_programacionesDia".
 *
 * @property integer $IdProgramacionDia
 * @property integer $IdProgramacionSemana
 * @property string $Dia
 * @property integer $Prioridad
 * @property integer $Programadas
 * @property integer $Hechas
 * @property integer $IdProceso
 * @property integer $IdArea
 * @property string $Proceso
 * @property integer $Secuencia
 * @property integer $IdPedido
 * @property integer $IdAlmacen
 * @property integer $IdProducto
 * @property integer $Codigo
 * @property integer $Numero
 * @property string $Producto
 * @property string $Almacen
 * @property string $Fecha
 * @property string $Cliente
 * @property string $OrdenCompra
 * @property integer $Estatus
 * @property string $Cantidad
 * @property string $SaldoCantidad
 * @property string $FechaEmbarque
 * @property integer $NivelRiesgo
 * @property string $TotalProgramado
 * @property string $Observaciones
 * @property integer $PiezasMolde
 * @property integer $CiclosMolde
 * @property string $PesoCasting
 * @property string $PesoArania
 * @property integer $Pedido
 * @property integer $pedido_hecho
 * @property integer $programadas_semana
 * @property integer $hechas_semana
 * @property integer $prioridad_semana
 * @property integer $Anio
 * @property integer $Semana
 * @property integer $IdTurno
 * @property string $Turno
 * @property integer $IdPresentacion
 * @property integer $IdProgramacion
 */
class VProgramacionesDia extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'v_programacionesDia';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['IdProgramacionDia', 'IdProgramacionSemana', 'Dia', 'Prioridad', 'Programadas', 'Hechas', 'IdProceso', 'IdArea', 'Proceso', 'Secuencia', 'IdPedido', 'IdAlmacen', 'IdProducto', 'Codigo', 'Numero', 'Fecha', 'Estatus', 'Cantidad', 'SaldoCantidad', 'NivelRiesgo', 'TotalProgramado', 'PiezasMolde', 'CiclosMolde', 'PesoCasting', 'PesoArania', 'Pedido', 'pedido_hecho', 'programadas_semana', 'hechas_semana', 'prioridad_semana', 'Anio', 'Semana', 'IdTurno', 'IdPresentacion', 'IdProgramacion'], 'required'],
            [['IdProgramacionDia', 'IdProgramacionSemana', 'Prioridad', 'Programadas', 'Hechas', 'IdProceso', 'IdArea', 'Secuencia', 'IdPedido', 'IdAlmacen', 'IdProducto', 'Codigo', 'Numero', 'Estatus', 'NivelRiesgo', 'PiezasMolde', 'CiclosMolde', 'Pedido', 'pedido_hecho', 'programadas_semana', 'hechas_semana', 'prioridad_semana', 'Anio', 'Semana', 'IdTurno', 'IdPresentacion', 'IdProgramacion'], 'integer'],
            [['Dia', 'Fecha', 'FechaEmbarque'], 'safe'],
            [['Proceso', 'Producto', 'Almacen', 'Cliente', 'OrdenCompra', 'Observaciones', 'Turno'], 'string'],
            [['Cantidad', 'SaldoCantidad', 'TotalProgramado', 'PesoCasting', 'PesoArania'], 'number']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'IdProgramacionDia' => 'Id Programacion Dia',
            'IdProgramacionSemana' => 'Id Programacion Semana',
            'Dia' => 'Dia',
            'Prioridad' => 'Prioridad',
            'Programadas' => 'Programadas',
            'Hechas' => 'Hechas',
            'IdProceso' => 'Id Proceso',
            'IdArea' => 'Id Area',
            'Proceso' => 'Proceso',
            'Secuencia' => 'Secuencia',
            'IdPedido' => 'Id Pedido',
            'IdAlmacen' => 'Id Almacen',
            'IdProducto' => 'Id Producto',
            'Codigo' => 'Codigo',
            'Numero' => 'Numero',
            'Producto' => 'Producto',
            'Almacen' => 'Almacen',
            'Fecha' => 'Fecha',
            'Cliente' => 'Cliente',
            'OrdenCompra' => 'Orden Compra',
            'Estatus' => 'Estatus',
            'Cantidad' => 'Cantidad',
            'SaldoCantidad' => 'Saldo Cantidad',
            'FechaEmbarque' => 'Fecha Embarque',
            'NivelRiesgo' => 'Nivel Riesgo',
            'TotalProgramado' => 'Total Programado',
            'Observaciones' => 'Observaciones',
            'PiezasMolde' => 'Piezas Molde',
            'CiclosMolde' => 'Ciclos Molde',
            'PesoCasting' => 'Peso Casting',
            'PesoArania' => 'Peso Arania',
            'Pedido' => 'Pedido',
            'pedido_hecho' => 'Pedido Hecho',
            'programadas_semana' => 'Programadas Semana',
            'hechas_semana' => 'Hechas Semana',
            'prioridad_semana' => 'Prioridad Semana',
            'Anio' => 'Anio',
            'Semana' => 'Semana',
            'IdTurno' => 'Id Turno',
            'Turno' => 'Turno',
            'IdPresentacion' => 'Id Presentacion',
            'IdProgramacion' => 'Id Programacion',
        ];
    }
}
