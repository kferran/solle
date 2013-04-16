var path = base_path + "/store/includes/api/api.php";

$(function(){

    $(".login").click(function(e){
        e.preventDefault();
        Modal('.sign_in','#login_modal');
    });

    $(".logout").click(function(e){
        e.preventDefault();
        $.get(path +'?action=logout', null, function(data, textStatus, xhr) {
          document.location.href = base_path + 'store/store.php';
        });
    });


    $(".forgot_password").click(function(e){
        e.preventDefault();
        Modal('.forgot', '#forgot_password');
    });

    $("#online_customer").click(function(e){
        e.preventDefault();
        Modal('.customer', '#customer_inner');
    });


    // category filtering
    $("#all").click(function(){
       $(".product").fadeIn('slow');
       $("#catpicker a").removeClass("current");
       $(this).addClass("current");
       return false;
    });

    $(".filter").click(function(){
        var thisFilter = $(this).attr("id");
        $(".product").fadeOut('fast');
        $("."+ thisFilter).fadeIn('slow');
        $("#catpicker a").removeClass("current");
        $(this).addClass("current");
        return false;
    });

    $("#cart table .remove").click(function(){
      $(this).fadeOut();
    });



});
