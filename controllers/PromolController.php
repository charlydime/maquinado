<?php

namespace frontend\controllers;


use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PromolController extends Controller
{
	
	//reporte promol
	public function actionPromol(){
		
		
		
		if ( isset ($_REQUEST["sem"]) ){
			$sem = $_REQUEST["sem"] ;
		}else{
			$sem = "22";
		}
		
		return $this->render('calculo', [ 'sem' => $sem ]);
		
	
		
	}
	
	
	
	
	
	
}