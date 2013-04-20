<div class="alert success" style="margin-top: 25px">    
    <p><?php echo $this->session->flashdata('msg_success'); ?></p>
</div>

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
    
    
    $("td input:radio").attr("disabled","true");
    
    
    function put_disabled(){
            $("input:checkbox:checked").each(function(){

               //alert('check: ' + $(this).val());
               $(this).parent().parent().find("td input:radio").removeAttr("disabled");

           });    
        
    }

    
$("input:checkbox:checked").change(function(){
                $("td input:radio").attr("disabled","true");
                put_disabled();
           });  

    
    
    
</script>
