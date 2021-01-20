<h1>Cargar la lista de productos</h1>  
<div class="wrap">
    <form  method="post" enctype="multipart/form-data" id="formuploadajax">
            <input type="file" name="archivo" id="archivo">
            <br><br>
            <input  type="submit" name="enviar" value="Show">
    </form>
<div>
     <form  method="post" id="changeState" >
            <?php
            global $wpdb;
            $sql = $wpdb->prepare("
            SELECT option_value FROM wp_options 
            WHERE option_name = 'ac_cache_data_1528a04a5b4428813a42a2471ded9974'
            "); 
            $wpdb->query($sql);
            if($wpdb->last_error == "") { $array= unserialize($wpdb->last_result[0]->option_value) ; ?>
                <label class="state" for="cars">Elegir Estado:</label>
                <select id="state" name="state" class="state" >
                <?php foreach($array['options'] as $key => $value){  echo "<option value=".$key.">".$value."</option>";}   ?>
                </select>           
                <?php  }
            else {  echo "ocurrio un error al cargar los estados"; } ?>
            <BR></BR>   
            <button type="submit" id="button" name="enviar" value="Estado">Cambiar Estado </button> 
        </form>
    </div>
    <BR></BR>
    <div id="espera">
    </div>
    <div id="respuesta">

    </div>
<BR></BR>

</div>
<script>
jQuery(document).ready(function($){
    console.log('JQuery is Working');
    var remesas=[];
    $("#formuploadajax").on("submit", function(e){
        e.preventDefault();
        // var url=SolicitudesAjax.url;

        
        ver=document.getElementById("espera");
        ver.innerHTML='Espere....';
        var myFile = document.getElementById('archivo'); 
        var file = myFile.files[0];
        var formData = new FormData();
        formData.append('archivo', file);
      
        $.ajax({
             url:'<?php echo site_url();?>/api/woo_set_state_order',
              type:'POST',
             data:formData,
                    // data: {
                     // action:"peticionCargar",
                     // nonce: SolicitudesAjax.seguridad,       
                    // data:'formData'},
                    cache: false,  
                  contentType: false,
                  processData: false,
                      success: function(res){
                        ver.innerHTML='';
                        console.log(res);
                        for(let index = 0; index < res.length; index++) {  remesas.push(res[index]); }
                         remesas.forEach( ele=> { 
                            row =`<div>${ele.id_guia} - ${ele.estado}</div>`;
                            $('#respuesta').append(row);   
                         });
                         console.log(remesas);
                        },error:function(err){
                        console.log(err);
                    }
              });       
    });

    $("#changeState").on("submit", function(e){
        e.preventDefault();
        // var url=SolicitudesAjax.url;

        ver=document.getElementById("espera");
        ver.innerHTML='Cambiando Estado....';
        var select = document.getElementById('state'); 
        var state= select.value;
        var formData = new FormData();
        formData.append('estado', state);
        formData.append('remesas',remesas);
        console.log(remesas);

        var data = {};
        data.estado = "wc-recaudo-conf";
        data.data = remesas;
      
        $.ajax({
            url:'<?php echo site_url();?>/api/woo_change_state_order',
            type:'POST',
            data:JSON.stringify(data),
            dataType: 'json',
            success: function(res){

                /*
                ver.innerHTML='';
                var respuesta =document.getElementById("respuesta");
                respuesta.innerHTML=res;
*/
                // for(let index = 0; index < res.length; index++) {  remesas.push(res[index]); }
                //  remesas.forEach( ele=> { 
                //     row =`<div>${ele.id_guia} - ${ele.estado}</div>`;
                //     $('#respuesta').append(row);   
                //  });
            },error:function(err){
                console.log(err);
            }
        });       
    });
});
</script>