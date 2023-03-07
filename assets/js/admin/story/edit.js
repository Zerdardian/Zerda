
$('input').on('change', function () {
    var name = $(this).attr('name');
    var type = $(this).attr('type');
    if (type == 'file') return;
    var typeinsert = $(this).attr('data-typeinsert');
    var storyid = $(this).attr('data-storyid');
    var blockid = null;
    var value = $(this).val();

    if ($(this).attr('data-blockid')) {
        var blockid = $(this).attr('data-blockid');
    }

    data = {
        'name': name,
        'type': type,
        'insertype': typeinsert,
        'storyid': storyid,
        'blockid': blockid,
        'value': value
    }

    $.ajax({
        type: "POST",
        url: "/ajax/admin/story/edit/",
        data: data,
    }).done(function (response) {
        console.log(response);
    }).fail(function () {
        console.log(this);
        console.log('fail');
    });
})

var canvas = $("#cropperimg"),
    context = canvas.get(0).getContext("2d"),
    $result = $('#headbackground');

$('input#backgroundpng').on('change', function () {
    $('.cropperarea').removeClass('hidden');
    var id = $('.story').attr('data-storyid');
    if (this.files && this.files[0]) {
        if (this.files[0].type.match(/^image\//)) {
            var reader = new FileReader();
            reader.onload = function (evt) {
                var img = new Image();
                img.onload = function () {
                    context.canvas.height = img.height;
                    context.canvas.width = img.width;
                    context.drawImage(img, 0, 0);
                    var cropper = canvas.cropper({
                        aspectRatio: 1920 / 500,
                        minContainerWidth: 80,
                        minContainerHeight: 100,
                        viewMode:2
                    });
                    $('#btnCrop').on('click', function () {
                        // Get a string base 64 data url
                        var croppedImageDataURL = canvas.cropper('getCroppedCanvas').toDataURL("image/png");
                        var data = {
                            'id': id,
                            'type': 'picturehead',
                            'value': croppedImageDataURL
                        }

                        $.ajax({
                            url: "/ajax/admin/story/edit",
                            type: "POST",
                            data: data,
                        }).done(function (response) {
                            if (response.error == 200) {
                                location.reload();
                            }
                        }).fail(function (jqXHR, textStatus, errorMessage) {
                            console.log(jqXHR);
                            console.log(textStatus);
                            console.log(errorMessage);
                        })
                    });
                    $('#btnRestore').on('click', function () {
                        canvas.cropper('reset');
                        $result.empty();
                    });
                };
                img.src = evt.target.result;
            };
            reader.readAsDataURL(this.files[0]);
        } else {
            alert("Invalid file type! Please select an image file.");
        }
    } else {
        alert('No file(s) selected.');
    }
})

$('textarea').on('change', function () {
    var name = $(this).attr('name');
    var type = $(this).attr('type');
    var typeinsert = $(this).attr('data-typeinsert');
    var storyid = $(this).attr('data-storyid');
    var blockid = null;
    var value = $(this).val();

    if ($(this).attr('data-blockid')) {
        var blockid = $(this).attr('data-blockid');
    }

    data = {
        'name': name,
        'type': type,
        'insertype': typeinsert,
        'storyid': storyid,
        'blockid': blockid,
        'value': value
    }

    $.ajax({
        type: "POST",
        url: "/ajax/admin/story/edit/",
        data: data,
    }).done(function (response) {
        console.log(response);
    }).fail(function () {
        console.log(this);
        console.log('fail');
    });
})

$("textarea").each(function (textarea) {
    var scroll_height = $(this).get(0).scrollHeight;

    $(this).css('height', scroll_height + "px");
});
$("textarea").on('input', function () {
    var scroll_height = $(this).get(0).scrollHeight;
    var client_height = $(this).get(0).clientHeight;

    if (scroll_height > client_height) {
        $(this).css('height', scroll_height + 'px');
    }
})

