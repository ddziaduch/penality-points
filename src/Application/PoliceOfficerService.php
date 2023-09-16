<?php

declare(strict_types=1);

namespace ddziaduch\PenaltyPoints\Application;

use ddziaduch\PenaltyPoints\Application\Ports\Primary\PoliceOfficer;
use ddziaduch\PenaltyPoints\Application\Ports\Secondary\GetDriverFile;
use ddziaduch\PenaltyPoints\Application\Ports\Secondary\StoreDriverFile;
use Psr\Clock\ClockInterface;

final readonly class PoliceOfficerService implements PoliceOfficer
{
    public function __construct(
        private ClockInterface $clock,
        private GetDriverFile $getDriverFile,
        private StoreDriverFile $storeDriverFile,
    ) {}

    public function imposePenalty(
        string $driverLicenseNumber,
        bool $isPaid,
        int $numberOfPoints,
    ): void {
        $now = $this->clock->now();
        $driverFile = $this->getDriverFile->get($driverLicenseNumber);

        if ($isPaid) {
            $driverFile->imposePaidPenalty(
                series: 'CS',
                number: 12345,
                occurredAt: $now,
                numberOfPoints: $numberOfPoints,
            );
        } else {
            $driverFile->imposeUnpaidPenalty(
                series: 'CS',
                number: 12345,
                occurredAt: $now,
                numberOfPoints: $numberOfPoints,
            );
        }

        $this->storeDriverFile->store($driverFile);
    }
}
