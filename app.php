
<link href="https://unpkg.com/tailwindcss@^2/dist/tailwind.min.css" rel="stylesheet">    
<div class="nav">
    <p>PANEL DE CAMBIO DE ESTADOS </p>
</div>   
<div class="container mx-auto grid grid-cols-1 md:grid-cols-2 gap-4 mt-5  ">
    <form  class="flex justify-center" method="post" enctype="multipart/form-data" id="formuploadajax">
    <input type="file" name="archivo" id="archivo"><label for="archivo">Seleccionar archivo
               </label>
            <input  type="submit" name="enviar" value="Show" id="Show">
            
    </form>
  
            <?php
            global $wpdb;
            $sql = $wpdb->prepare("
            SELECT option_value FROM wp_options 
            WHERE option_name = 'ac_cache_data_1528a04a5b4428813a42a2471ded9974'
            "); 
            $wpdb->query($sql);
            if($wpdb->last_error == "") { $array= unserialize($wpdb->last_result[0]->option_value) ; ?>
                   <form  method="post" id="changeState"  class="flex justify-center ">
                   <h3 class="mb-4 font-normal text-green-800 px-2 mt-3">Cambiar estado a:</h3>
                    <select id="state" name="state" class="rounded mx-2" >
                    <?php foreach($array['options'] as $key => $value){  echo "<option value=".$key.">".$value."</option>";}   ?>
                    </select>           
                    <?php  }
                    else {  echo "ocurrio un error al cargar los estados"; } ?>
                    <button type="submit" id="button" name="enviar" value="Estado" class="bg-green-600 hover:bg-green-500  font-medium px-2 mx-2 border-radius transform hover:scale-110 motion-reduce:transform-none text-white rounded">Actualizar estado</button>

            </form>
                
 </div>     
 <div id="esperar" class="text-bold flex justify-center my-3"></div>     <!-- <button type="submit" id="button" name="enviar" value="Estado">Cambiar Estado </button> -->
    <div class="container sm:container mx-auto sm:mx-auto grid grid-cols-1 sm:grid-cols-5  gap-4 mt-10">
        
        <table class=" border-collapse border border-green-800 col-span-3 col-start-2">
            <thead>
                <tr class="bg-green-600 text-white">
                <th class="border border-green-600 px-3 sm:px-3 md:px-5 py-1 sm:py-3 "> Remesa </th>
                <th class="border border-green-600 px-3 sm:px-3 md:px-5 py-1 sm:py-3 " > Ubicado en BD </th>
                <th class="border border-green-600 px-3 sm:px-3 md:px-5 py-1 sm:py-3 " > Actualizacion</th>
            </tr>
            </thead>
            <tbody class="bg-gray-200 " id="table-body">
               
            </tbody>
        </table>
      
    </div>
<script>
jQuery(document).ready(function($){
    console.log('JQuery is Working');
    var remesas=[];
    $("#formuploadajax").on("submit", function(e){
        e.preventDefault();
        // var url=SolicitudesAjax.url
        boton=document.getElementById("Show");
        boton.setAttribute("disabled", "");
        ver=document.getElementById("esperar");
        ver.innerHTML='Espere....';

    //   var = extensiones_permitidas = new Array(".gif", ".jpg", ".doc", ".pdf");
    //   extension = (archivo.substring(archivo.lastIndexOf("."))).toLowerCase();

    //   for (var i = 0; i < extensiones_permitidas.length; i++) {
    //      if (extensiones_permitidas[i] == extension) {
    //      permitida = true;
    //      break;
    //      }
    //   }
      

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
                            row = `<tr> <td class="border border-green-600 py-3 text-center  font-medium ">
                                         ${ele.id_guia}
                                    </td>
                                </tr>`
                           
                            $('#table-body').append(row);

                         });
                         boton.removeAttribute("disabled");
                         console.log(remesas);
                        },error:function(err){
                        console.log(err.responseText);
                        ver.innerHTML=err.responseText;
                        boton.removeAttribute("disabled");
                    }
              });       
    });

    $("#changeState").on("submit", function(e){
        e.preventDefault();
        // var url=SolicitudesAjax.url;

        ver=document.getElementById("esperar");
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
                    var respuesta=res['data'];
                    ver.innerHTML='';
                    //$('#table-body').append();
                    caja=document.getElementById("table-body");
                  caja.innerHTML='';
                  for(let index = 0; index < respuesta.length; index++) {  
                        //  remesas.forEach( ele=> { 
                            row = `<tr> <td class="border border-green-600 py-3 text-center  font-medium ">
                                         ${respuesta[index]['id_guia']}
                                    </td>
                                    <td class="border border-green-600 py-3 text-center  font-medium ">
                                         ${respuesta[index]['_bd']}
                                    </td>
                                    <td class="border border-green-600 text-green-800 font-bold py-3 text-center  font-medium ">
                                         ${respuesta[index].estado}
                                    </td>
                                </tr>`
                           
                            $('#table-body').append(row);   
                      }
                            //  });

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
