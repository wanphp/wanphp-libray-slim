<?php

namespace Wanphp\Libray\Slim;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\RequestException;

trait HttpTrait
{
  /**
   * @param Client $client
   * @param string $method
   * @param string $uri
   * @param array $options
   * @return array
   * @throws Exception
   */
  protected function request(Client $client, string $method, string $uri = '', array $options = []): array
  {
    try {
      $resp = $client->request($method, $uri, $options);
      $body = $resp->getBody()->getContents();
      if (in_array($resp->getStatusCode(), [200, 201])) {
        $content_type = $resp->getHeaderLine('Content-Type');
        if (str_contains($content_type, 'application/json') || str_contains($content_type, 'text/plain')) {
          $json = json_decode($body, true);
          if (json_last_error() === JSON_ERROR_NONE) {
            if (isset($json['errcode']) && $json['errcode'] != 0) {
              throw new Exception($json['errcode'] . ' - ' . $json['errmsg'], 400);
            } else if (isset($json['errMsg'])) {
              throw new Exception($json['errMsg'], 400);
            } else {
              return $json;
            }
          }
        }
        // 微信公众号接口返回
        if (str_contains($content_type, 'application/xml') || str_contains($content_type, 'text/plain')) {
          $result = $this->fromXml($body);
          if ($result) {
            // 请求失败
            if (isset($result['return_code']) && $result['return_code'] === 'FAIL') {
              throw new Exception('FAIL - ' . $result['return_msg'], 400);
            }

            if (isset($result['result_code']) && $result['result_code'] === 'FAIL') {
              throw new Exception($result['err_code'] . ' - ' . $result['err_code_des'], 400);
            }
            return $result;
          }
        }
        return ['content_type' => $content_type, 'content_disposition' => $resp->getHeaderLine('Content-disposition'), 'body' => $body];
      } else {
        throw new Exception($resp->getReasonPhrase(), $resp->getStatusCode());
      }
    } catch (RequestException $e) {
      $message = $e->getMessage();
      if ($e->hasResponse()) {
        $message .= "\n" . $e->getResponse()->getStatusCode() . ' ' . $e->getResponse()->getReasonPhrase();
        $message .= "\n" . $e->getResponse()->getBody();
      }
      throw new Exception($message);
    } catch (GuzzleException $e) {
      throw new Exception($e->getMessage(), $e->getCode());
    }
  }

  public function getClientIP()
  {
    return $_SERVER['HTTP_X_REAL_IP'] ?? $_SERVER['REMOTE_ADDR'];
  }

  /**
   * @param $data
   * @param bool $return
   * @return string
   */
  public function toXml($data, bool $return = true): string
  {
    $xml = '';
    foreach ($data as $key => $val) {
      is_numeric($key) && $key = "item";
      $xml .= "<$key>";
      $xml .= is_array($val) ? $this->toXml($val, false) : '<![CDATA[' . preg_replace("/[\\x00-\\x08\\x0b-\\x0c\\x0e-\\x1f]/", '', $val) . ']]>';
      $xml .= "</$key>";
    }
    if ($return) return '<xml>' . $xml . '</xml>';
    else return $xml;
  }

  /**
   * 将xml转为array
   * @param $xml
   * @return array
   */
  public function fromXml($xml): array
  {
    if (!$xml) return [];
    return json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
  }
}
