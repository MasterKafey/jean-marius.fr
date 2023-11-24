<?php

namespace App\Business;

use App\API\GoogleCalendar;
use App\Model\Calendar;
use App\Model\Event as ModelEvent;
use Google\Service\Calendar\Event as GoogleEvent;
use Google\Service\Calendar\EventDateTime;

class GoogleBusiness
{
    public function __construct(
        private readonly GoogleCalendar         $googleCalendar
    )
    {

    }

    public function getEvents(\DateTime $startingDateTime, \DateTime $endingDateTime, Calendar $calendar = null): array
    {
        if (null !== $calendar) {
            $calendars = [$calendar];
        } else {
            $calendars = $this->getCalendars();
        }

        $result = [];
        foreach ($calendars as $calendar) {
            $events = $this->googleCalendar->getEvents($calendar->getId(), [
                'timeMin' => $startingDateTime->format('c'),
                'timeMax' => $endingDateTime->format('c'),
            ]);
            foreach ($events as $event) {
                $result[] = $this->fromGoogleCalendarEventToModelEvent($event);
            }
        }

        return $result;
    }

    public function getCalendars(): array
    {
        $calendars = $this->googleCalendar->getCalendarIds();
        $results = [];
        foreach ($calendars as $calendar) {
            $results[] = (new Calendar())
                ->setId($calendar['id'])
                ->setDescription($calendar['description'])
                ->setSummary($calendar['summary']);
        }

        return $results;
    }

    public function fromGoogleCalendarEventToModelEvent(GoogleEvent $googleEvent): ModelEvent
    {
        $model = new ModelEvent();

        return $model
            ->setId($googleEvent->getId())
            ->setStartAt($this->fromGoogleDateTimeToPhpDateTime($googleEvent->getStart()))
            ->setEndAt($this->fromGoogleDateTimeToPhpDateTime($googleEvent->getEnd()))
            ->setTitle($googleEvent->getSummary());
    }

    /**
     * @throws \Exception
     */
    public function fromGoogleDateTimeToPhpDateTime(EventDateTime $eventDateTime): \DateTime
    {
        return (new \DateTime($eventDateTime->getDateTime()))->setTimezone(new \DateTimeZone($eventDateTime->getTimeZone()));
    }

    public function addCalendar(Calendar $calendar): Calendar
    {
        $calendarListEntry = $this->googleCalendar->addCalendarId($calendar->getId());

        return $calendar
            ->setId($calendarListEntry->getId())
            ->setSummary($calendarListEntry->getSummary())
            ->setDescription($calendarListEntry->getDescription());
    }
}