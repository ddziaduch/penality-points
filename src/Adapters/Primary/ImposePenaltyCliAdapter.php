<?php

declare(strict_types=1);

namespace ddziaduch\PenaltyPoints\Adapters\Primary;

use ddziaduch\PenaltyPoints\Application\Ports\Primary\PoliceOfficer;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// TODO: rename the adapter
#[AsCommand(name: 'app:impose-penalty', description: 'Allows to impose a new penalty to the driver')]
final class ImposePenaltyCliAdapter extends Command
{
    public function __construct(
        private readonly PoliceOfficer $imposePenalty,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('driverLicenseNumber', InputArgument::REQUIRED, 'The license number of the driver');
        $this->addArgument('isPaid', InputArgument::REQUIRED, 'Whether the penalty is already paid');
        $this->addArgument('numberOfPoints', InputArgument::REQUIRED, 'The number of points');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $numberOfPoints = $input->getArgument('numberOfPoints');

        if (!is_numeric($numberOfPoints) || !is_int($numberOfPoints + 0) ) {
            $output->writeln('The number of points needs to be an integer');

            return self::INVALID;
        }

        // TODO: validate the is paid and think about possible values

        $this->imposePenalty->imposePenalty(
            $input->getArgument('driverLicenseNumber'),
            $input->getArgument('isPaid'),
            (int) $numberOfPoints,
        );

        return self::SUCCESS;
    }
}
