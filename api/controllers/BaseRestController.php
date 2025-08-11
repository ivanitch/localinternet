<?php

declare(strict_types=1);

namespace api\controllers;

use Yii;
use yii\filters\ContentNegotiator;
use yii\rest\Controller;
use yii\rest\Serializer;
use yii\web\Response;

class BaseRestController extends Controller
{
    protected mixed $args;
    protected mixed $bodyParams;

    /**
     * @param $id
     * @param $module
     * @param array $config
     */
    public function __construct($id, $module, array $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->response   = Yii::$app->getResponse();
        $this->args       = Yii::$app->request->queryParams;
        $this->bodyParams = Yii::$app->request->bodyParams;
    }

    /**
     * @var array
     */
    public $serializer = [
        'class'              => Serializer::class,
        'collectionEnvelope' => 'items',
    ];

    /**
     * @return array
     */
    public function behaviors(): array
    {
        $behaviors = parent::behaviors();

        $behaviors['contentNegotiator'] = [
            'class'   => ContentNegotiator::class,
            'formats' => [
                'application/json' => Response::FORMAT_JSON,
            ],
        ];

        unset($behaviors['rateLimiter']);

        return $behaviors;
    }
}
