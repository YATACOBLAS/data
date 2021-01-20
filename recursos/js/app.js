jQuery(document).ready(function($){
    console.log('JQuery is Working');
    
    $("#formuploadajax").on("submit", function(e){
        e.preventDefault();
        // var url=SolicitudesAjax.url;
        var formData = new FormData();
     
        $.ajax({
            url:'https://crazynatural.test/api/get_metadata_user',
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
                        ver=document.getElementById("respuesta");
                        ver.innerHTML=res;
                         // display response from the PHP script, if any
                    },
                    error:function(err){
                        console.log(err);
                    }
                });       
    });
});
// function enviarServidor(){
//     var formData = new FormData(this);
//     var theObject= new XMLHttpRequest();
//         theObject.open('POST','cargarExcel.php',true);
//         theObject.setRequestHeader('Content-Type','application/x-www-form-urlencoded');
//         theObject.onreadystatechange = function (){
//                 document.getElementById('respuesta').innerHTML=theObject.responseText;
//         }
//         theObject.send(formData);
// }


         
// var formData = new FormData(this);
// $.post('cargarExcel.php',formData, function (data) {
//         alert("Data Loaded:");
//     }
