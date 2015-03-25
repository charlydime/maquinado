<?php

namespace frontend\controllers;

use Yii;
use common\models\dux\VProductos;
use common\models\dux\Productos;
use common\models\dux\VMarcas;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ProductosController implements the CRUD actions for Productos model.
 */
class ProductosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Productos models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new VMarcas();
        $dataProvider = $model->getMarcas();
        //var_dump($dataProvider); exit;
        return $this->render('index', [
            'title' => 'Datos Adicionales',
            'model' => $model,
            'marcas' => $dataProvider,
        ]);
    }
    
    public function actionData_productos()
    {
        $this->layout = 'JSON';
        
        $model = new VProductos();
        $marca = isset($_POST['marca']) ? $_POST['marca'] : "2";
        $dataProvider = $model->getProductos($marca);

        //var_dump($dataProvider);exit;
        return json_encode([
                'total'=>count($dataProvider->allModels),
                'rows'=>$dataProvider->allModels,
        ]);
    }

    /**
     * Displays a single Productos model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Productos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Productos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->IdProducto]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Productos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionMoldeo($id)
    {
        if (($model = Productos::findOne($id)) === null) {
            return false;
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return true;
        } else {
            return $this->renderPartial('update', [
                'model' => $model,
                'form' => '_formMoldeo',
            ]);
        }
    }
    
    public function actionAlmas($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return true;
        } else {
            return $this->renderPartial('update', [
                'model' => $model,
                'form' => '_formMoldeo',
            ]);
        }
    }

    public function actionData_almas()
    {
        $this->layout = 'JSON';
        $semana1 = $semana1 == '' ? array(date('Y'),date('W')) : explode('-W',$semana1);
        $semanas['semana1'] = ['año'=>$semana1[0],'semana'=>$semana1[1],'value'=>"$semana1[0]-W$semana1[1]"];
        $semanas['semana2'] = $this->checarSemana($semanas['semana1']);
        $semanas['semana3'] = $this->checarSemana($semanas['semana2']);
        
        $programacion = new Programacion();
        $dataProvider = $programacion->getProgramacionSemanal($semanas);
        
        return json_encode([
                'total'=>count($dataProvider->allModels),
                'rows'=>$dataProvider->allModels,
        ]);
    }
    
    /**
     * Deletes an existing Productos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Productos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Productos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Productos::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
