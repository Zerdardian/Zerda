$(document).ready(function () {
    $('.openbutton').click(function() {
        $("#settingsmenu").toggleClass('hidden');
    })

    $('.text').change(function() {
        var id = $('.reviewedit').attr('data-reviewid');
        var name = $(this).attr('name');
        var value = $(this).val();

        var data = {
            'id':id,
            'name':name,
            'value':value
        }

        console.log(data);
        // $.ajax({
        //     type: "POST",
        //     url: "/ajax/admin/edit/",
        //     data: data,
        //     success: function (response) {
                
        //     }
        // });
    })
})