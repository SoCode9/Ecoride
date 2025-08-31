<div class="popup" id="<?= $popup_id ?>" style="display:none;">
    <h3 class=" m-tb-12"><?= $popup_title ?></h3>
    <div class="block-column-g20">

        <?= $popup_content ?>

    </div>
    <button type="button" class="col-back-grey-btn btn" style="justify-self:right;"
        onclick="closePopup('<?= $popup_id ?>')">Annuler</button>
</div>