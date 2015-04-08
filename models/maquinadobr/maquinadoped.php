<?php

namespace frontend\Models\Maquinado;
use Yii;
use yii\base\Model;

Class MaquinadoPED extends Model {
    
    public function GetPorMaquinar($fecha) {
        
        
        if ( $fecha == '' )
                $fecha = date('Y-m-d');
        $command = \Yii::$app->db_maquinado;
       // $result =$command->createCommand("select * from  v_prod_ped")->queryAll();
        $result =$command->createCommand("call sp_paramaquinado('$fecha')")->queryAll();
        
         if(count($result)!=0){
          return $result;   
         }
         
         return null; 
    }
    
    public function GetMaquinaPieza() {
        
        $command = \Yii::$app->db_maquinado;
        $result =$command->createCommand("select * from pdp_maquina_pieza")->queryAll();
        
        if(count($result)!=0){
          return $result;   
        }   
        
        return null;
    }
    
     public function elemento_maquinapieza($val) {
        
        $command = \Yii::$app->db_maquinado;
        $result =$command->createCommand("select * from pdp_maquina_pieza where Pieza = '$val'")->queryOne();
        
        if(count($result)!=0){
          return $result;   
        }   
        
        return null;
    }
    
   
    
}