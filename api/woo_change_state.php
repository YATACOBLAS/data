<?php

header('Content-Type: application/json');
global $wpdb;


if ($_SERVER['REQUEST_METHOD'] === 'POST') 
{


    // wc-recaudo-nove
    // $order = wc_get_order(18907);
    //            if($order){
    //                $order->update_status( 'wc-recaudo-nove', '', true );
    //            }
    // exit;

    $body = json_decode(file_get_contents('php://input'), true);
    $estado = $body['estado'];
    $respuesta_array=array();
    for ($i=0; $i < count($body['data']); $i++) 
    {

        $id_guia = $body['data'][$i]['id_guia'];
      
        $sql= $wpdb->prepare("
        SELECT post_id FROM wp_postmeta
        WHERE meta_key = '_wc_shipment_tracking_items' AND 
        meta_value REGEXP '.*;s:[0-9]+:\"tracking_number\".*' AND 
        meta_value REGEXP '.*;s:[0-9]+:\"".$id_guia."\".*' 
        ");
          $wpdb->query($sql);   
        if($wpdb->last_error == "") 
        {
            if(count($wpdb->last_result)>0)
            {
                $post_id = $wpdb->last_result[0]->post_id;
               
                $order = wc_get_order($post_id);

                if($order){

                    $sql= $wpdb->prepare("
                    UPDATE wp_posts
                    SET
                        post_status = %s
                    WHERE ID = %s
                    ",$estado,$post_id);
                    $wpdb->query($sql);
                    if($wpdb->last_error == "") 
                    {
                        $body['data'][$i]['estado']='Actualizado';
                        $body['data'][$i]['_bd']='Si se encontró';
                    }
                    else
                    {
                        $body['data'][$i]['estado']='No se Actualizó';
                        $body['data'][$i]['_bd']='Se encontro pero sucedio un Error';
                    }
                }
                else        
                {
                    $body['data'][$i]['_bd']='Se encontro pero sucedio un Error';
                    $body['data'][$i]['estado']='No se Actualizó';
                }
            }
            else
            {
                $body['data'][$i]['_bd']='No se encontró';
                $body['data'][$i]['estado']='No se Actualizó';
               // $body['data'][$i]['BD']='No se encontro un pedido con esta guia';
            }   
        }

    }

    if (!isset($res))
    {
        $res = (object)array();
    }
    $res->data = "transferencia correcta";
    echo json_encode($body);


}







exit;

if(!empty($_POST["estado"]) && !empty($_POST["remesas"]) ){
    $estado=$_POST["estado"];
    $remesas[]=$_POST["remesas"];
    echo $remesas;    
    // $myArray = json_decode(json_encode($remesas[0][0][0], true)); 
    // echo "After conversion-----: \n"; 
    //     echo $myArray;
    // echo gettype($myArray);
//    echo gettype(json_decode(json_encode($remesas[0][0])));
    //  foreach ($remesas as $objetos=> $val) {
    //       print_r( $val->id_guia);
    //     }
     //  for ($i=0; $i <count($remesas); $i++) { 
        
    // var_dump(json_decode($remesas[0], true)); 
        
    // }
         
    
}else{

    foreach( $remesas as $key=>$value){
            $sql= $wpdb->prepare("
        SELECT post_id FROM wp_postmeta
        WHERE meta_key = '_wc_shipment_tracking_items' AND 
        meta_value REGEXP '.*;s:[0-9]+:\"tracking_number\".*' AND 
        meta_value REGEXP '.*;s:[0-9]+:".$key.".*' 
        ");
          $wpdb->query($sql);   
        if($wpdb->last_error == "") {
            echo  $wpdb->$last_result[0]->post_id;
            echo "----";//  $order = wc_get_order($id);
            // if($order){
            //     $order->update_status( 'wc-recaudo-conf', '', true );
            //  }
               
        }   }
} 

?>          