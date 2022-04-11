<?php

namespace Route;

class RouteParser {
  private $route;
  private $query;

  public function __construct(string $route)
  {
    $this->parseRoute($route);
  }

  private function parseRoute($route) {
    $uriSplittedByQuerySymbol = explode('?', $_SERVER['REQUEST_URI']);
    $this->route = $uriSplittedByQuerySymbol[0];
    $this->query = $uriSplittedByQuerySymbol[1];
  }

  public function getRoute()
  {
    return $this->route;
  }

  public function getSplittedRoute()
  {
    return explode('/', $this->getRoute());
  }

  public function getQuery()
  {
    return $this->query;
  }
}