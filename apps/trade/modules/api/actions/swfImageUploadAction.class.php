<?php
class swfImageUploadAction extends sfAction{
	public function execute($request)
	{
		sfConfig::set('sf_web_debug', false);
		header('Access-Control-Allow-Origin: https://m.kaluli.com');
        header('Access-Control-Allow-Credentials: true');

		if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {
			return $this->renderText(json_encode(array('status'=>500,'msg'=>'非法上传')));
		}
		$size = filesize($_FILES["Filedata"]["tmp_name"]);
		$finfo = new finfo(FILEINFO_MIME_TYPE);
		$ext = array_search(
			$finfo->file($_FILES['Filedata']['tmp_name']),
			array(
				'jpg' => 'image/jpeg',
				'png' => 'image/png',
				'gif' => 'image/gif',
		),true);
		if(empty($ext))
		{
			return $this->renderText(json_encode(array('status'=>500,'msg'=>'图片格式不符合要求')));
		}
		list($width, $height, $type, $attr) = getimagesize($_FILES["Filedata"]["tmp_name"]);

		$type = $request->getParameter('type','def');
		if($type == 'groupon.admin.cover'){
			if(($width/$height > 1.05 || $height/$width > 1.05))
			{
				return $this->renderText(json_encode(array('status'=>500,'msg'=>'图片高宽比不是1比1')));
			}
			if($width < 500 || $height < 500)
			{
				return $this->renderText(json_encode(array('status'=>500,'msg'=>'图片高宽小于500')));
			}
		}elseif($type == 'trade/goods/style'){
            if($width < 400 || $height < 400)
            {
                return $this->renderText(json_encode(array('status'=>500,'msg'=>'图片高宽小于400')));
            }
        }
        
		$new_file_name = $type.'/'.date('Ymd').'/'.md5_file($_FILES['Filedata']['tmp_name']).time().'.'.$ext;
		
		$qiniuObj = new tradeQiNiu();
		$qiuniu_path = $qiniuObj->uploadFile($new_file_name,$_FILES["Filedata"]["tmp_name"]);
        if($type == 'trade/goods/style')
        {
            $new_file_name = $type.'/'.date('Ymd').'/'.md5_file($_FILES['Filedata']['tmp_name']).time().'.'.$ext;
            $qiuniu_path = FunBase::cutGoodsPic($qiuniu_path,$new_file_name);
            if(empty($qiuniu_path))
            {
                return $this->renderText(json_encode(array('status'=>500,'msg'=>'另存图片失败')));
            }
        }
		$info = array();
		$info['status'] = 200;
		$info['data']['url'] = $qiuniu_path;
		$info['data']['size'] = $size;
		return $this->renderText(json_encode($info));
	}
}