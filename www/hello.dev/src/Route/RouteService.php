<?php

namespace Route;

use Controller\AccountController;

class RouteService {
 private $route;

 private function controllers()
 {
  return [
   '' => [
    '' => function() { return AccountController::indexAction(); },
    'account' => [
     '' => function() { return AccountController::indexAction(); }
    ]
   ],
  ];
 }

 public function callAction(array $route)
 {
   $action = $this->getAction($route);

   if ($action !== false) {
      $result = $action();
   } else {
      $result = "Page not found.";
   }

   return $result;
 }

 public function getAction(array $route)
 {
  $currentNode = $this->controllers();

  foreach ($route as $routeKey) {
   if (isset($currentNode[$routeKey])) {
    $currentNode = $currentNode[$routeKey];
   } else {
    $currentNode = false;
    break;
   }
  }

  return $currentNode;
 }
}