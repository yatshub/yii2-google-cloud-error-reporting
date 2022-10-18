<?php

namespace GoogleErrorReporting;

use Google\Cloud\ErrorReporting\Bootstrap;
use Google\Cloud\Logging\Logger;
use Google\Cloud\Logging\LoggingClient;
use Google\Cloud\Core\Report\SimpleMetadataProvider;
use yii\log\Target;

/**
 * ErrorReporting component.
 */
class ErrorReporting extends Target
{
    /**
     * @var string $projectId
     */
    public $projectId;

    /**
     * @var string $clientSecretPath
     */
    public $clientSecretPath;

    /**
     * @var string $loggerInstance
     */
    public $loggerInstance;

    /**
     * @var string $version
     */
    public $version;

    /**
     * @var string $service
     */
    public $service;

    /**
     * @var Logger $logger
     */
    protected $logger;

    /**
     * Initializes the ErrorReporting component.
     * This method will initialize the Google Cloud property to make sure it refers to a valid Google project.
     */
    public function init()
    {
        parent::init();
        $logging = new LoggingClient([
            'keyFilePath' => $this->clientSecretPath,
            'projectId' => $this->projectId,
        ]);
        $metadata = new SimpleMetadataProvider([], $this->projectId, $this->service, $this->version);
        $psrLogger = $logging->psrLogger('error-log', [
            'metadataProvider' => $metadata,
        ]);
        $this->logger = $logging->logger($this->loggerInstance);
        Bootstrap::init($psrLogger);
    }

    /**
     * {@inheritdoc}
     * @return void
     */
    public function export()
    {
        $this->getLevels();
        foreach ($this->messages as $message) {
            list($text, $level, $category, $timestamp) = $message;
            switch ($level) {
                case 1: {
                    $this->logger->write(
                        Target::formatMessage($message),
                        ['severity' => Logger::ERROR]
                    );
                    break;
                }
                case 2: {
                    $this->logger->write(
                        Target::formatMessage($message),
                        ['severity' => Logger::WARNING]
                    );
                    break;
                }
                case 64:
                case 8: {
                    $this->logger->write(
                        Target::formatMessage($message),
                        ['severity' => Logger::DEBUG]
                    );
                    break;
                }
                default: {
                    $this->logger->write(
                        Target::formatMessage($message),
                        ['severity' => Logger::INFO]
                    );
                    break;
                }
            }
        }
    }
}