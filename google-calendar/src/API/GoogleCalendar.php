<?php

namespace App\API;

use Google\Service\Calendar\CalendarList;
use Google\Service\Calendar\CalendarListEntry;
use Google\Service\Calendar\Events;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GoogleCalendar
{
    private \Google_Client $client;

    private \Google_Service_Calendar $service;

    public function __construct(
        string $authConfigurationPath,
    )
    {
        $this->client = new \Google_Client();
        $this->client->setApplicationName('Google Calendar API PHP Integration');
        $this->client->setAuthConfig($authConfigurationPath);
        $this->client->useApplicationDefaultCredentials();
        $this->client->addScope(\Google_Service_Calendar::CALENDAR);
        $this->client->setAccessType('offline');
        $this->service = new \Google_Service_Calendar($this->client);
    }

    public function getEvents(string $calendarId, array $options): Events
    {
        $optionsResolver = new OptionsResolver();
        $optionsResolver->setDefaults([
            'maxResults' => 10,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => (new \DateTime())->format('c'),
            'timeMax' => (new \DateTime())->add(new \DateInterval('P7D'))->format('c'),
        ]);
        return $this->service->events->listEvents($calendarId, $optionsResolver->resolve($options));
    }

    public function getCalendarIds(): array
    {
        $calendarList = $this->service->calendarList->listCalendarList();

        $calendars = [];
        foreach ($calendarList->getItems() as $calendarListEntry) {
            $calendars[] = [
                'id' => $calendarListEntry->getId(),
                'description' => $calendarListEntry->getDescription(),
                'summary' => $calendarListEntry->getSummary(),
            ];
        }

        return $calendars;
    }

    public function addCalendarId(string $calendarId): CalendarListEntry
    {
        $calendar = new CalendarListEntry();
        $calendar->setId($calendarId);
        return $this->service->calendarList->insert($calendar);
    }
}