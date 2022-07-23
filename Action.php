<?php

namespace Wanphp\Libray\Slim;

use Exception;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Views\Twig;

abstract class Action
{
  /**
   * @var Request
   */
  protected Request $request;

  /**
   * @var Response
   */
  protected Response $response;

  /**
   * @var array
   */
  protected array $args;

  /**
   * @param Request $request
   * @param Response $response
   * @param array $args
   * @return Response
   * @throws HttpNotFoundException
   */
  public function __invoke(Request $request, Response $response, array $args): Response
  {
    $this->request = $request;
    $this->response = $response;
    $this->args = $args;

    try {
      return $this->action();
    } catch (HttpNotFoundException|Exception $e) {
      throw new HttpNotFoundException($this->request, $e->getMessage());
    }
  }

  /**
   * @return Response
   * @throws HttpNotFoundException
   * @throws HttpBadRequestException
   * @throws Exception
   */
  abstract protected function action(): Response;

  /**
   * @return array
   * @throws HttpBadRequestException
   */
  protected function getFormData(): array
  {
    if (str_contains($this->request->getHeaderLine('Content-Type'), 'application/json')) {
      $postData = json_decode(file_get_contents('php://input'), true);
      if (json_last_error() !== JSON_ERROR_NONE) throw new HttpBadRequestException($this->request, '提交JSON串格式错误。');
    } else {
      $postData = $this->request->getParsedBody();
    }
    return $postData;
  }

  /**
   * @param string $name
   * @return mixed
   * @throws HttpBadRequestException
   */
  protected function resolveArg(string $name): mixed
  {
    if (!isset($this->args[$name])) throw new HttpBadRequestException($this->request, "未知参数 `$name`。");
    return $this->args[$name];
  }

  /**
   * @return int
   * @throws HttpUnauthorizedException
   */
  protected function getUid(): int
  {
    $userid = (int)$this->request->getAttribute('oauth_user_id', 0);
    if ($userid < 1) throw new HttpUnauthorizedException($this->request, "未知用户!");
    return $userid;
  }

  /**
   * @param array $data
   * @param int $statusCode
   * @return Response
   */
  protected function respondWithData(array $data = [], int $statusCode = 200): Response
  {
    $json = json_encode($data, JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE + JSON_NUMERIC_CHECK);
    $this->response->getBody()->write($json);
    return $this->respond($statusCode);
  }

  /**
   * @param null $error
   * @param int $statusCode
   * @return Response
   */
  protected function respondWithError($error = null, int $statusCode = 400): Response
  {
    $json = json_encode(['errMsg' => $error], JSON_PRETTY_PRINT + JSON_UNESCAPED_UNICODE);
    $this->response->getBody()->write($json);
    return $this->respond($statusCode);
  }

  /**
   * @param string $template 模板路径
   * @param array $data
   * @return Response
   * @throws Exception
   */
  protected function respondView(string $template, array $data = []): Response
  {
    $tplVars = $this->request->getAttribute('tplVars') ?? [];
    if (is_array($tplVars)) $data = array_merge($data, $tplVars);
    return Twig::fromRequest($this->request)->render($this->response, $template, $data);
  }

  /**
   * @param $statusCode
   * @return Response
   */
  protected function respond($statusCode): Response
  {
    return $this->response->withHeader('Content-Type', 'application/json')->withStatus($statusCode);
  }

  protected function getLimit(): array
  {
    $params = $this->request->getQueryParams();
    return [$params['start'] ?? 0, $params['length'] ?? 10];
  }

  protected function getOrder(): array
  {
    $params = $this->request->getQueryParams();
    $order = [];
    if (isset($params['order'])) foreach ($params['order'] as $param) {
      $order[$params['columns'][$param['column']]['data']] = strtoupper($param['dir']);
    }
    return $order;
  }
}