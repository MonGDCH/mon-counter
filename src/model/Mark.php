<?php
namespace mon\counter\model;

use mon\orm\Model;

/**
 * 计数器标志位模型
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class Mark extends Model
{
    /**
     * 单例
     *
     * @var void
     */
    protected static $instance;

    /**
     * 操作表
     *
     * @var string
     */
    protected $table = 'cnt_mark';

    /**
     * 单例实现
     *
     * @return void
     */
    public static function instance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * 创建计数器标志索引
     *
     * @param  string  $mark1   标志位1
     * @param  string  $mark2   标志位2
     * @param  string  $remark  备注信息
     * @return [type]          [description]
     */
    public function createMark(string $mark1, string $mark2, string $remark = '')
    {
        $info = $this->getMark($mark1, $mark2);
        // 标志位已存在，直接返回标志位ID
        if ($info) {
            return $info['id'];
        }

        // 不存在标志位，创建标志位
        $info = [
            'mark1'         => $mark1,
            'mark2'         => $mark2,
            'remark'        => $remark,
            'create_time'   => time(),
        ];
        $id = $this->insert($info, false, true);
        if (!$id) {
            $this->error = '创建计数器标志位失败';
            return false;
        }

        return $id;
    }

    /**
     * 获取计数器标志索引
     *
     * @param  string $mark1 标志1
     * @param  string $mark2 标志2
     * @return [type]        [description]
     */
    public function getMark(string $mark1, string $mark2)
    {
        // 缓存数据
        static $cacheList = [];
        $key = $mark1 . '_' . $mark2;
        if (isset($cacheList[$key])) {
            return $cacheList[$key];
        }

        $info = $this->where('mark1', $mark1)->where('mark2', $mark2)->find();
        if (!$info) {
            $this->error = '计数器标志位不存在';
            return false;
        }

        $cacheList[$key] = $info;
        return $cacheList[$key];
    }

    /**
     * 判断计数器标志是否存在
     *
     * @param string $mark1  标志1
     * @param string $mark2  标志2
     * @return boolean
     */
    public function hasMark(string $mark1, string $mark2)
    {
        return !empty($this->getMark($mark1, $mark2));
    }
}
