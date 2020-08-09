<?php

namespace App\Models;


/**
 * 私人定制模型
 * Class Cases
 * @package App\Models
 */
class Person extends Base
{    //定义模型关联表
    protected $table = 'person';
    //时间转换
    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'update_time';

    //设置保存字段
    protected $guarded  = ['is_audit','is_delete'];

    /**
     * 定制分类
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function cate(){
        return $this->hasOne('App\Models\PersonCate','id','cate_id')
            ->select(['id','cate_name'])
            ->where(['is_delete'=>0]);
    }
    /*--------------------------------小程序----------------------------------*/
    /**
     * 查询本月是否已经提交过私人定制计划
     * @param $userId
     * @return mixed
     */
    public function getMonthPerson($userId)
    {
        //当月
        $month = date('m');
        $map = ['user_id'=>$userId,'is_delete'=>0];
        return self::where($map)->whereIn('is_audit',[10,20])->whereMonth('create_time',$month)->count();
    }

    /**
     * 提交私人定制
     * @param $data
     * @return mixed
     */
    public function addPerson($data){
        return self::create($data);
    }

    /*------------------------------------后端私人定制--------------------------------*/

    /**
     * 后端私人定制
     * @param String $keywords
     * @param String $userinfo
     * @param int $cateId
     * @param int $limit
     * @return mixed
     */
    public function getPersonLists($keywords, $cateId,$startTime,$endTime,$limit)
    {
        //设置搜索条件
        $map = ['is_delete'=>0];
        return self::select("*")
            ->when(!empty($keywords),function ($query) use ($keywords){
                return $query->where('person_name','like','%'.$keywords.'%')
                    ->orWhere('phone','like','%'.$keywords.'%')
                    ->orWhere('company','like','%'.$keywords.'%');
            })
            ->when($cateId > 0,function ($query) use ($cateId){
                return $query->where('cate_id',$cateId);
            })
            ->when(!empty($startTime),function ($query) use ($startTime){
                return $query->whereDate('create_time','>',$startTime);
            })
            ->when(!empty($endTime),function ($query) use ($endTime){
                return $query->whereDate('create_time','<',$endTime);
            })
            ->where($map)
            ->with('cate')
            ->orderBy('id','desc')
            ->paginate($limit);
    }

    /**
     * 私人定制详情
     * @param $id
     * @return mixed
     */
    public function getAdminPersonById($id)
    {
        $map = ['id'=>$id,'is_delete'=>0];
        $field = ['id','person_name','phone','company','person_price','sales_price','ocupation','person_remark'];
        return self::select($field)->where($map)->first();
    }

    /**
     * 编辑
     * @param array $data
     * @return mixed
     */
    public function editPerson(array $data)
    {
        return self::where('id',$data['id'])->update($data);
    }

    /**
     * 批量删除
     * @param $data
     * @param string|null $ids
     * @return mixed
     */
    public function delBatch($data, $ids)
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
        return self::where('id',$data['id'])->update($data);
    }

}
