<?php
namespace mon\counter\model;

use mon\orm\Model;
use mon\counter\model\Mark;

/**
 * 计数器模型
 * 
 * @author Mon <985558837@qq.com>
 * @version 1.0.0
 */
class Counter extends Model
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
    protected $table = 'cnt_counter';

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
     * 创建计数器，如果标志位不存在则自动创建标志位
     *
     * @param string  $mark1    标志位1
     * @param string  $mark2    标志位2
     * @param integer $count    初始化计数
     * @param integer $uid      预留ID字段（int类型）
     * @param string  $str      预留str字段（varchar类型）
     * @param string  $remard   标志位备注信息
     * @return boolean
     */
    public function createCounterOfMark(string $mark1, string $mark2, int $count = 0, int $uid = 0, string $str = '', string $remark = '')
    {
        // 获取标志位
        $markInfo = Mark::instance()->createMark($mark1, $mark2, $remark);
        if (!$markInfo) {
            $this->error = '创建计数器标志位失败';
            return false;
        }
        // 获取标志位ID
        $mark_id = $markInfo['id'];
        // 创建计数器
        $now = time();
        $info = [
            'mark_id'       => $mark_id,
            'count'         => $count,
            'uid'           => $uid,
            'str'           => $str,
            'update_time'   => $now,
            'create_time'   => $now,
        ];
        $save = $this->insert($info);
        if (!$save) {
            $this->error = '创建计数器失败';
            return false;
        }

        return true;
    }

    /**
     * 创建计数器
     *
     * @param string  $mark1    标志位1
     * @param string  $mark2    标志位2
     * @param integer $count    初始化计数
     * @param integer $uid      预留ID字段（int类型）
     * @param string  $str      预留str字段（varchar类型）
     * @return boolean
     */
    public function createCounter(string $mark1, string $mark2, int $count = 0, int $uid = 0, string $str = '')
    {
        // 获取标志位
        $markInfo = Mark::instance()->getMark($mark1, $mark2);
        if (!$markInfo) {
            $this->error = '计数器标志位未创建';
            return false;
        }
        // 获取标志位ID
        $mark_id = $markInfo['id'];
        // 创建计数器
        $now = time();
        $info = [
            'mark_id'       => $mark_id,
            'count'         => $count,
            'uid'           => $uid,
            'str'           => $str,
            'update_time'   => $now,
            'create_time'   => $now,
        ];
        $save = $this->insert($info);
        if (!$save) {
            $this->error = '创建计数器失败';
            return false;
        }

        return true;
    }

    /**
     * 查询是否存在计数器
     *
     * @param string  $mark1 标志位1
     * @param string  $mark2 标志位2   
     * @param integer $uid   附加条件
     * @param string  $str   附加条件
     * @return boolean
     */
    public function hasCounter(string $mark1, string $mark2, int $uid = 0, string $str = '')
    {
        $markInfo = Mark::instance()->getMark($mark1, $mark2);
        if ($markInfo === false) {
            $this->error = Mark::instance()->getError();
            return false;
        }

        $info = $this->where('mark_id', $markInfo['id'])->where('uid', $uid)->where('str', $str)->find();
        return !empty($info);
    }

    /**
     * 增加计数，快捷修改计数
     *
     * @param string  $mark1 标志位1
     * @param string  $mark2 标志位1
     * @param integer $step  增加步长
     * @param integer $uid   附加条件
     * @param string  $str   附加条件
     * @return boolean
     */
    public function addCount(string $mark1, string $mark2, int $step = 1, int $uid = 0, string $str = '')
    {
        $markInfo = Mark::instance()->getMark($mark1, $mark2);
        if ($markInfo === false) {
            $this->error = Mark::instance()->getError();
            return false;
        }

        // 增加计数
        $save = $this->where('mark_id', $markInfo['id'])->where('uid', $uid)->where('str', $str)->setInc('count', $step);
        if (!$save) {
            $this->error = '增加计数失败';
            return false;
        }

        return true;
    }

    /**
     * 减少计数，快捷修改计数
     *
     * @param string  $mark1 标志位1
     * @param string  $mark2 标志位1
     * @param integer $step  减少步长
     * @param integer $uid   附加条件
     * @param string  $str   附加条件
     * @return boolean
     */
    public function reduceCount(string $mark1, string $mark2 = '', int $step = 1, int $uid = 0, string $str = '')
    {
        $markInfo = Mark::instance()->getMark($mark1, $mark2);
        if ($markInfo === false) {
            $this->error = Mark::instance()->getError();
            return false;
        }

        // 减少计数
        $save = $this->where('mark_id', $markInfo['id'])->where('uid', $uid)->where('str', $str)->setDec('count', $step);
        if (!$save) {
            $this->error = '减少计数失败';
            return false;
        }

        return true;
    }

    /**
     * 修改计数
     *
     * @param string  $mark1 标志位1
     * @param string  $mark2 标志位2
     * @param integer $count 新的计数
     * @param integer $uid   附加条件
     * @param string  $str   附加条件
     * @return void
     */
    public function modifyCount(string $mark1, string $mark2 = '', int $count, int $uid = 0, string $str = '')
    {
        $markInfo = Mark::instance()->getMark($mark1, $mark2);
        if ($markInfo === false) {
            $this->error = Mark::instance()->getError();
            return false;
        }

        // 修改计数
        $save = $this->where('mark_id', $markInfo['id'])->where('uid', $uid)->where('str', $str)->update(['count' => $count]);
        if (!$save) {
            $this->error = '修改计数失败';
            return false;
        }

        return true;
    }

    /**
     * 查询计数
     *
     * @param string  $mark1 标志位1
     * @param string  $mark2 标志位2
     * @param integer $uid   附加条件
     * @param string  $str   附加条件
     * @return void
     */
    public function queryCount(string $mark1, string $mark2 = '', int $uid = null, string $str = null)
    {
        $markInfo = Mark::instance()->getMark($mark1, $mark2);
        if ($markInfo === false) {
            $this->error = Mark::instance()->getError();
            return false;
        }

        // 查询数据
        $this->where('mark_id', $markInfo['id'])->field('count');
        if (!is_null($uid)) {
            $this->where('uid', $uid);
        }
        if (!is_null($str)) {
            $this->where('str', $str);
        }
        $info = $this->find();
        if (!$info) {
            $this->error = '计数器不存在';
            return false;
        }

        return $info['count'];
    }

    /**
     * 查询计数器个数
     *
     * @param string  $mark1 标志位1
     * @param string  $mark2 标志位2
     * @param integer $uid   附加条件
     * @param string  $str   附加条件
     * @return void
     */
    public function queryTotal(string $mark1, string $mark2 = '', int $uid = null, string $str = null)
    {
        $markInfo = Mark::instance()->getMark($mark1, $mark2);
        if ($markInfo === false) {
            $this->error = Mark::instance()->getError();
            return false;
        }

        // 查询数据
        $this->where('mark_id', $markInfo['id'])->field('id');
        if (!is_null($uid)) {
            $this->where('uid', $uid);
        }
        if (!is_null($str)) {
            $this->where('str', $str);
        }
        $count = $this->count();
        if ($count === false) {
            $this->error = '查询失败';
            return false;
        }

        return $count;
    }
}
