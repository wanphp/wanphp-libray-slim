<?php

namespace Wanphp\Libray\Slim;

use Psr\Http\Message\UploadedFileInterface;

interface UploaderInterface
{
  /**
   * 上传文件
   * @param string $directory
   * @param array $formData
   * @param UploadedFileInterface $uploadedFile
   * @return array
   */
  public function uploadFile(string $directory, array $formData, UploadedFileInterface $uploadedFile): array;

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
