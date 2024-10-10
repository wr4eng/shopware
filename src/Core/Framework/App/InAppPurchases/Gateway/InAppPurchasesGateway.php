<?php declare(strict_types=1);

namespace Shopware\Core\Framework\App\InAppPurchases\Gateway;

use Shopware\Core\Framework\App\AppEntity;
use Shopware\Core\Framework\App\InAppPurchases\Event\InAppPurchasesGatewayEvent;
use Shopware\Core\Framework\App\InAppPurchases\Payload\InAppPurchasesPayload;
use Shopware\Core\Framework\App\InAppPurchases\Payload\InAppPurchasesPayloadService;
use Shopware\Core\Framework\App\InAppPurchases\Response\InAppPurchasesResponse;
use Shopware\Core\Framework\Context;
use Shopware\Core\Framework\Log\Package;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * @internal
 */
#[Package('checkout')]
readonly class InAppPurchasesGateway
{
    public function __construct(
        private AppEntity $app,
        private Context $context,
        private InAppPurchasesPayloadService $payloadService,
        private EventDispatcherInterface $eventDispatcher,
    ) {
    }

    public function process(InAppPurchasesPayload $payload): ?InAppPurchasesResponse
    {
        $response = $this->payloadService->request($payload, $this->app, $this->context);

        $this->eventDispatcher->dispatch(new InAppPurchasesGatewayEvent($response));

        return $response;
    }
}
