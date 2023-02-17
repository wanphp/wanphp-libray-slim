<?php

namespace Wanphp\Libray\Slim;

use Slim\Psr7\UploadedFile;

interface UploaderInterface
{
  /**
   * 上传文件
   * @param array $formData
   * @param UploadedFile $uploadedFile
   * @return array
   */
  public function uploadFile(array $formData, UploadedFile $uploadedFile): array;

  /**
   * 设置文件名称
   * @param int $id
   * @param string $name
   * @return int
   */
  public function setName(int $id, string $name): int;

  /**
   * 删除文件
   * @param int $id
   * @return int
   */
  public function delFile(int $id): int;
}
