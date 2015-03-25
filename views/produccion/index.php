
<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\Modal;

$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="easyui-layout" style="width:100%;height:750px;">
    <div data-options="region:'center',border:false">
        <div class="easyui-layout" data-options="fit:true">
            <div data-options="region:'north',split:false,border:false" style="height:50px">encabezado</div>
            <div data-options="region:'west',border:true,split:true" title="Programado" style="width:20%"></div>
            <div data-options="region:'center',border:false">
                <div class="easyui-layout" data-options="fit:true">
                    <div class="easyui-tabs" style="width:100%;height:700px" data-options="tabPosition:'left'">
                        <div title="Produccion" style="padding:10px">
                            aqui se captura la produccion
                        </div>
                        <div title="Tiempo Muerto" style="padding:10px">
                            aqui se captura el tiempo muerto
                        </div>
                        <div title="Temperaturas" data-options="" style="padding:10px">
                            Aqui se captura Temperaturas
                        </div>
                        <div title="Rechazo" data-options="" style="padding:10px">
                            Aqui se captura el rechazo
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
