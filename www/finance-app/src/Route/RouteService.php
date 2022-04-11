<?php

namespace Route;

use Controller\AccountController;

class RouteService
{
    private static function controllers()
    {
        return [
            '' => [
                '' => [
                    '' => [
                        'GET' => function () {
                            return AccountController::indexAction();
                        }
                    ]
                ],
                'account' => [
                    'authorize' => [
                        '' => [
                            'GET' => function () {
                                return AccountController::authorizeAction();
                            },
                            'POST' => function () {
                                return AccountController::authorizeActionPost();
                            }
                        ]
                    ]
                ]
            ],
        ];
    }

    public static function start(array $route, string $method)
    {
        $currentNode = self::controllers();

        foreach ($route as $routeKey) {
            if (isset($currentNode[$routeKey])) {
                $currentNode = $currentNode[$routeKey];
            } else {
                self::ErrorPage404();
            }
        }

        if (!isset($currentNode[$method])) {
            self::ErrorPage405();
        } else {
            $currentNode = $currentNode[$method];

            $currentNode();
        }
    }

    private static function ErrorPage404()
    {
        http_response_code(404);
        exit;
    }

    private static function ErrorPage405()
    {
        http_response_code(405);
        exit;
    }
}
