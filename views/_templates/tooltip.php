<script type="text/template" class="js-template-tooltip">
    <div class="stock-tooltip stock-tooltip-right">
        <div class="stock-tooltip-content container-fluid">

            <div class="row">
                <div class="col-xs-8 stock-tooltip-title">
                    <strong>{title}</strong>
                </div>
                <div class="col-xs-4 ">
                    <i class="fa fa-times-circle stock-tooltip-btn text-warning stock-tooltip-close" data-btn="close"></i>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <strong>Level</strong>
                </div>
                <div class="col-xs-8">
                    <span class="float-right">{level}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <strong>Price</strong>
                </div>
                <div class="col-xs-8">
                    <span class="float-right">{price}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-4">
                    <strong>Capacity</strong>
                </div>
                <div class="col-xs-8">
                    <span class="float-right">{capacity}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <span class="float-right stock-tooltip-description">{description}</span>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-7">
                </div>
                <div class="col-xs-5">
                    <i class="fa fa-edit text-info stock-tooltip-btn stock-tooltip-edit" data-btn="edit"></i>

                    <i data-toggle="modal"
                       data-target="#confirmRemoveEventDialog"
                       class="fa fa-minus-circle text-danger stock-tooltip-btn stock-tooltip-remove"
                       data-btn="remove">
                    </i>
                </div>
            </div>
        </div>
    </div>
</script>