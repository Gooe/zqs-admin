//图标控件
iconPicker.render({
    // 选择器，推荐使用input
    elem: '.iconPicker',
    // 数据类型：fontClass/unicode，推荐使用fontClass
    type: 'fontClass',
    // 是否开启搜索：true/false，默认true
    search: true,
    // 是否开启分页：true/false，默认true
    page: false,
    // 每页显示数量，默认12
    limit: 140,
    // 每个图标格子的宽度：'43px'或'20%'
    cellWidth: '36px',
    // 点击回调
    click: function (data) {
        //console.log(data);
    },
    // 渲染成功后的回调
    success: function(d) {
        //console.log(d);
    }
});