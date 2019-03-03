<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use kartik\file\FileInput;
use yii\helpers\Url;

$cb = <<<CB
    function(event, data, previewId, index){
         var form = data.form, files = data.files, extra = data.extra,
        response = data.response, reader = data.reader;
        console.log(response);
     }
CB;

?>
    <div class="modal-content animated bounceInTop" >
        <?php
        $form = ActiveForm::begin(['id' => 'import-form', 'options' => [
            'enctype'=>'multipart/form-data'
        ]]);
        ?>
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-left text-center">Импорт сементики и перелинковка</h4>
        </div>
        <div class="modal-body">
            <?= $form->field($import, 'file')->widget(FileInput::className(), [
                    'options' => [

                    ],
                    'pluginOptions'=>[
                        'showPreview' => false,
                        'uploadUrl' => Url::to(['/project/file-upload?id=' . $project->id])
                    ],
                    'pluginEvents'  => [
                       'fileuploaded' => $cb
                    ]
            ]) ?>

            <div class=" view-btn text-right">
                <button  type="button" class="btn btn-primary" data-dismiss="modal">Отмена</button>
                <?= Html::submitButton('Импорт', ['class' => 'btn btn-default', 'id' => 'proj=import']) ?>
            </div>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
<?php
$js = <<<JS
    $(document).ready(function() {
      $('#submit').on('click', function(e){
         e.preventDefault(); 
         var form_data = new FormData($('#form-add-project')[0]);
         var url = $('#form-add-project').attr('action');
        
         return false;
      });
    });
JS;
$this->registerJS($js);
?>