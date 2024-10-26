function btnSaveLoad() {
  $("#btn_save").html("Guardando ...");
  $("#btn_save").attr("disabled", true);
}

function btnSave() {
  $("#btn_save").html("Guardar");
  $("#btn_save").attr("disabled", false);
}

$(document).ready(function () {
  $(".textoGlo").keypress(function (key) {
    if (
      (key.charCode < 97 || key.charCode > 122) &&
      (key.charCode < 65 || key.charCode > 90) &&
      key.charCode != 45 &&
      key.charCode != 241 &&
      key.charCode != 209 &&
      key.charCode != 32
    )
      return false;
  });
  $(".numeroDni").keypress(function (key) {
    if (key.charCode < 48 || key.charCode > 57) return false;
  });
  $(".numeroDni").on("keydown keypress", function (e) {
    if (e.key.length === 1) {
      if ($(this).val().length < 8 && !isNaN(parseFloat(e.key))) {
        $(this).val($(this).val() + e.key);
      }
      return false;
    }
  });

  $("#frm_foto")
    .unbind("submit")
    .bind("submit", function () {
      var nombre = $("#nombre").val();
      var apellido = $("#apellido").val();
      var fecha = $("#fnac").val();
      var email = $("#email").val();
      var dni = $("#dni").val();
      var sexo = $("#sexo").val();
      var radio = $("input[name='radio_select']:checked").val();

      // Captura la firma
        var signatureCanvas = document.getElementById("signatureCanvas");
        var signatureData = signatureCanvas.toDataURL("image/png");

      if (radio == 0) {
        // Creamos un FormData y añadimos la firma manualmente
        var formData = new FormData(this);
        formData.append("firma", signatureData); // Agregamos la firma al FormData
        
        $.ajax({
          url: "../controllers/save_img.php",
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
            if (response.success == true) {
              swal("MENSAJE", response.messages, "success");
              $("#frm_foto")[0].reset();
              $("#radiosfoto").click();
            } else {
              swal("MENSAJE", response.messages, "error");
            }
          },
          error: function (xhr, status, error) {
            btnSave();
            if (status === "timeout") {
              swal(
                "MENSAJE",
                "La solicitud ha tardado demasiado tiempo en completarse.",
                "error"
              );
            } else {
              swal("MENSAJE", "Ha ocurrido un error: " + error, "error");
            }
            console.log(xhr.responseText);
          },
        });
      }

      if (radio == 1) {
        // Captura la imagen del video
        cxt.drawImage(video, 0, 0, 300, 150);
        var data = canvas.toDataURL("image/jpeg");
        var info = data.split(",", 2)[1];

        cxt.drawImage(video, 0, 0, 300, 150);
        var data = canvas.toDataURL("image/jpeg");
        $.ajax({
          type: "POST",
          url: "../controllers/save_photo.php",
          data: {
            foto: data,
            nombre: nombre,
            apellido: apellido,
            fecha: fecha,
            email: email,
            dni: dni,
            sexo: sexo,
            firma: signatureData, // Enviamos la firma en base64
          },
          dataType: "json",
          timeout: 3000,
          beforeSend: function () {
            btnSaveLoad();
          },
          success: function (response) {
            btnSave();
            console.log(response);
            if (response.success == true) {
              swal("MENSAJE", response.messages, "success");
              $("#frm_foto")[0].reset();
              $("#radiosfoto").click();
            } else {
              swal("MENSAJE", response.messages, "error");
            }
          },
          error: function (xhr, status, error) {
            btnSave();
            if (status === "timeout") {
              swal(
                "MENSAJE",
                "La solicitud ha tardado demasiado tiempo en completarse.",
                "error"
              );
            } else {
              swal("MENSAJE", "Ha ocurrido un error: " + error, "error");
            }
            console.log(xhr.responseText);
          },
        });
      }
      return false;
    });
});
