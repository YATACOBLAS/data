
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">    
<div class="nav">
    <p>PANEL DE CAMBIO DE ESTADOS </p>
</div>   
<div class="wrap">
    <form  method="post" enctype="multipart/form-data" id="formuploadajax">
    <input type="file" name="archivo" id="archivo"><label for="archivo">Seleccionar archivo
                <img src="https://raw.githubusercontent.com/erickmatias/plugin_wordpress_excel/main/recursos/icon/excel.png" alt="">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
            <input  type="submit" name="enviar" value="Show">

    </form>

     <form  method="post" id="changeState" >
            <?php
            global $wpdb;
            $sql = $wpdb->prepare("
            SELECT option_value FROM wp_options 
            WHERE option_name = 'ac_cache_data_1528a04a5b4428813a42a2471ded9974'
            "); 
            $wpdb->query($sql);
            if($wpdb->last_error == "") { $array= unserialize($wpdb->last_result[0]->option_value) ; ?>
                <label  for="state" class>Cambiar estado a:</label>
                <select id="state" name="state" class="rounded p-2 " >
                <?php foreach($array['options'] as $key => $value){  echo "<option value=".$key.">".$value."</option>";}   ?>
                </select>           
                <?php  }
            else {  echo "ocurrio un error al cargar los estados"; } ?>
            <BR></BR>   
            <button type="submit" id="button" name="enviar" value="Estado">Cambiar Estado </button> 
        </form>
    
    <div class="container sm:container mx-auto sm:mx-auto grid grid-cols-5 sm:grid-cols-5 lg:grid-cols-6 gap-4 mt-10">
        
        <table class=" border-collapse border border-green-800 col-span-2 col-start-2">
            <thead>
                <tr class="bg-green-600 text-white">
                <th class="border border-green-600 px-3 sm:px-3 md:px-5 py-1 sm:py-3 "> Remesa </th>
                <th class="border border-green-600 px-3 sm:px-3 md:px-5 py-1 sm:py-3 " > Ubicado en BD </th>
            </tr>
            </thead>
            <tbody class="bg-gray-200 ">
                <tr>
                    <td class="border border-green-600 py-3 text-center  font-medium ">
                        Actualización
                    </td>
                    <td class="border border-green-600 py-3 text-center text-red-600  font-medium">
                        asdasda
                    </td>
                </tr>
            </tbody>
        </table>
        <table class=" border-collapse border border-green-800 col-start-4 lg:col-start-5">
            <thead>
            <tr class="bg-green-600 text-white">
                <th class="border border-green-600 px-2 sm:px-3 md:px-5 py-1 sm:py-3  "> Actualización </th>
            </tr>
            </thead>
            <tbody class="bg-gray-200 ">
                <tr >
                    <td class="border border-green-600 py-3 sm:py-3 text-center  font-medium ">
                        sadasdasd
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="container mx-auto flex justify-center mt-6">
        <button class="bg-green-600 hover:bg-green-500 font-medium p-2 border-radius transform hover:scale-110 motion-reduce:transform-none text-white rounded">Actualizar estado</button>

    </div>
    <div id="espera">
    </div>
    <div id="respuesta">

    </div>


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
      console.log(remesas);

       var data = {};
        data.estado = state;
        data.data = remesas;
      
        $.ajax({
            url:'<?php echo site_url();?>/api/woo_change_state_order',
            type:'POST',
            data:JSON.stringify(data),
            dataType: 'json',
            success: function(res){
                    console.log(res);
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
