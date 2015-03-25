<?php

namespace frontend\Models\Maquinado;
use Yii;
use yii\base\Model;

Class MaquinadoCTA extends Model {
    
    public function GetMaquinaPieza() {
        
        $command = \Yii::$app->db_maquinado;
        $result =$command->createCommand("
                 
select 
cta.PRODUCTO, 
cta.existencia_cta,
maq.Maquina,
maq.Minutos,
maq.siguiente,
maq.Minutos1maquinado as min_setup,
maq.Minutos*cta.existencia_cta as min_prod,
FORMAT( (maq.Minutos*cta.existencia_cta)/60,2 ) as horaprod,
FORMAT(((maq.Minutos*cta.existencia_cta)/60)/8 ,2 ) as turno8,
FORMAT(((maq.Minutos*cta.existencia_cta)/60)/9 ,2 )as turno9
from v_producto_en_cta  as cta
LEFT JOIN  pdp_maquina_pieza  as maq on cta.PRODUCTO = maq.Pieza and maq.op = 10"
                )->queryAll();
        
        if(count($result)!=0){
          return $result;   
        }   
        
        return null;
    }
   
        
    
}