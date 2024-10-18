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

                if(response != 'error'){ //significa que si tiene los datos en formato json los convierte en un array
                    var info = JSON.parse(response);
                    $('#producto_id').val(info.codproducto);
                    $('.nameProduct').html(info.descripcion);
                    $('.bodyModal').html('<form action="" method="post" name="form-add-product" class="formulario" id="form-add-product" onsubmit="event.preventDefault(); sendDataProduct();">'+
                        '<h1><i class="fas fa-cubes"></i>Agregar producto</h1>'+
                        '<h2 class="nameProduct">'+info.descripcion+'</h2>'+
                        '<input type="number" name="cantidad" id="txtcantidad" placeholder="Cantidad del producto">'+
                        '<input type="number" name="precio" id="txtprecio" placeholder="Precio del producto">'+
                        '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" required>'+
                        '<input type="hidden" name="action" value="addProduct" required>'+
                        '<div class="alerta alertaAddProduct"></div>'+
                        '<button type="submit"class="btn-new-2"><i class="fas fa-plus"></i>Agregar</button>'+
                        '<a href="#" class="btn-ok closeModal" onclick="coloseModal();"><i class="fas fa-ban"></i>  Cerrar</a>'+
                        '</form>')
                }
            },

            error: function(error){
                console.log(error);
            }
        });


        $('.modal').fadeIn();
    });
});

function sendDataProduct(){
    $('.alertaAddProduct').html('');

    $.ajax({
        url: 'ajax.php',
        type:'POST',
        async: true,
        data: $('#form-add-product').serialize(),
    
        success: function(response){
            if(response == 'error'){
                $('.alertaAddProduct').html('<p style="color:Red">Error al agregar el producto.</p>')
            }else{
                var info = JSON.parse(response);
                $('.row'+info.producto_id+' .precioC').html(info.nuevo_precio); //se concatena el dato
                $('.row'+info.producto_id+' .existenciaC').html(info.nueva_existencia);
                $('#txtcantidad').val('');
                $('#txtprecio').val('');
                $('.alertaAddProduct').html('<p>Producto guardado correctamente.</p>')
            }
            console.log(response);
        },

        error: function(error){
            console.log(error);
        }
    });    
}

function coloseModal(){
    $('.alertaAddProduct').html(''); //se vacia la alerta
    $('#txtcantidad').val('');//se vacian el input cantidad
    $('#txtprecio').val('');//se vacian el input precio
    $('.modal').fadeOut(); //desaparece el modal
}
