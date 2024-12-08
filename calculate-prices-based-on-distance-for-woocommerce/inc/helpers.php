<?php 

 /*======= loading template files ======*/
function wccpd_load_templates( $template_name, $vars = null) {
    if( $vars != null && is_array($vars) ){
       extract( $vars );
    };

    $template_path =  WCCPD_PATH . "/templates/{$template_name}";
    if( file_exists( $template_path ) ){
        include  $template_path ;
    } else {
       die( "Error while loading file {$template_path}" );
    }
}


/* ==== print defualt array ==== */
function wccpd_pa($arr){
   echo '<pre>';
   print_r($arr);
   echo '</pre>';
}