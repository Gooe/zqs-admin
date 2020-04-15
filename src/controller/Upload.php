<?php
namespace zqs\admin\controller;
use zqs\admin\model\Attachment;

class Upload extends Admin
{
    protected $noNeedRight = ['*'];
    public function initialize()
    {
        parent::initialize();
        $this->model = new Attachment();
    }
    /**
     * 图片上传
     */
    public function image()
    {
        //默认验证,随后改为数据库配置
        $image_validate = [
            'fileSize' => 2*1024*1024,//1M
            'fileExt' => 'jpg,png,jpeg,gif',
            'fileMime' => 'image/jpeg,image/jpg,image/png,image/gif',
        ];
        //从哪里上传来的  默认表单
        $up_from = input('up_from','form');
        // 获取表单上传文件 例如上传了001.jpg
        //表单名
        $target = input('target','image');
        $file = $this->request->file($target);
        if (!$file){
            return $this->error('请选择图片');
        }
        
        //验证
        $ret = $this->validate(['image'=>$file], ['image'=>$image_validate]);
        
        if (true===$ret){
            //判断是否已经存在附件
            $sha1 = $file->hash();
            $uploaded = $this->model->where('sha1', $sha1)->find();
            if ($uploaded)
            {
                $data = [
                    'id' => $uploaded['id'],
                    'url' => $uploaded['url'],
                    'name' => $uploaded['name']
                ];
                return $this->success('上传成功','',$data);
            }
            //保存文件
            $savename = \think\facade\Filesystem::disk('public')->putFile( 'uploads/image', $file);
            $path = config('filesystem.disks.public.url').'/'.$savename;
            
            //保存文件信息入库
            $imagewidth = $imageheight = 0;
            if (in_array($file->getOriginalExtension(), ['gif', 'jpg', 'jpeg', 'bmp', 'png', 'swf']))
            {
                $imgInfo = getimagesize($file->getPathname());
                $imagewidth = isset($imgInfo[0]) ? $imgInfo[0] : $imagewidth;
                $imageheight = isset($imgInfo[1]) ? $imgInfo[1] : $imageheight;
            }
            $params = array(
                'name'        => $file->getOriginalName(),
                'filesize'    => $file->getSize(),
                'imagewidth'  => $imagewidth,
                'imageheight' => $imageheight,
                'imagetype'   => $file->getOriginalExtension(),
                'imageframes' => 0,
                'mimetype'    => $file->getMime(),
                'url'         => $path,
                'upload_time' => time(),
                'sha1'        => $sha1,
                'storage'     => 'public',
            );
            
            //入库
            $this->model->save(array_filter($params));
            
            $data = [
                'id' => $this->model->id,
                'url' => $params['url'],
                'name' => $params['name']
            ];
            
            return $this->success('上传成功','',$data);
        }else {
            return $this->error($ret['msg']);
        }
        
        
    }
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    
}