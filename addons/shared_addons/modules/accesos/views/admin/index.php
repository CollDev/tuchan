<?php if ($this->session->flashdata('msg_success') !== false) { ?>
<div class="alert success" style="margin-top: 25px">
    <p><?php echo $this->session->flashdata('msg_success'); ?></p>
</div>
<?php } ?>
<section class="title">
    <h4><?php echo $module_details['name']; ?> para <?php echo $usuario ?></h4>
</section>
<section class="item">
    <!-- Acceso a canales -->
    <div id="filter-stage">
        <?php template_partial('canales'); ?>
    </div>
</section>
<script>
    $(document).ready(function() {
        if($.browser.mozilla) $("form#accesos").attr("autocomplete", "off");
        $('input:checkbox').change(function(){
            var $this = this;
            if($($this).is(':checked')) {
                $('input:radio[value=' + $($this).val() + ']').attr('disabled', false);
            } else {
                $('input:radio[value=' + $($this).val() + ']').attr('disabled', true);
            }
        });
//        $("td input:radio").attr("disabled", "true");
//        put_disabled();
//        
//        $("input:checkbox:checked").change(function() {
//            $("td input:radio").attr("disabled", "true");
//            put_disabled();
//        });
//        
//        $("input:checkbox").change(function() {
//            desabilitar_radios();
//        });
    });

//    function put_disabled() {
//        var cont = 0;
//        $("input:checkbox:checked").each(function() {
//            $(this).parent().parent().find("td input:radio").removeAttr("disabled");
//            if (cont == 0) {
//                $(this).parent().parent().find("td input:radio").attr('checked', true);
//            }
//            cont++;
//        });
//        if (cont == 0) {
//            $("input:checkbox").change(function() {
//                if ($(this).is(':checked')) {
//                    $(this).parent().parent().find("td input:radio").attr('checked', true);
//                    $(this).parent().parent().find("td input:radio").attr('disabled', false);
//                }
//                desabilitar_radios();
//            });
//        }
//    }
//    
//    function desabilitar_radios() {
//        $("input:checkbox").each(function() {
//            if ($(this).is(':checked')) {
//                $(this).parent().parent().find("td input:radio").attr('disabled', false);
//            } else {
//                $(this).parent().parent().find("td input:radio").attr('disabled', true);
//            }
//        });
//    }
</script>