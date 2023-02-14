$(document).ready(function () {
    var id = $('.reviewedit').attr('data-reviewid');
    var blockid = null;
    var type = null;
    var value = null
    $('.openbutton').click(function () {
        $("#settingsmenu").toggleClass('hidden');
    })
    // Text area set height.
    $("textarea").each(function (textarea) {
        $(this).height($(this)[0].scrollHeight);
    });
    $("textarea").on('input', function () {
        var scroll_height = $(this).get(0).scrollHeight;

        $(this).css('height', scroll_height + 'px');
    })
    $("textarea").change(function () {
        var id = $('.reviewedit').attr('data-reviewid');
        var name = $(this).attr('name');
        if ($(this).attr('data-blockid')) {
            var blockid = $(this).attr('data-blockid');
        } else {
            var blockid = null;
        }
        var type = $(this).attr('data-type');
        var value = $(this).val();

        var data = {
            'id': id,
            'name': name,
            'blockid': blockid,
            'type': type,
            'value': value
        }
        $.ajax({
            type: "POST",
            url: "/ajax/admin/review/edit?type=block",
            data: data
        }).done(function (response) {
            if (response.type == 'Updated') {
                console.log(`Updated ${blockid}'s ${name} with ${value}`);
            }
        }).fail(function (jqXHR, textStatus, errorMessage) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorMessage);
        });
    })
    $("input[type='text']").change(function () {
        var id = $('.reviewedit').attr('data-reviewid');
        var name = $(this).attr('name');
        if ($(this).attr('data-blockid')) {
            var blockid = $(this).attr('data-blockid');
        } else {
            var blockid = null;
        }
        var type = $(this).attr('data-type');
        var value = $(this).val();

        var data = {
            'id': id,
            'name': name,
            'blockid': blockid,
            'type': type,
            'value': value
        }
        $.ajax({
            type: "POST",
            url: "/ajax/admin/review/edit?type=block",
            data: data
        }).done(function (response) {
            if (response.type == 'Updated') {
                console.log(`Updated ${blockid}'s ${name} with ${value}`);
            }
        }).fail(function (jqXHR, textStatus, errorMessage) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorMessage);
        });
    });
    $('.platformclick').click(function () {
        var id = $(this).attr('data-reviewid');
        var platform = $(this).attr('data-platformid');
        var type = $(this).attr('data-type');

        var data = {
            'id': id,
            'platform': platform,
            'type': type
        }

        $.ajax({
            type: "POST",
            url: "/ajax/admin/review/edit?type=platform",
            data: data,
        }).done(function (response) {
            console.log(response);
            console.log(data);
            if (response.display == false) {
                location.reload();
            } else {
                $('.platformreview').addClass('hidden');
                $(`.platformreview[data-platformid=${platform}]`).removeClass('hidden');
            }
        }).fail(function (jqXHR, textStatus, errorMessage) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorMessage);
        });
    });
    $("input#review_public").change(function () {
        var id = $(this).attr('data-reviewid');
        var name = $(this).attr('name');
        var type = $(this).attr('data-type');

        var data = {
            'id': id,
            'name': name,
            'type': type,
        }
        $.ajax({
            type: "POST",
            url: "/ajax/admin/review/edit?type=block",
            data: data
        }).done(function (response) {
            location.reload();
        }).fail(function (jqXHR, textStatus, errorMessage) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorMessage);
        });
    })
    $("input[type=number]").change(function () {
        var id = $('.reviewedit').attr('data-reviewid');
        var name = $(this).attr('name');
        if ($(this).attr('data-blockid')) {
            var blockid = $(this).attr('data-blockid');
        } else {
            var blockid = null;
        }
        var type = $(this).attr('data-type');
        var value = $(this).val();

        var data = {
            'id': id,
            'name': name,
            'blockid': blockid,
            'type': type,
            'value': value
        }
        $.ajax({
            type: "POST",
            url: "/ajax/admin/review/edit?type=block",
            data: data
        }).done(function (response) {
            if (response.type == 'Updated') {
                console.log(`Updated ${blockid}'s ${name} with ${value}`);
            }
        }).fail(function (jqXHR, textStatus, errorMessage) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorMessage);
        });
    })
    // Image upload
    $('label.uploadpng').click(function () {
        console.log(this);
        if ($(this).attr('data-blockid')) {
            blockid = $(this).attr('data-blockid');
        } else {
            blockid = null;
        }
        type = $(this).attr('data-type');
        value = $(this).val();
    })
    $("#uploadpng").change(function () {
        var file = $(this).prop("files")[0]
        var form = new FormData();
        form.append('id', id);
        form.append('blockid', blockid);
        form.append('type', 'pictureblock');
        form.append('image', file);

        $.ajax({
            url: "/ajax/admin/review/edit?type=pictureblock",
            type: "POST",
            data: form,
            contentType: false,
            processData: false,
        }).done(function (response) {
            if (response.error == 200) {
                // location.reload();
                console.log(response);
            }
        }).fail(function (jqXHR, textStatus, errorMessage) {
            console.log(jqXHR);
            console.log(textStatus);
            console.log(errorMessage);
        })
    })

    var canvas = $("#cropperimg"),
        context = canvas.get(0).getContext("2d"),
        $result = $('#headbackground');

    // Cropper properties.
    $("#headerpng").change(function () {
        $('.cropperarea').removeClass('hidden');
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
                            minContainerHeight: 100
                        });
                        $('#btnCrop').click(function () {
                            // Get a string base 64 data url
                            var croppedImageDataURL = canvas.cropper('getCroppedCanvas').toDataURL("image/png");
                            var data = {
                                'id': id,
                                'type': 'picturehead',
                                'value': croppedImageDataURL
                            }

                            $.ajax({
                                url: "/ajax/admin/review/edit?type=picturehead",
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
                        $('#btnRestore').click(function () {
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
})