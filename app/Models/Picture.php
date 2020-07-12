<?php


namespace App\Models;


/**
 * 图片模型
 * Class Picture
 * @package App\Models
 */
class Picture extends Base
{
    //定义模型关联表
    protected $table = 'hp_picture';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = null;

    //时间格式
    protected $dateFormat = 'U';

    //隐藏字段
    protected $hidden = ['is_delete'];

    //设置保存字段
    protected $guarded = [];

    /**
     * 添加图片
     * @param $data
     * @return mixed
     */
    public function addPicture($data){
        return self::insert($data);
    }

    /**
     * 删除图片
     * @param $id
     * @return mixed
     */
    public function deletePic($id){
        return self::where(['pic_id'=>$id])->update(['is_delete'=>1]);
    }
}
