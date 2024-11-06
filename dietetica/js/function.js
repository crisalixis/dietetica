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

        if($("#foto-actual") && $("#foto-remove")){ //si existen estos elementos va a cambiar el texto del input   
            $("#foto-remove").val('img-producto.png');  //al elemento se le va a cambiar el valor
        }
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
                    $('.bodyModal').html('<form action="" method="post" name="form-add-product" class="formulario1" id="form-add-product" onsubmit="event.preventDefault(); sendDataProduct();">'+
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

    // Modal para eliminar el producto
    $('.del-product').click(function(event){
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
                    $('.bodyModal').html('<form action="" method="post" name="form-del-product" class="formulario2" id="form-del-product" onsubmit="event.preventDefault(); delProduct();">'+
                        '<h1><i class="fas fa-cubes"></i>Eliminar producto</h1>'+
                        '<p class="p">¿Esta seguro de eliminar el siguiente registro?</p>'+
                        '<p class="nameProduct parrafo">'+info.descripcion+'</p>'+
                        '<input type="hidden" name="producto_id" id="producto_id" value="'+info.codproducto+'" required>'+
                        '<input type="hidden" name="action" value="delProduct" required>'+
                        '<div class="alerta alertaAddProduct"></div>'+
                        
                        '<a href="#" class="btn-new-2" onclick="coloseModal();"><i class="fas fa-ban"></i> Cancelar</a>'+
                        '<button type="submit" class="btn-ok"><i class="fas fa-trash"></i> Aceptar</button>'+
                        '</form>')
                }
            },

            error: function(error){
                console.log(error);
            }
        });


        $('.modal').fadeIn();
    });

    //Busqueda
    $('#search-proveedor').change(function(e){
        e.preventDefault(); //no va a recargar la página

        var sistema = getUrl(); //de vuelve la funcion
        location.href = sistema='buscar-producto.php?proveedor='+$(this).val(); //redirecciona a la url y se le esta concatenando el archivo
    });
});

function getUrl(){
    var loc = window.location;
    var pathName = loc.pathname.substring(0, loc.pathname.lastIndexOf('/') + 1);
    return loc.href.substring(0, loc.href.length - ((loc.pathname + loc.search + loc.hash).length - pathName.length)); //devuelve la direccion donde se encuentra el directorio
}

//Agregar cantidad del producto y promedio de precio
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

//Eliminar producto
function delProduct(){
    var pr = $('#producto_id').val();
    $('.alertaAddProduct').html('');

    $.ajax({
        url: 'ajax.php',
        type:'POST',
        async: true,
        data: $('#form-del-product').serialize(),
        
        
        success: function(response){
            if(response == 'error'){
                $('.alertaAddProduct').html('<p style="color:Red">Error al eliminar el producto.</p>')
            }else{
                $('.row'+pr).remove(); //se concatena el dato
                $('#form-del-product .btn-ok').remove();
                $('.alertaAddProduct').html('<p>Producto eliminado correctamente.</p>');
            }
            console.log(response);
        },

        error: function(error){
            console.log(error);
        }
    });    
}

//Cerrar modal
function coloseModal(){
    $('.alertaAddProduct').html(''); //se vacia la alerta
    $('#txtcantidad').val('');//se vacian el input cantidad
    $('#txtprecio').val('');//se vacian el input precio
    $('.modal').fadeOut(); //desaparece el modal
}
