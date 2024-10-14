$(document).ready(function(){

    //--------------------- SELECCIONAR FOTO PRODUCTO ---------------------
    $("#foto").on("change", function(){
        var uploadFoto = document.getElementById("foto").value;
        var foto = document.getElementById("foto").files;
        var nav = window.URL || window.webkitURL;
        var contactAlert = document.getElementById('form_alert');
        
        if (uploadFoto !='') {
            var type = foto[0].type;
            var name = foto[0].name;
            if(type != 'image/jpeg' && type != 'image/jpg' && type != 'image/png') {
                contactAlert.innerHTML = '<p class="errorArchivo">El archivo no es válido.</p>';                        
                $("#img").remove();
                $(".delPhoto").addClass('notBlock');
                $('#foto').val('');
                return false;
            } else {  
                contactAlert.innerHTML='';
                $("#img").remove();
                $(".delPhoto").removeClass('notBlock');
                var objeto_url = nav.createObjectURL(this.files[0]);
                $(".prevPhoto").append("<img id='img' src="+objeto_url+">");
            }
        } else {
            alert("No seleccionó foto");
            $("#img").remove();
        }              
    });

    $('.delPhoto').click(function(){
        $('#foto').val('');
        $(".delPhoto").addClass('notBlock');
        $("#img").remove();
    });

    // Modal para agregar el producto
    $('.add-product').click(function(event){
        event.preventDefault();
        var producto = $(this).attr('product') //permite acceder a los atributos del elemento producto
        var action = 'infoProducto';

        $.ajax({
            url: 'ajax.php',
            type:'POST',
            async: true,
            data: {action:action,producto:producto},
        
            success: function(response){
                console.log(response);

                if(response != 'error'){ //significa que si tiene los datos en formato json
                    var info = JSON.parse(response);
                    $('#producto_id').val(info.codproducto);
                    $('.nameProduct').html(info.descripcion);
                }
            },

            error: function(error){
                console.log(error);
            }
        });


        $('.modal').fadeIn();
    });
});

function coloseModal(){
    $('.modal').fadeOut(); //desaparece el modal
}
