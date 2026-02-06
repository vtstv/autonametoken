<?php
/**
 * @copyright Copyright (c) 2024 Murr <https://github.com/vtstv>
 * @license AGPL-3.0
 */
namespace OCA\AutoNameToken\AppInfo;

use OCP\AppFramework\App;
use OCP\AppFramework\Bootstrap\IBootContext;
use OCP\AppFramework\Bootstrap\IBootstrap;
use OCP\AppFramework\Bootstrap\IRegistrationContext;
use OCA\AutoNameToken\Listener\ShareCreatedListener;
use OCP\Share\Events\BeforeShareCreatedEvent;

class Application extends App implements IBootstrap {
    public const APP_ID = 'autonametoken';

    public function __construct() {
        parent::__construct(self::APP_ID);
    }

    public function register(IRegistrationContext $context): void {
        $context->registerEventListener(BeforeShareCreatedEvent::class, ShareCreatedListener::class);
    }

    public function boot(IBootContext $context): void {
    }
}
