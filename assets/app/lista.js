$(document).ready(function() {
    $("#tabla").DataTable({
        "destroy": true,
        "processing": true,
        "ajax": {
            "url": "../controllers/tabla.php",
            "dataSrc": function (json) {
                return json.data;
            }
        },
        "order": []
    });
});



function verfoto(fotoBase64){
    $("#verfotomodal").html(""); 
    $('#modalfoto').modal('show');  
    $("#verfotomodal").append('<img src="' + fotoBase64 + '" class="img-responsive" style="width: 100%;">');  
	
}
