<?php
declare(strict_types=1);

namespace Zwernemann\Withdrawal\Setup\Patch\Data;

use Magento\Framework\App\Config\Storage\WriterInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory;

class SetDefaultAllowedOrderStatuses implements DataPatchInterface
{
    private const CONFIG_PATH = 'zwernemann_withdrawal/general/allowed_order_statuses';

    public function __construct(
        private readonly WriterInterface $configWriter,
        private readonly CollectionFactory $statusCollectionFactory
    ) {}

    public function apply(): self
    {
        $statuses = $this->statusCollectionFactory->create();
        $allCodes = array_column($statuses->toArray()['items'], 'status');

        if (!empty($allCodes)) {
            $this->configWriter->save(self::CONFIG_PATH, implode(',', $allCodes));
        }

        return $this;
    }

    public function getAliases(): array { return []; }

    public static function getDependencies(): array { return []; }
}
