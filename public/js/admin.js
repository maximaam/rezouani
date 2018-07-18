const config = {
    'form': 'form',
    'images': {
        'names_separator': '-',
        'upload_uri': '/ajx/upload-image',
        'delete_uri': '/ajx/delete-image',
        'dir': '/images',
        'products_dir': '/images/products',
        'classes': {
            'file_input': 'js_upload-image',
            'names_input': 'images-names-container',
            'tmp_names_input': 'images-tmp-names-container',
            'update_uploaded_images_container': 'uploaded-images-container',
            'delete': 'js_delete-image',
            'loading_img': 'loading-img',
            'btn_submit': 'sonata-ba-form-actions button[type=submit]'
        }
    }
};

/**
 *
 * @type {appManager|*|{}}
 */
var appManager = appManager || {};
appManager.imageUpload = appManager.imageUpload || {};

/**
 *
 * @param $context
 */
appManager.imageUpload.upload = function($context) {
    $context.on('change', '.' + config.images.classes.file_input, function () {
        let $fileElement = $(this),
            fileData = $fileElement.prop('files')[0],
            formData = new FormData();

        formData.append('file', fileData);
        $fileElement.after(helpers.renderLoadingImg());
        //$fileElement.after(this.files[0].name);

        $.ajax({
            url: config.images.upload_uri,
            type: 'post',
            data: formData,
            dataType: 'text',
            cache: false,
            contentType: false,
            processData: false,
            success: function (filename) {
                let $namesList = $('.' + config.images.classes.names_input),
                    $tmpNamesList = $('.' + config.images.classes.tmp_names_input),
                    namesListVal = $namesList.val(),
                    tmpNamesListVal = $tmpNamesList.val();

                if (namesListVal === '') {
                    $namesList.val(filename + config.images.names_separator);
                    $tmpNamesList.val(filename + config.images.names_separator);
                } else {
                    $namesList.val(namesListVal + filename + config.images.names_separator);
                    $tmpNamesList.val(tmpNamesListVal + filename + config.images.names_separator);
                }

                $('.' + config.images.classes.loading_img).remove();
                $fileElement.after(helpers.renderImg(filename, $namesList.data('pk')));
            },
            error: function (filename) {
                alert('error');
            },
        });
    });
};

/**
 *
 * @param $context
 */
appManager.imageUpload.showOnUpdate = function($context) {
    if ($context.length > 0) {
        let images = $context.val();

        if (images.length > 0) {
            images = images.split(config.images.names_separator);
            $context.after('<div class="' + config.images.classes.update_uploaded_images_container + '"><h3>Current images</h3></div>');

            images.forEach(function (filename, index) {
                if (filename.length > 0) {
                    $('.' + config.images.classes.update_uploaded_images_container).append(helpers.renderImg(filename, $context.data('pk')));
                }
            });
        }
    }
};

/**
 *
 * @param $context
 */
appManager.imageUpload.delete = function($context) {
    $context.on('click', '.' + config.images.classes.delete, function() {

        /*
        if (!confirm('Please confirm delete image?')) {
            return false;
        }
        */

        let $this = $(this),
            filename = $this.data('filename'),
            url = config.images.delete_uri + '?filename=' + filename + '&pk=' + $this.data('pk');

        $.get(url, function(response) {
            if (response === 'success') {
                $this.parent().fadeOut(function () {

                    let $namesList = $('.' + config.images.classes.names_input),
                        $tmpNamesList = $('.' + config.images.classes.tmp_names_input),
                        namesListVal = $namesList.val(),
                        tmpNamesListVal = $tmpNamesList.val();

                    $namesList.val(namesListVal.replace(filename + config.images.names_separator, ''));
                    $tmpNamesList.val(tmpNamesListVal.replace(filename + config.images.names_separator, ''));

                    $(this).remove();
                });

                //let $imgContainer  = $('.' + config.images.classes.update_uploaded_images_container);

                /*
                if ($imgContainer.has('div').length === 1) {
                    $imgContainer.fadeOut();
                }
                */
            } else {
                alert(response);
            }
        });

        return false;
    });
};

/**
 * Delete images that were upload but not persisted
 * @param $context
 */
appManager.imageUpload.deleteOrphans = function($context) {
    $context.unload(function(){
        let $tmpNamesList = $('.' + config.images.classes.tmp_names_input),
            tmpNamesListVal = $tmpNamesList.val();

        if (tmpNamesListVal.length > 0) {

        }
    });
};

/**
 * Prevent from updating without a least one image
 * @param $context
 */
appManager.imageUpload.validateOnUpdate = function($context) {
    $context.on('click', '.' + config.images.classes.btn_submit, function() {

        let $images = $('.' + config.images.classes.names_input);

        if ($images.length > 0) {
            let images = $images.val();

            if (images.length < 1 && $images.data('pk') > 0) {
                alert('Mindestens ein Bild pro Produkt!');
                return false;
            }
        }
    });
};

appManager.colorsList = function(){
    let $list = $("ul[id$='_colors']").find('li');

    $list.each(function (index, item) {

        let $item = $(item),
            color = $item.find('input').data('color');
        $item.css('border-left', '10px solid ' + color);
    });
};

appManager.submitForm = function($context){
    let $form = $context.find('form');

    $form.on('submit', function () {
       alert('ok');
    });


};



let helpers = {
    renderImg: function (filename, pk) {
        return '<div class="uploaded-images-elem">' +
            '<img src="' + config.images.products_dir + '/' + filename +'" width="120">' +
            '<button title="Delete" class="js_delete-image" data-filename="'+ filename +'" data-pk="'+ pk +'">X</button>' +
            '</div>';
    },
    renderLoadingImg: function () {
        return '<div class="'+ config.images.classes.loading_img +'"><img src="' + config.images.dir + '/loading.gif" width="100" height="100"></div>';
    }
};


$(document).ready(function() {

    appManager.run = function($context) {
        if (!$context) {
            $context = $(document);
        }

        let $images = $('.' + config.images.classes.names_input);

        appManager.imageUpload.upload($context);
        appManager.imageUpload.showOnUpdate($images);
        appManager.imageUpload.validateOnUpdate($context);
        appManager.imageUpload.delete($context);

        appManager.colorsList();
    };

    appManager.run();
});

