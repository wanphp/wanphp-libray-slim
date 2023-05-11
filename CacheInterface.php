<?php

namespace Wanphp\Libray\Slim;

use Exception;

interface CacheInterface
{
  /**
   * 获取缓存
   * @param string $key 缓存键
   * @param mixed|null $default 默认值
   * @return mixed
   * @throws Exception 如果键不是合法值
   */
  public function get(string $key, mixed $default = null): mixed;

  /**
   * 添加缓存，重复添加直接覆盖
   * @param string $key 缓存键
   * @param mixed $value 缓存值
   * @param int|null $ttl 缓存时间，单位为秒，null 为长期
   * @return bool
   * @throws Exception 如果键不是合法值
   */
  public function set(string $key, mixed $value, null|int $ttl = null): bool;

  /**
   * 删除缓存项
   * @param string $key 要删除的键
   * @return bool
   * @throws Exception 如果有键不是合法值
   */
  public function delete(string $key): bool;

  /**
   * 清除所有缓存
   * @return bool
   */
  public function clear(): bool;

  /**
   * 通过唯一键获取多个缓存项，有重复的直接覆盖
   * @param array<string> $keys 一次获取多个键值
   * @param mixed $default 不存在返回默认值
   * @return array<string, mixed>
   */
  public function getMultiple(array $keys, mixed $default = null): iterable;

  /**
   * 一次添加多个缓存项
   * @param array<string, mixed> $values
   * @param null|int $ttl 缓存时间，单位为秒，null 为长期
   * @return bool
   * @throws Exception
   */
  public function setMultiple(array $values, null|int $ttl = null): bool;

  /**
   * 一次删除多个缓存项
   * @param array<string> $keys 要删除的键列表
   * @return bool
   * @throws Exception 如果有键不是合法值
   */
  public function deleteMultiple(iterable $keys): bool;
}
