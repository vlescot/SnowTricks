$("#connexion").on("click", function(){
    $("#modal-container").load("/connexion",function(response){
        $("#connectionModal").modal({show:true});
    });
});
$("#resetPassword").on("click", function(){
    console.log('a');
    $("#modal-container").load("/reset_password",function(response){
        $("#resetPasswordModal").modal({show:true});
    });
});
$("#registration").on("click", function(){
    console.log('a');
    $("#modal-container").load("/registration",function(response){
        $("#registrationModal").modal({show:true});
    });
});