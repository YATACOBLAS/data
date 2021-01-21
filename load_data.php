<?php 
/*
Plugin Name: LoadData
Plugin Uri: https://www.LoadData.com
Description: Sube tu excel de productos y cambia su estado con el plugin Fixblue
Version: 0.0.1scss
*/

add_action('init', function() {
    $url_path = trim(parse_url(add_query_arg(array()), PHP_URL_PATH), '/');
    if ( $url_path === 'api/woo_set_state_order'  ) 
     {
        // $load=locate_template('api/prueba.php',true);        
        $load =  __DIR__.'\api\woo_state.php';     
        if ($load ) {
            load_template( $load);         
            exit();           
        }

    }else if ($url_path === 'api/woo_change_state_order'){
        
        $load2 =  __DIR__.'\api\woo_change_state.php';
        if($load2){
            load_template( $load2);        
            exit();
        }
    }
});



add_action('admin_menu','loadData');

function loadData(){
        add_menu_page(
            'Plugin Load Data',//Titulo de la Pagina  
            'Cambiar Estado',//Titulo del plugin como opcion en el menu
            'manage_options',// Esto se les da a los usuarios que tengan permiso de administrador
            plugin_dir_path(__FILE__).'/app.php',//el slogan del plugin 
            null//funcion para mostrar COntenido de la pagina o null si es que tienes una pagina aparte
           // plugin_dir_url(__FILE__).'../testplugin/admin/img/icon.png'//direccion de la imagen
            //posicion en el menu
        );
  
}
   
function config_scripts($hook){ 
   if($hook!='data/app.php'){
       return;
   }
    wp_register_style( 'custom-style', plugins_url( '/recursos/css/style.css', __FILE__ ) ); 
    wp_enqueue_style( 'custom-style');  
    // wp_enqueue_script( 'custom-script', plugins_url( '/recursos/js/app.js', __FILE__ ),array('jquery')); 
    //  wp_localize_script('custom-script','SolicitudesAjax',array(
    //         //para hacer solicitudes siempre a esta direccion que maneja wordpress
    //         'url'=> admin_url('admin-ajax.php'),
    //          'seguridad'=>wp_create_nonce('seg')
    //  ));
}

add_action( 'admin_enqueue_scripts', 'config_scripts' );

// function CargarExcel(){
// $nonce=$_POST['nonce'];
// if(!wp_verify_nonce($nonce,'seg')){
//     die('NO tiene permisos para ejecturar esta peticion');
// }
//     return true;
// }

// add_action('wp_ajax_nopriv_peticionCargar','CargarExcel');
//  add_action('wp_ajax_peticionCargar','CargarExcel');

