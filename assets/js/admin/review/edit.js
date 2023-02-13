$(document).ready(function () {
    $('.openbutton').click(function() {
        $("#settingsmenu").toggleClass('hidden');
    })

    $("input[type='text']").change(function() {
        var id = $('.reviewedit').attr('data-reviewid');
        var name = $(this).attr('name');
        var type = $(this).attr('data-type');
        var value = $(this).val();

        var data = {
            'id':id,
            'name':name,
            'type':type,
            'value':value
        }
        $.ajax({
            type: "POST",
            url: "/ajax/admin/review/edit?type=block",
            data: data,
            success: function (response) {
                console.log(response)
            },
            error: function (response) {
                console.log(response);
            }
        });
    });

    $('.platformclick').click(function() {
        var id = $(this).attr('data-reviewid');
        var platform = $(this).attr('data-platformid');
        var type = $(this).attr('data-type');

        var data = {
            'id':id,
            'platform':platform,
            'type':type
        }

        $.ajax({
            type: "POST",
            url: "/ajax/admin/review/edit?type=platform",
            data: data,
        }).done(function(response) {
            console.log(response);
        }).fail(function(jqXHR, textStatus, errorMessage) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorMessage);
        });
    });
})