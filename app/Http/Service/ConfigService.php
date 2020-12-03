<?php


namespace App\Http\Service;


use App\Models\Config;

class ConfigService extends BaseSerivce
{
    private $config;

    /**
     * CasesService constructor.
     */
    public function __construct()
    {
        $this->config = isset($this->config) ?: new Config();
    }

    /**
     * 配置列表
     * @param string $keyword
     * @param int $limit
     * @return mixed
     */
    public function getConfigLists(string $keyword, int $limit)
    {
        return $this->config->getConfigLists($keyword,$limit);

    }

    /**
     * 修改配置
     * @param array $data
     * @param $loginInfo
     * @return mixed
     */
    public function edit(array $data, $loginInfo)
    {
        $data['update_user_id'] = $loginInfo['id'];;
        $data['update_user_name'] = $loginInfo['username'];
        return $this->config->edit($data);
    }

}
