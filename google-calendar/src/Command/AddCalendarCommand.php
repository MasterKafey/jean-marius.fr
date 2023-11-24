<?php

namespace App\Command;

use App\Business\GoogleBusiness;
use App\Model\Calendar;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:calendar:add', description: 'Add new google calendar')]
class AddCalendarCommand extends Command
{
    public function __construct(
        private readonly GoogleBusiness $googleBusiness
    )
    {
        parent::__construct();
    }

    public function configure(): void
    {
        $this->addArgument('calendar-id', InputArgument::REQUIRED, 'Google calendar\'s id');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $calendar = new Calendar();
        $calendar
            ->setId($input->getArgument('calendar-id'))
        ;

        $this->googleBusiness->addCalendar($calendar);

        return Command::SUCCESS;
    }
}