$(document).ready(function () {
  $("#tabla").DataTable({
    destroy: true,
    processing: true,
    ajax: {
      url: "../controllers/tabla.php",
      dataSrc: function (json) {
        return json.data;
      },
    },
    order: [],
  });
});

function verfoto(fotoBase64) {
  $("#verfotomodal").html("");
  $("#modalfoto").modal("show");
  $("#verfotomodal").append(
    '<img src="' + fotoBase64 + '" class="img-responsive" style="width: 100%;">'
  );
}

function editarRegistro(alumno) {
  $("#id").val(alumno["id"]);
  $("#nombre").val(alumno["nombre"]);
  $("#apellido").val(alumno["apellido"]);
  $("#fnac").val(alumno["fnac"]);
  $("#email").val(alumno["email"]);
  $("#dni").val(alumno["dni"]);
  $("#sexo").val(alumno["sexo"]);

  // Muestra el modal
  $("#modaleditar").modal("show");
}

function btnSaveLoad() {
  $("#btn_edit").html("Guardando ...");
  $("#btn_edit").attr("disabled", true);
}

function btnSave() {
  $("#btn_edit").html("Editar");
  $("#btn_edit").attr("disabled", false);
}

$(document).ready(function () {
  // Inicializar la tabla
  $("#tabla").DataTable({
    destroy: true,
    processing: true,
    ajax: {
      url: "../controllers/tabla.php",
      dataSrc: function (json) {
        return json.data;
      },
    },
    order: [],
  });

  // Evento de envío del formulario de edición
  $("#frm_edit").on("submit", function (e) {
    e.preventDefault(); // Evita el comportamiento por defecto del formulario

    // Obtener los valores del formulario
    var formData = new FormData(this);
    var radio = $("input[name='radio_select']:checked").val();

    // Comprobar qué tipo de formulario estamos tratando
    if (radio == 0) {
      // Enviar datos de texto
      $.ajax({
        url: "../controllers/edit_img.php",
        type: "POST",
        data: formData,
        timeout: 3000,
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function () {
          btnSaveLoad();
        },
        success: function (response) {
          btnSave();
          console.log(response);
          if (response.success) {
            swal("MENSAJE", response.messages, "success");
            // Recargar la tabla con los nuevos datos
            $("#tabla").DataTable().ajax.reload();
            $("#modaleditar").modal("hide");
          } else {
            swal("MENSAJE", response.messages, "error");
          }
        },
        error: function (xhr, status, error) {
          btnSave();
          swal("MENSAJE", "Ha ocurrido un error: " + error, "error");
          console.log(xhr.responseText);
        },
      });
    } else if (radio == 1) {
      // Enviar imagen capturada
      cxt.drawImage(video, 0, 0, 300, 150);
      var data = canvas.toDataURL("image/jpeg");

      $.ajax({
        type: "POST",
        url: "../controllers/edit_photo.php",
        data: {
          id: $("#id").val(),
          foto: data,
          nombre: $("#nombre").val(),
          apellido: $("#apellido").val(),
          fecha: $("#fnac").val(),
          email: $("#email").val(),
          dni: $("#dni").val(),
          sexo: $("#sexo").val(),
        },
        dataType: "json",
        timeout: 3000,
        beforeSend: function () {
          btnSaveLoad();
        },
        success: function (response) {
          btnSave();
          console.log(response);
          if (response.success) {
            swal("MENSAJE", response.messages, "success");
            // Recargar la tabla con los nuevos datos
            $("#tabla").DataTable().ajax.reload();
            $("#modaleditar").modal("hide");
          } else {
            swal("MENSAJE", response.messages, "error");
          }
        },
        error: function (xhr, status, error) {
          btnSave();
          swal("MENSAJE", "Ha ocurrido un error: " + error, "error");
          console.log(xhr.responseText);
        },
      });
    }
  });
});
function eliminarRegistro(id) {
  // Agregar confirmación antes de eliminar
  if (confirm("¿Estás seguro de que deseas eliminar este registro?")) {
    $.ajax({
      url: "../controllers/delete.php",
      type: "POST",
      data: {
        id: id,
      },
      dataType: "json",
      success: function (response) {
        console.log(response);
        if (response.success == true) {
          swal("MENSAJE", response.messages, "success");
          $("#tabla").DataTable().ajax.reload(); // Recargar tabla
        } else {
          swal("MENSAJE", response.messages, "error");
        }
      },
      error: function (xhr, status, error) {
        console.log(xhr.responseText);
      },
    });
  }
}
