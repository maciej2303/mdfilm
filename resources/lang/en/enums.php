<?php

use App\Enums\ClientStatus;
use App\Enums\ProjectComponentType;
use App\Enums\ProjectVersionStatus;
use App\Enums\UserLevel;
use App\Enums\UserStatus;

return [

    UserLevel::class => [
        UserLevel::ADMIN => 'Administrator_en',
        UserLevel::WORKER => 'Pracownik_en',
        UserLevel::CLIENT => 'Klient_en',
    ],

    UserStatus::class => [
        UserStatus::ACTIVE => 'Aktywny_en',
        UserStatus::INACTIVE => 'Nieaktywny_en',
    ],

    ClientStatus::class => [
        ClientStatus::ACTIVE => 'Aktywny_en',
        ClientStatus::INACTIVE => 'Nieaktywny_en',
    ],

    ProjectVersionStatus::class => [
        ProjectVersionStatus::PENDING => 'Oczekuje_en',
        ProjectVersionStatus::IN_PROGRESS => 'W trakcie_en',
        ProjectVersionStatus::TO_ACCEPT => 'Do akceptacji_en',
        ProjectVersionStatus::COMMENTS => 'Uwagi_en',
        ProjectVersionStatus::ACCEPTED => 'Zaakceptowany_en',
        ProjectVersionStatus::CLOSED => 'Zamknięty_en',
        ProjectVersionStatus::SUSPENDED => 'Zawieszony_en',
        ProjectVersionStatus::SUSPENDED => 'Anulowany_en',
    ],

    ProjectComponentType::class => [
        ProjectComponentType::BRIEF => "brief",
        ProjectComponentType::CREATIVE_CONCEPT => "creative_concept",
        ProjectComponentType::SCENARIO => "scenario",
        ProjectComponentType::STORYBOARD => "storyboard",
        ProjectComponentType::RECORDINGS => "recordings",
        ProjectComponentType::ANIMATION => "animation",
        ProjectComponentType::MOVIE => "movie"
    ],
];
