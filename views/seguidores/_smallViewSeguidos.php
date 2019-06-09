<?php

use app\models\Seguidores;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Seguidores */

\yii\web\YiiAsset::register($this);
$seguido = $model->seguido;
$seguidor = $model->seguidor;

$follow = Url::to(['seguidores/follow', 'seguido_id' => $seguido->id]);
$block = Url::to(['seguidores/block', 'seguido_id' => $seguido->id]);

if (($soySeguidor = Seguidores::soySeguidorOBloqueador($seguido->id)) !== null) {
    $esBloqueado = $soySeguidor->blocked_at !== null;
    $soySeguidor = $soySeguidor->blocked_at === null;
} else {
    $esBloqueado = $soySeguidor = false;
}
?>
<div class="usuarios-shows-view">

    <div class="col-xs-12 col-md-12 border-bottom-custom" style="padding-top: 5px; padding-bottom: 5px">
        <div class="col-xs-3 text-center">
            <?= Html::img($seguido->getImagenLink(), ['alt' => 'Enlace roto', 'class' => 'img-responsive img-circle', 'width' => '100%']) ?>

            <?php if ($seguido->id !== Yii::$app->user->id): ?>
                <?=
                Html::a(($soySeguidor ? 'Unfollow' : 'Follow'), $follow, [
                    'class' => 'btn col-md-6 col-xs-6 ' . ($soySeguidor ? 'btn-danger' : 'btn-success'),
                    'style' => ($esBloqueado ? 'display: none' : 'display: block'),
                    'onclick' => "
                        event.preventDefault();
                        btn = this;
                        $.ajax({
                            type : 'GET',
                            url : '$follow',
                            success: function(data) {
                                data = JSON.parse(data);
                                if (data == '') {
                                    $.notify({
                                        title: 'Error:',
                                        message: 'El usuario te tiene bloqueado.'
                                    }, {
                                        type: 'danger'
                                    });
                                } else {
                                    $.notify({
                                        title: data.message.tittle,
                                        message: data.message.content
                                    }, {
                                        type: data.message.type
                                    });
                                    $(btn).html(data.tittle).toggleClass(data.class);
                                }
                            }
                        });
                        return false;
                    ",
                ]);
                ?>
                <?=
                Html::a(($esBloqueado ? 'Desbloquear' : 'Bloquear'), $block, [
                    'class' => 'btn ' . ($esBloqueado ? 'col-md-12 col-xs-12 btn-success' : 'col-md-6 col-xs-6 btn-danger'),
                    'onclick' => "
                        event.preventDefault();
                        if(
                        $(this).html() == 'Desbloquear' ||
                        confirm('¿Estas seguro de bloquear a este usuario? Esto denegara el acceso a esta persona a tu perfil y no podras seguirlo.')
                        ) {
                            btn = this;
                            $.ajax({
                                type : 'GET',
                                url : '$block',
                                success: function(data) {
                                    sessionStorage.setItem('blockData', data);
                                    location.reload();
                                }
                            });
                        }
                        return false;
                    ",
                ]);
                ?>
            <?php endif; ?>
        </div>
        <div class="col-xs-9">
            <h3 style="margin: 0"><?= Html::a($seguido->nick, ['usuarios/view', 'id' => $seguido->id]) ?>
                <small><?= Html::encode($seguido->email) ?></small>
            </h3>
            <small>Seguido por <?= Html::a($seguidor->nick, ['usuarios/view', 'id' => $seguidor->id]) ?> desde el <?= Yii::$app->formatter->asDate($model->created_at, 'long') ?></small>
            <p>
                <?= Html::encode($seguido->biografia) ?>
            </p>
        </div>
    </div>

</div>
