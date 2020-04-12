//拖拽上传,单图
upload.render({
	elem: '#upload_img_{$name}'
	,url: '{:url('upload/image')}' //改成您自己的上传接口
	,accept:'images'
	,field:'image'
	,acceptMime: 'image/*'
	,done: function(res){
	  if (res.code==1) {
	  	layui.$('#upload_img_view_{$name}').removeClass('layui-hide').find('img').attr('src', res.data.url);
	  	layui.$('#upload_img_view_{$name}').find('input').val(res.data.id);
	  	layer.msg(res.msg,{icon:1});
	  }else{
	  	layer.msg(res.msg,{icon:2});
	  }
	}
});