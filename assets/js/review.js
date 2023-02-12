$(document).ready(function() {
    $('.reviewcontent.basiscontent .maincontent').each(function() {
        if($(this).find('div.image').length !== 1) {
            $(this).addClass('noimg');
        }
    })

    $('button.platformbutton').each(function() {
        $(this).click(function () {
            $('.platformcontentitems').addClass('hidden');
            item = ".item-"+ $(this).attr('data-id');

            $(item).removeClass('hidden');          
        });
    })
})