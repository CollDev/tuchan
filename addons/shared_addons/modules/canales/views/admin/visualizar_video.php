<!--<!doctype html>-->

<!--<head>-->

   <!-- player skin -->
   <!--<link rel="stylesheet" type="text/css" href="skin/minimalist.css" />-->
   <link rel="stylesheet" type="text/css" href="<?php echo base_url('addons/shared_addons/modules/canales/css/skin/minimalist.css') ?>" />

   <!-- site specific styling -->
<!--   <style>
   body { font: 12px "Myriad Pro", "Lucida Grande", sans-serif; text-align: center; padding-top: 5%; }
   .flowplayer { width: 80%; }
   </style>-->

   <!-- flowplayer depends on jQuery 1.7.1+ (for now) -->
   <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>-->

   <!-- include flowplayer -->
   <!--<script src="flowplayer.min.js"></script>-->
   <script src="<?php echo base_url('addons/shared_addons/modules/canales/js/flowplayer.min.js') ?>"></script>

<!--</head>-->

<!--<body>-->

   <!-- the player -->
   <div class="flowplayer" data-swf="<?php echo base_url('addons/shared_addons/modules/canales/js/flowplayer.swf') ?>" data-ratio="0.417">
      <video>
         <!--<source type="video/webm" src="http://stream.flowplayer.org/bauhaus/624x260.webm"/>-->
         <source type="video/mp4" src="<?php echo $ruta; ?>" />
         <!--<source type="video/ogv" src="http://stream.flowplayer.org/bauhaus/624x260.ogv"/>-->
      </video>
   </div>

<!--</body>-->