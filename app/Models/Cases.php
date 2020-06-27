<?php

namespace App\Models;

/**
 * 案例模型
 * Class Cases
 * @package App\Models
 */
class Cases extends Base
{    //定义模型关联表
    protected $table = 'hp_cases';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    protected $guarded = [];

    /*--------------------------------小程序----------------------------------*/

    /**
     * 小程序首页案例
     * @return mixed
     */
    public function getCaseLists($limit){
        $map = ['status'=>10,'is_delete'=>0];
        $field = ['id','case_name','case_cover'];
        return self::select($field)->where($map)->orderBy('sort','desc')->paginate($limit);
    }

    /**
     * 案例详情
     * @param $id
     * @return mixed
     */
    public static function getCasesDetail($id)
    {
        $map = ['status'=>10,'is_delete'=>0,'id'=>$id];
        $field = ['id','case_name','case_desc','sort','case_cover','content','create_time'];
        return self::select($field)->where($map)->first();
    }

    /**
     * 案例列表
     * @param string $keyword
     * @param int $limit
     * @return mixed
     */
    public function getCasesAdminLists(string $keyword, int $limit)
    {
        $map = ['is_delete'=>0];
        $field = ['id','case_name','case_desc','case_cover','status','content','sort',
           'update_time','create_time','update_user_name'];
        return self::select($field)
            ->when(!empty($keyword),function ($query) use ($keyword){
                return $query->where('case_name','like','%'.$keyword.'%');
            })
            ->where($map)
            ->orderBy('sort','desc')
            ->orderBy('id','desc')
            ->paginate($limit);
    }

    /**
     * 添加
     * @param array $data
     * @return mixed
     */
    public function addCases(array $data)
    {
        return self::create($data);
    }
    /**
     * 修改
     * @param array $data
     * @return mixed
     */
    public function editCases(array $data)
    {
        return self::where(["id"=>$data['id']])->update($data);
    }

    /**
     * 批量删除
     * @param $data
     * @param array $ids
     * @return mixed
     */
    public function delBatch($data, array $ids)
    {
        return self::whereIn('id',$ids)->update($data);
    }
    /**
     * 修改状态
     * @param array $data
     * @return mixed
     */
    public function updateStatus(array $data)
    {
        return self::where(['id'=>$data['id']])->update($data);
    }
}
