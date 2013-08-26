<section class="title">
    <div>
        <ul class="main_menu">
            <li class="alast" style="width: 76px;"></li>
            <li class="last">
                <a class="" href="http://local.adminmicanal.com/admin/canales/categoria_papelera">Papelera</a>
            </li>
        </ul>
    </div>
</section>
<script type="text/javascript">
    var ul_width = parseInt($('section.title div ul.main_menu').css('width'));
    var lilast_pos = $('section.title div ul.main_menu li.last').position();
    var anew_width = ul_width - lilast_pos.left;
    $('section.title div ul.main_menu li.alast').css('width',anew_width);
</script>
<section class="item">
    <div id="filter-stage">
        <?php template_partial('papelera_categoria'); ?>
    </div>
</section>
<script type="text/javascript">
    function showMessage(type, message, duration, pathurl) {
        var configs = {
            autoHide: true, // added in v2.0
            TimeShown: duration,
            HorizontalPosition: 'center',
            VerticalPosition: 'top',
        }
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