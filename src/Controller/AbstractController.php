<?php

declare(strict_types=1);

namespace App\Controller;

use App\Request;
use App\View;
use App\Exception\ConfigurationException;

abstract class AbstractController
{
  protected static array $configuration = [];
  protected Request $request;
  protected View $view;
  

  public static function initConfiguration(array $configuration): void
  {
    self::$configuration = $configuration;
  }

  public function __construct(Request $request)
  {
    if (empty(self::$configuration['db'])) {
      throw new ConfigurationException('Configuration error');
    }
    $this->request = $request;
    $this->view = new View();
  }


  abstract public function run(): void;

  abstract protected function action(): string;
  
  protected function redirect(string $to, array $params): void
  {
    $location = $to;

    if (count($params)) {
      $queryParams = [];
      foreach ($params as $key => $value) {
        $queryParams[] = urlencode($key) . '=' . urlencode($value);
      }
      $queryParams = implode('&', $queryParams);
      $location .= '?' . $queryParams;
    }

    header("Location: $location");
    exit;
  }

  // protected function action(): string
  // {
  //   return $this->request->getParam('action', self::DEFAULT_ACTION);
  // }
}
