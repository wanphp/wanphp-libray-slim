<?php

namespace Wanphp\Libray\Slim;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

interface WpUserInterface
{
  /**
   * 客户端注册添加新用户
   * @param array $data
   * @return array
   */
  public function addUser(array $data): array;

  /**
   * 客户端更新用户信息
   * @param int $uid
   * @param array $data
   * @return array
   */
  public function updateUser(int $uid, array $data): array;

  /**
   * 取用户信息
   * @param int $uid
   * @return array
   */
  public function getUser(int $uid): array;

  /**
   * 通过用户ID取用户信息
   * @param array $uidArr
   * @return array
   */
  public function getUsers(array $uidArr): array;

  /**
   * 搜索用户
   * @param string $keyword
   * @param int $page
   * @return array
   */
  public function searchUsers(string $keyword, int $page = 0): array;

  /**
   * 通过微信服务号发送模板消息
   * @param array $uidArr
   * @param array $msgData
   * @return array
   */
  public function sendMessage(array $uidArr, array $msgData): array;

  /**
   * 给公众号用户添加标签
   * @param string $uid
   * @param int $tagId
   * @return array
   */
  public function membersTagging(string $uid, int $tagId): array;

  /**
   * 给公众号用户移除标签
   * @param string $uid
   * @param int $tagId
   * @return array
   */
  public function membersUnTagging(string $uid, int $tagId): array;

  /**
   * 用户帐号密码登录
   * @param string $account
   * @param string $password
   * @return int|string
   */
  public function userLogin(string $account, string $password): int|string;

  /**
   * 用户授权操作，第一步：资源服务器，前往认证服务器获取code
   * @throws Exception
   */
  public function oauthRedirect(Request $request, Response $response): Response;

  /**
   * 用户授权操作，第二步：通过code换取网页授权access_token
   * @param string $code
   * @param string $redirect_uri
   * @return string
   * @throws Exception
   */
  public function getOauthAccessToken(string $code, string $redirect_uri): string;

  /**
   * 用户授权操作，第三步：获取用户信息
   * @param string $access_token
   * @return array
   * @throws Exception
   */
  public function getOauthUserinfo(string $access_token): array;

  /**
   * 用户授权操作，更新用户信息
   * @param string $access_token
   * @param array $data
   * @return array
   * @throws Exception
   */
  public function updateOauthUser(string $access_token, array $data): array;

  /**
   * 检查用户授权，过期则刷新
   * @return string $access_token
   * @throws Exception
   */
  public function checkOauthUser(): string;
}
