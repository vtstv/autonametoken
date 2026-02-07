<?php
/**
 * @copyright Copyright (c) 2024 Murr <https://github.com/vtstv>
 * @license AGPL-3.0
 */
namespace OCA\AutoNameToken\Listener;

use OCP\EventDispatcher\Event;
use OCP\EventDispatcher\IEventListener;
use OCP\Share\Events\BeforeShareCreatedEvent;
use OCP\Share\IShare;
use OCP\Share\IManager;

class ShareCreatedListener implements IEventListener {
    private IManager $shareManager;

    public function __construct(IManager $shareManager) {
        $this->shareManager = $shareManager;
    }

    public function handle(Event $event): void {
        if (!($event instanceof BeforeShareCreatedEvent)) {
            return;
        }

        $share = $event->getShare();
        
        if ($share->getShareType() !== IShare::TYPE_LINK) {
            return;
        }

        $node = $share->getNode();
        $fileName = $node->getName();
        $token = $this->getUniqueToken($fileName);
        
        $share->setToken($token);
    }

    private function getUniqueToken(string $fileName): string {
        $baseToken = $this->sanitizeToken($fileName);
        $token = $baseToken;
        
        while ($this->tokenExists($token)) {
            $token = $baseToken . rand(10, 99);
        }
        
        return $token;
    }

    private function sanitizeToken(string $fileName): string {
        $token = pathinfo($fileName, PATHINFO_FILENAME);
        $token = preg_replace('/[^a-zA-Z0-9_-]/', '', $token);
        $token = substr($token, 0, 15);
        return $token ?: substr(md5($fileName), 0, 15);
    }

    private function tokenExists(string $token): bool {
        try {
            $this->shareManager->getShareByToken($token);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
