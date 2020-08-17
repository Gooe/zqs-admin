var t = layui.tinymce
// 创建编辑器 t.render(option，load_callback)
t.render({
    elem: "#tinymce_{$name}",
    // 支持tinymce所有配置
    //skin: 'oxide-dark',
    height:'100%',
    //automatic_uploads: false,
    images_upload_url: '{:url('upload/image',['up_from'=>'tinymce'])}',
    setup: function(editor){ 
        editor.on('change',function(){ editor.save(); });
    },
    menubar: false,
    convert_urls: false,
    plugins:'code image media link emoticons print preview colorpicker fullscreen table',
    toolbar: [
        "code | undo redo | image media link emoticons | bold italic underline strikethrough | forecolor backcolor |table| styleselect fontselect fontsizeselect",
        "bullist numlist outdent indent | alignleft aligncenter alignright alignjustify | print preview colorpicker fullscreen"
    ]

    

},(opt)=>{
    //加载完成后回调 opt 是传入的所有参数
    
});

