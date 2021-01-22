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
         
