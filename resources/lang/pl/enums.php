<?php

use App\Enums\ClientStatus;
use App\Enums\ProjectComponentType;
use App\Enums\ProjectVersionStatus;
use App\Enums\UserLevel;
use App\Enums\UserStatus;

return [

    UserLevel::class => [
        UserLevel::ADMIN => 'Administrator',
        UserLevel::WORKER => 'Pracownik',
        UserLevel::CLIENT => 'Klient',
    ],

    UserStatus::class => [
        UserStatus::ACTIVE => 'Aktywny',
        UserStatus::INACTIVE => 'Nieaktywny',
    ],

    ClientStatus::class => [
        ClientStatus::ACTIVE => 'Aktywny',
        ClientStatus::INACTIVE => 'Nieaktywny',
    ],

    ProjectVersionStatus::class => [
        ProjectVersionStatus::PENDING => 'Oczekuje',
        ProjectVersionStatus::IN_PROGRESS => 'W trakcie',
        ProjectVersionStatus::TO_ACCEPT => 'Do akceptacji',
        ProjectVersionStatus::COMMENTS => 'Uwagi',
        ProjectVersionStatus::ACCEPTED => 'Zaakceptowany',
        ProjectVersionStatus::CLOSED => 'Zamknięty',
        ProjectVersionStatus::SUSPENDED => 'Zawieszony',
        ProjectVersionStatus::SUSPENDED => 'Anulowany',
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
