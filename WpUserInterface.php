<?php

namespace Wanphp\Libray\Slim;

interface WpUserInterface
{
  public function addUser(array $data): array;

  public function updateUser(int $uid, array $data): array;

  public function getUser(int $uid): array;

  public function getUsers(array $uidArr): array;

  public function searchUsers(string $keyword, int $page = 0): array;

  public function sendMessage(array $uidArr, array $msgData): array;
}
