<?php

namespace App\Services\Calendar;

class GoogleCalendarService
{
    public function authorizeUrl()
    {
        // return mocked auth url
        return 'https://accounts.google.com/o/oauth2/auth?mock=true';
    }

    public function createEvent($calendarId, $eventData)
    {
        // mock create event
        logger()->info('Creating calendar event', compact('calendarId','eventData'));
        return ['status' => 'created', 'event_id' => uniqid('evt_')];
    }
}
