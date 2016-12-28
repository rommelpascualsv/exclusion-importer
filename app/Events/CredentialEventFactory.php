<?php

namespace App\Events;

class CredentialEventFactory
{
    const EVENTTYPE_FILE_CREATE = 'credential.file.create';
    const EVENTTYPE_FILE_SAVE   = 'credential.file.save';
    const EVENTTYPE_SEED        = 'credential.seed';

    const EVENT_TYPES = [
        self::EVENTTYPE_FILE_CREATE,
        self::EVENTTYPE_FILE_SAVE,
        self::EVENTTYPE_SEED
    ];

    const DEFAULT_FILE_CREATE_SUCCESS_DESCRIPTION = 'Credential file created successfully';
    const DEFAULT_FILE_SAVE_SUCCESS_DESCRIPTION   = 'Credential file saved successfully';
    const DEFAULT_SEED_SUCCESS_DESCRIPTION        = 'Credentials seeded successfully';

    public static function newFileCreationFailed()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_FILE_CREATE)
                 ->setStatus(Event::EVENTSTATUS_FAIL);

        return $instance;
    }

    public static function newFileCreationSucceeded()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_FILE_CREATE)
                 ->setStatus(Event::EVENTSTATUS_SUCCESS)
                 ->setDescription(json_encode(['message' => self::DEFAULT_FILE_CREATE_SUCCESS_DESCRIPTION]));

        return $instance;
    }

    public static function newFileSaveFailed()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_FILE_SAVE)
                 ->setStatus(Event::EVENTSTATUS_FAIL);

        return $instance;
    }

    public static function newFileSaveSucceeded()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_FILE_SAVE)
                 ->setStatus(Event::EVENTSTATUS_SUCCESS)
                 ->setDescription(json_encode(['message' => self::DEFAULT_FILE_SAVE_SUCCESS_DESCRIPTION]));

        return $instance;
    }

    public static function newCredentialSeedingFailed()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_SEED)
                 ->setStatus(Event::EVENTSTATUS_FAIL);

        return $instance;
    }

    public static function newCredentialSeedingSucceeded()
    {
        $instance = new Event();
        $instance->setEventType(self::EVENTTYPE_SEED)
                 ->setStatus(Event::EVENTSTATUS_SUCCESS)
                 ->setDescription(json_encode(['message' => self::DEFAULT_SEED_SUCCESS_DESCRIPTION]));

        return $instance;
    }
}
