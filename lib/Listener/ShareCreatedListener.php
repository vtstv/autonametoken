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

class ShareCreatedListener implements IEventListener {
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
        $token = $this->sanitizeToken($fileName);
        
        $share->setToken($token);
    }

    private function sanitizeToken(string $fileName): string {
        $token = pathinfo($fileName, PATHINFO_FILENAME);
        $token = preg_replace('/[^a-zA-Z0-9_-]/', '', $token);
        $token = substr($token, 0, 15);
        return $token ?: substr(md5($fileName), 0, 15);
    }
}
