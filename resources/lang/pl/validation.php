<?php

return array(
    'accepted'             => ':attribute musi zostać zaakceptowany.',
    'active_url'           => ':attribute jest nieprawidłowym adresem URL.',
    'after'                => ':attribute musi być datą późniejszą od :date.',
    'after_or_equal'       => ':attribute musi być datą nie wcześniejszą niż :date.',
    'alpha'                => ':attribute może zawierać jedynie litery.',
    'alpha_dash'           => ':attribute może zawierać jedynie litery, cyfry i myślniki.',
    'alpha_num'            => ':attribute może zawierać jedynie litery i cyfry.',
    'array'                => ':attribute musi być tablicą.',
    'before'               => ':attribute musi być datą wcześniejszą od :date.',
    'before_or_equal'      => ':attribute musi być datą nie późniejszą niż :date.',
    'between'              => [
        'numeric' => ':attribute musi zawierać się w granicach :min - :max.',
        'file'    => ':attribute musi zawierać się w granicach :min - :max kilobajtów.',
        'string'  => ':attribute musi zawierać się w granicach :min - :max znaków.',
        'array'   => ':attribute musi składać się z :min - :max elementów.',
    ],
    'boolean'              => ':attribute musi mieć wartość prawda albo fałsz',
    'confirmed'            => 'Potwierdzenie :attribute nie zgadza się.',
    'date'                 => ':attribute nie jest prawidłową datą.',
    'date_equals'          => ':attribute musi być datą równą :date.',
    'date_format'          => ':attribute nie jest w formacie :format.',
    'different'            => ':attribute oraz :other muszą się różnić.',
    'digits'               => ':attribute musi składać się z :digits cyfr.',
    'digits_between'       => ':attribute musi mieć od :min do :max cyfr.',
    'dimensions'           => ':attribute ma niepoprawne wymiary.',
    'distinct'             => ':attribute ma zduplikowane wartości.',
    'email'                => 'Format :attribute jest nieprawidłowy.',
    'exists'               => 'Pole :attribute jest wymagane.',
    'file'                 => ':attribute musi być plikiem.',
    'filled'               => 'Pole :attribute jest wymagane.',
    'gt'                   => [
        'numeric' => ':attribute musi być większy niż :value.',
        'file'    => ':attribute musi być większy niż :value kilobajtów.',
        'string'  => ':attribute musi być dłuższy niż :value znaków.',
        'array'   => ':attribute musi mieć więcej niż :value elementów.',
    ],
    'gte'                  => [
        'numeric' => ':attribute musi być większy lub równy :value.',
        'file'    => ':attribute musi być większy lub równy :value kilobajtów.',
        'string'  => ':attribute musi być dłuższy lub równy :value znaków.',
        'array'   => ':attribute musi mieć :value lub więcej elementów.',
    ],
    'image'                => ':attribute musi być obrazkiem.',
    'in'                   => 'Zaznaczony :attribute jest nieprawidłowy.',
    'in_array'             => ':attribute nie znajduje się w :other.',
    'integer'              => ':attribute musi być liczbą całkowitą.',
    'ip'                   => ':attribute musi być prawidłowym adresem IP.',
    'ipv4'                 => ':attribute musi być prawidłowym adresem IPv4.',
    'ipv6'                 => ':attribute musi być prawidłowym adresem IPv6.',
    'json'                 => ':attribute musi być poprawnym ciągiem znaków JSON.',
    'lt'                   => [
        'numeric' => ':attribute musi być mniejszy niż :value.',
        'file'    => ':attribute musi być mniejszy niż :value kilobajtów.',
        'string'  => ':attribute musi być krótszy niż :value znaków.',
        'array'   => ':attribute musi mieć mniej niż :value elementów.',
    ],
    'lte'                  => [
        'numeric' => ':attribute musi być mniejszy lub równy :value.',
        'file'    => ':attribute musi być mniejszy lub równy :value kilobajtów.',
        'string'  => ':attribute musi być krótszy lub równy :value znaków.',
        'array'   => ':attribute musi mieć :value lub mniej elementów.',
    ],
    'max'                  => [
        'numeric' => ':attribute nie może być większy niż :max.',
        'file'    => ':attribute nie może być większy niż :max kilobajtów.',
        'string'  => ':attribute nie może być dłuższy niż :max znaków.',
        'array'   => ':attribute nie może mieć więcej niż :max elementów.',
    ],
    'mimes'                => ':attribute musi być plikiem typu :values.',
    'mimetypes'            => ':attribute musi być plikiem typu :values.',
    'min'                  => [
        'numeric' => ':attribute musi być nie mniejszy od :min.',
        'file'    => ':attribute musi mieć przynajmniej :min kilobajtów.',
        'string'  => ':attribute musi mieć przynajmniej :min znaków.',
        'array'   => ':attribute musi mieć przynajmniej :min elementów.',
    ],
    'not_in'               => 'Zaznaczony :attribute jest nieprawidłowy.',
    'not_regex'            => 'Format :attribute jest nieprawidłowy.',
    'numeric'              => ':attribute musi być liczbą.',
    'present'              => 'Pole :attribute musi być obecne.',
    'regex'                => ':attribute jest nieprawidłowy.',
    'required'             => 'Pole :attribute jest wymagane.',
    'required_if'          => 'Pole :attribute jest wymagane gdy :other jest :value.',
    'required_unless'      => ':attribute jest wymagany jeżeli :other nie znajduje się w :values.',
    'required_with'        => 'Pole :attribute jest wymagane gdy :values jest obecny.',
    'required_with_all'    => 'Pole :attribute jest wymagane gdy :values jest obecny.',
    'required_without'     => 'Pole :attribute jest wymagane gdy :values nie jest obecny.',
    'required_without_all' => 'Pole :attribute jest wymagane gdy żadne z :values nie są obecne.',
    'same'                 => 'Pole :attribute i :other muszą się zgadzać.',
    'size'                 => [
        'numeric' => ':attribute musi mieć :size.',
        'file'    => ':attribute musi mieć :size kilobajtów.',
        'string'  => ':attribute musi mieć :size znaków.',
        'array'   => ':attribute musi zawierać :size elementów.',
    ],
    'starts_with'          => ':attribute musi się zaczynać jednym z wymienionych: :values',
    'string'               => ':attribute musi być ciągiem znaków.',
    'timezone'             => ':attribute musi być prawidłową strefą czasową.',
    'unique'               => 'Taki :attribute już występuje.',
    'uploaded'             => 'Nie udało się wgrać pliku :attribute.',
    'url'                  => 'Format :attribute jest nieprawidłowy.',

    /*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

    /*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

    'attributes' => array(
        'password' => 'hasło',
        'name' => 'nazwa',
        'name.pl' => 'nazwa',
        'name.en' => 'nazwa',
        'email' => 'e-mail',
        'emailTemporary' => 'e-mail',
        'phone_number' => "numer telefonu",
        'colour' => "kolor",
        'avatar' => "awatar",
        //Client
        'contact_emails.*' => 'e-mail kontaktowy',
        'billing_emails.*' => 'e-mail do rozliczeń',
        'address.*' => 'dane adresowe',
        'additional_informations' => 'informacje dodatkowe',
        'validation.distinct' => 'e-maile nie mogą być takie same',

        //Project
        'term' => 'termin',
        'comment' => 'komentarz',
        //Work time
        'date.*' => 'data',
        'task.*' => 'zadanie',
        'logged_hours.*' => 'godziny',
        'project' => 'projekt',
        'date_edit' => 'data',
        'task_edit' => 'zadanie',
        'logged_hours_edit' => 'godziny',

        //Project Element
        'components' => 'składowe elementu',

        //Project Version
        'version' => 'wersja',
        'title' => 'tytuł',
        'description' => 'opis',
        'storage_path' => 'film',
        'file' => 'załącznik',

        //to do list
        'content' => 'treść',

        //calendar
        'start_date' => 'dzień rozpoczęcia',
        'end_date' => 'dzień zakończenia',
        'start_time' => 'godzina rozpoczęcia',
        'end_time' => 'godzina rozpoczęcia',
        'event' => 'wydarzenie',

        'start_date_edit' => 'dzień rozpoczęcia',
        'end_date_edit' => 'dzień zakończenia',
        'start_time_edit' => 'godzina rozpoczęcia',
        'end_time_edit' => 'godzina rozpoczęcia',
        'event_edit' => 'wydarzenie'
    ),

);
