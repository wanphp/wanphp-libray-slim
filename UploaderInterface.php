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
   * 下载文件
   * @param string $url
   * @return array
   */
  public function downloadFile(string $url): array;

  /**
   * 设置文件名称
   * @param int $id
   * @param string $name
   * @return int
   */
  public function setName(int $id, string $name): int;

  /**
   * 删除文件
   * @param int|string $file 文件ID或文件路径
   * @param bool $deleteFile
   * @return int
   */
  public function delFile(int|string $file, bool $deleteFile = true): int;

  /**
   * 允许上传文件扩展名
   * @param array $extension
   */
  public function allowExtension(array $extension): void;

  /**
   * 允许上传文件类型
   * @param array $fileType
   */
  public function allowFileType(array $fileType): void;
}
