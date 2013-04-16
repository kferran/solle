function ContactUs(){
    var view_width = $(document).width();
    var view_height = $(window).height();

    var w = 707;
    var h = 615;

    $.blockUI({ message : $('#contact_us_dialog'), css :
    {
        'text-align' : 'left',
        cursor : 'default',
        width : w,
        height : h,
        left : (view_width - w)/2,
        top : (view_height - h)/2,
        border : 'none'
    }});

    $('#contact_us_inner').show();
    $('#confirmation').hide();

    $('#newsletter_error').css('visibility','hidden');
    $('#name_error').hide();
    $('#email_error').hide();
    $('input[name=name]').val('');
    $('input[name=email]').val('');
    $('input[name=subject]').val('');
    $('textarea[name=message]').val('');
    $('input[type=radio]:checked').removeAttr('checked');
}

function Validate(){
    var valid = true;

    function NotEmpty(s)
    {
        s = $.trim(s);

        return s != '' && s != null;
    }

    if(NotEmpty($('input[name=name]').val()))
    {
        $('#name_error').hide();
    }
    else
    {
        $('#name_error').show();
        valid = false;
    }


    var pattern = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i
    if(pattern.test($('#contact_email').val()))
    {
        $('#email_error').hide();
    }
    else
    {
        $('#email_error').show();
        valid = false;
    }

    if($('input[type=radio]:checked').length == 1)
    {
        $('#newsletter_error').css('visibility','hidden');
    }
    else
    {
        $('#newsletter_error').css('visibility','visible');
        valid = false;
    }

    return valid;
}

var curr_quote = 1;
var num_quotes;
function DoQuoteSwap(){
    setTimeout(function(){
        $('.quote:nth-child('+curr_quote+')').fadeOut(400,function()
        {
            curr_quote++;

            if(curr_quote > num_quotes)
                curr_quote = 1;

            $('.quote:nth-child('+curr_quote+')').fadeIn(400,function()
            {
                DoQuoteSwap();
            });
        });

    },7000);
}
$(function(){
    $('#name_error').css({
        color : 'red',
        display : 'none'
    });

    $('#email_error').css({
        color : 'red',
        display : 'none'
    });

    $('#newsletter_error').css({
        color : 'red',
        visibility : 'hidden'
    });

    $('#contact_header_link, #footer_contact_link, #contact_button').click(function(){
        ContactUs();
        return false;
    });

    $('#contact_button').mousedown(function() { $(this).css('margin-top','+=2px'); });
    $('#contact_button').mouseup(function() { $(this).css('margin-top','0'); });
    $('#contact_button').mouseout(function() { $(this).css('margin-top','0'); });

    $('#cancel_button').mousedown(function() { $(this).css('margin-top','+=2px'); });
    $('#cancel_button').mouseup(function() { $(this).css('margin-top','0'); });
    $('#cancel_button').mouseout(function() { $(this).css('margin-top','0'); });
    $('#cancel_button').click(function(){
        $('#contact_us_dialog').hide();
        $.unblockUI();
    });

    $('#send_now_button').mousedown(function() { $(this).css('margin-top','+=2px'); });
    $('#send_now_button').mouseup(function() { $(this).css('margin-top','0'); });
    $('#send_now_button').mouseout(function() { $(this).css('margin-top','0'); });
    $('#send_now_button').click(function(){
        if(Validate()){
            $.ajax({
                url : '../contact_submit.php',
                data :
                {
                    name : $('input[name=name]').val(),
                    email : $('input[name=email]').val(),
                    subject : $('input[name=subject]').val(),
                    message : $('textarea[name=message]').val(),
                    sign_up : $('input[type=radio]:checked').val()
                },
                type : 'POST',
                success : function(){}
            });

            $('#contact_us_inner').fadeOut();
            $('#confirmation').fadeIn();
        }
    });

    $('#close_window_span').click(function(){
        $('#contact_us_dialog').hide();

        $.unblockUI();
    });

    $('#legal_disclaimer').click(function(){
        if($('#footer_disclaimer img').attr('src') =='../images/right_arrow.png')
        {
            $('#footer_disclaimer p').show();
            $('#footer_disclaimer img').attr('src','../images/down_arrow.png');
        }
        else
        {
            $('#footer_disclaimer p').hide();
            $('#footer_disclaimer img').attr('src','../images/right_arrow.png');
        }
    });

    // about_us.php
    $('#fader_nav div').click(function(){
        var curr_i = $('.fader_current').index() + 1;
        var dest_i = $(this).index() + 1;

        if(dest_i != curr_i)
        {
            $('.fader_page:nth-child('+curr_i+')').hide();
            $('.fader_page:nth-child('+dest_i+')').show();
            var dest_height = $('.fader_page:nth-child('+dest_i+')').height();
            $('.fader_page:nth-child('+dest_i+')').hide();
            $('.fader_page:nth-child('+curr_i+')').show();

            $('#fader_content').animate
            ({
                height : dest_height
            });

            $('.fader_page:nth-child('+curr_i+')').animate
            ({
                opacity : 0.0
            },function()
            {
                $('.fader_page:nth-child('+curr_i+')').hide();
                $('#fader_nav div').removeClass('fader_current');
                $('#fader_nav div:nth-child('+dest_i+')').addClass('fader_current');
                $('.fader_page:nth-child('+dest_i+')').show();
                $('.fader_page:nth-child('+dest_i+')').css('opacity','0.0');
                $('.fader_page:nth-child('+dest_i+')').animate({ opacity : 1.0 });
            });
        }
    });

    // ind_product.php
    $('#dropdown_selected').click(function()
    {
        $('#dropdown_list').show();

        return false;
    });

    $('body').click(function()
    {
        $('#dropdown_list').hide();
    });

    $('#overview_a').click(function(){ $('body').scrollTo('a[name=overview]',200); return false;});
    $('#ingredients_a').click(function(){ $('body').scrollTo('a[name=ingredients]',200); return false;});
    $('#difference_a').click(function(){ $('body').scrollTo('a[name=difference]',200); return false;});
    $('#use_a').click(function(){ $('body').scrollTo('a[name=bottom]',200); return false;});
    $('#complement_a').click(function(){ $('body').scrollTo('a[name=bottom]',200); return false;});

    $('.back_to_top').click(function(){ $('body').scrollTo('#header',200); return false;});


 // home.php
    num_quotes = $('.quote').length;

    DoQuoteSwap();

    //comp_plan.php
    $('#ways_link').click(function(){
        $('body').scrollTo('a[name=ways]',400);

        return false;
    });

    $('#comp_link').click(function(){
        $('body').scrollTo('a[name=comp]',400);

        return false;
    });

    $('#comp_nav li').click(function(){
        var i = $(this).index() + 1;

        if(!$('#sub_page_container > *:nth-child('+i+')').is(':visible'))
        {
            $('#nav_arrow').animate({ left : 150 + (i-1) * 315 });
            $('#sub_page_container').animate({ height : $('#sub_page_container > *:nth-child('+i+')').height() + 50 });

            $('#sub_page_container > *:visible').fadeOut(function(){
                $('#sub_page_container > *:nth-child('+i+')').fadeIn();
            });
        }
    });
    $('.pre-enroll img').mousedown(function() { $(this).attr('src','../images/pre_enrollbutton.png'); $(this).css('padding-top','2px'); } );
    $('.pre-enroll img').mouseup(function() { $(this).attr('src','../images/pre_enrollbutton_up.png'); $(this).css('padding-top',0); } );
    $('.pre-enroll img').mouseout(function() { $(this).attr('src','../images/pre_enrollbutton_up.png'); $(this).css('padding-top',0); } );
});


$(function()
        {
            $('.category').click(function()
            {
                if(!$(this).hasClass('selected_category'))
                {
                    var to_i = $(this).index();
                    var to = $(this);
                    var from_i = $('.selected_category').index();

                    $('#products_arrow').animate
                    ({
                        left : 87 + to_i * 232
                    },function()
                    {
                        $('.selected_category').removeClass('selected_category');
                        to.addClass('selected_category');
                    });

                    $('.product_category:nth-child('+(from_i+1)+')').fadeOut(function()
                    {
                        //$(this).css('display','none');
                        $('.product_category:nth-child('+(to_i+1)+')').fadeIn();
                    });
                }
            });
        });
