<section class="title"></section>
<section class="item">
    <div id="filter-stage">
        <?php template_partial('lista_canales'); ?>
    </div>
</section>
<script type="text/javascript">
    function showMessage(type, message, duration, pathurl) {
        var configs = {
            autoHide: true, // added in v2.0
            TimeShown: duration,
            HorizontalPosition: 'center',
            VerticalPosition: 'top'
        };
        if (type === 'error') {
            jError(
                message, configs
            );
        } else  if (type === 'exit') {
            jSuccess(
                message, configs
            );
        } else if (type === 'info') {
            jNotify(
                message, configs
            );
        }
    }
</script>