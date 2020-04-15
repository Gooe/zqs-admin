layedit.set({
    uploadImage: {
        url: '{:url('upload/image')}',
        accept: 'image',
        acceptMime: 'image/*',
        field:'image',
        exts: 'jpg|png|gif|bmp|jpeg',
        size: 1024 * 10,
        data:{editor_type:'layedit'},
        done: function (data) {
            console.log(data);
        }
    }
    , autoSync: true
    , devmode: false
    , tool: [
        'html', 'undo', 'redo', 'code', 'strong', 'italic', 'underline', 'del', 'addhr', '|', 'fontFomatt', 'fontfamily','fontSize', 'fontBackColor', 'colorpicker', 'face'
        , '|', 'left', 'center', 'right', '|', 'link', 'unlink', 'images', 'image_alt', 'anchors'
        , '|'
        , 'table', 'fullScreen'
    ]
    , height: '80%'
});

var editor_{$name} = layedit.build('layedit_{$name}'); //建立编辑器