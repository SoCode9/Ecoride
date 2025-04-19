<div class="popup" id="<?= $popup_id ?>" style="display:none;">
    <h3 class=" m-tb-12"><?= $popup_title ?></h3>
    <div class="block-column-g20">

        <!--popup content-->
        <?= $popup_content ?>

    </div>
    <button type="button" class="col-back-grey-btn btn" style="justify-self:right;"
        onclick="closePopup('<?= $popup_id ?>')">Annuler</button>
</div>


<?php if (!empty($popup_script)): ?>
    <script>
        <?= $popup_script ?>
    </script>
<?php endif; ?>