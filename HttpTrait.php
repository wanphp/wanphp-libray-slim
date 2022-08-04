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
  private function request(Client $client, string $method, string $uri, array $options): array
  {
    try {
      $resp = $client->request($method, $uri, $options);
      $body = $resp->getBody()->getContents();
      if ($resp->getStatusCode() == 200) {
        $content_type = $resp->getHeaderLine('Content-Type');
        if (str_contains($content_type, 'application/json') || str_contains($content_type, 'text/plain')) {
          $json = json_decode($body, true);
          if (json_last_error() === JSON_ERROR_NONE) {
            if (isset($json['errMsg'])) {
              throw new Exception($json['errMsg'], 400);
            } else {
              return $json;
            }
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
}