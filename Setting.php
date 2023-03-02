<?php

declare(strict_types=1);

namespace Wanphp\Libray\Slim;

class Setting
{
  private array $settings;

  public function __construct(array $settings)
  {
    $this->settings = $settings;
  }

  /**
   * @param string $key
   * @param $value
   * @return void
   */
  public function set(string $key, $value)
  {
    $this->settings[$key] = $value;
  }

  /**
   * @param string $key
   * @return mixed
   */
  public function get(string $key = ''): mixed
  {
    return empty($key) ? $this->settings : $this->settings[$key] ?? '';
  }
}
