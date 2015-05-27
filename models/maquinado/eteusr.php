<?php

namespace frontend\Models\Maquinado;
use Yii;
use yii\base\Model;
use common\models\User;



Class Eteusr extends Model {
    
public function altamasiva(){
	echo "alta de usuarios <br>";
	
	$empleados =  file_get_contents('ete_usr.csv',true);
	$empleados = str_getcsv($empleados,"\n");
	
	
	
	foreach($empleados as $e){
	
			$e = str_getcsv($e,",");
			
			// print_r($e);
			echo "  $e[0] , $e[1], $e[2], $e[3] <br>";
			
			$user = new User();
            $user->IdEmpleado = utf8_encode ( $e[0] ) ;
            $user->username = utf8_encode ( $e[1] ) ;
            $user->setPassword( utf8_encode ( $e[2] ) );
            $user->email = utf8_encode ( $e[3] ) ;
            $user->generateAuthKey();
            $user->save();
	}
	
}
   

	
}