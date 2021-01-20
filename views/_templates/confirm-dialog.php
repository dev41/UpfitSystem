<script type="text/template" class="js-template-confirm-modal-dialog">
    <div class="uf-confirm-dialog modal fade"
         id="confirmModalDialog"
         tabindex="-1"
         role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title js-title">{title}</h4>
                    <button type="button" class="close js-button" data-btn="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body js-message">{message}</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary js-button" data-btn="cancel" data-dismiss="modal">
                        <?= Yii::t('app', 'Cancel') ?>
                    </button>
                    <button type="button" class="btn btn-primary js-button" data-btn="confirm">
                        <?= Yii::t('app', 'Confirm') ?>
                    </button>
                </div>
            </div>
        </div>
    </div>
</script>