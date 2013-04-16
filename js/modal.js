function Modal(modal, modal_inner){
    var view_width = $(document).width();
    var view_height = $(window).height();

    var w = 662;
    var h = 451;
                
    $.blockUI({ message : $(modal), css :
    {
        'text-align' : 'left',
        cursor : 'default',
        width : w,
        height : h,
        left : (view_width - w)/2,
        top : (view_height - h)/2,
        border : 'none'
    }, overlayCSS:
    {
        backgroundColor: '#fff'
    }
    });
                
    $(modal).show();
}
$(function(){
    $(".close_modal").click(function(){
         $('.modal').hide();
        $.unblockUI();
    });
});
