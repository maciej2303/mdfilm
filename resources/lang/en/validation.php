<?php

return array(
    'accepted'             => ':attribute must be accepted.',
    'active_url'           => ':attribute is an invalid URL.',
    'after'                => ':attribute must be later than :date.',
    'after_or_equal'       => ':attribute must be later or equal to :date.',
    'alpha'                => ':attribute can only contain letters.',
    'alpha_dash'           => ':attribute can only contain letters, numbers and hyphens.',
    'alpha_num'            => ':attribute can only contain letters and numbers.',
    'array'                => ':attribute must be array.',
    'before'               => ':attribute must be before :date.',
    'before_or_equal'      => ':attribute must be before or equal to :date.',
    'between'              => [
        'numeric' => ':attribute must be within the limits :min - :max.',
        'file'    => ':attribute must be within the limits :min - :max kilobytes.',
        'string'  => ':attribute must be within the limits :min - :max signs.',
        'array'   => ':attribute must consist of :min - :max elements.',
    ],
    'boolean'              => ':attribute must be true or false.',
    'confirmed'            => 'Confirmation :attribute is not correct.',
    'date'                 => ':attribute is not correct date.',
    'date_equals'          => ':attribute must be equal to :date.',
    'date_format'          => ':attribute is not in format :format.',
    'different'            => ':attribute and :other must be different.',
    'digits'               => ':attribute must consist of :digits digits.',
    'digits_between'       => ':attribute must have from :min to :max digits.',
    'dimensions'           => ':attribute has incorrect sizes.',
    'distinct'             => ':attribute has duplicated values.',
    'email'                => 'Format :attribute is incorrect.',
    'exists'               => 'Field :attribute is required.',
    'file'                 => ':attribute must be a file.',
    'filled'               => 'Field :attribute is required.',
    'gt'                   => [
        'numeric' => ':attribute must be higher than :value.',
        'file'    => ':attribute must be bigger than :value kilobytes.',
        'string'  => ':attribute must be longer than :value signs.',
        'array'   => ':attribute must have more than :value elements.',
    ],
    'gte'                  => [
        'numeric' => ':attribute must be bigger or equal :value.',
        'file'    => ':attribute must be bigger or equal :value kilobytes.',
        'string'  => ':attribute must be bigger or equal :value signs.',
        'array'   => ':attribute must have :value or more elements.',
    ],
    'image'                => ':attribute has to be picture.',
    'in'                   => 'Selected :attribute is incorrect.',
    'in_array'             => ':attribute is not in :other.',
    'integer'              => ':attribute must be an integer.',
    'ip'                   => ':attribute must be correct IP address',
    'ipv4'                 => ':attribute must be correct IPv4 address.',
    'ipv6'                 => ':attribute must be correct IPv6 address.',
    'json'                 => ':attribute must be in correct JSON format.',
    'lt'                   => [
        'numeric' => ':attribute must be lower than :value.',
        'file'    => ':attribute must be lower than :value kilobytes.',
        'string'  => ':attribute must be lower than :value signs.',
        'array'   => ':attribute must be lower than :value elements.',
    ],
    'lte'                  => [
        'numeric' => ':attribute must be lower or equal :value.',
        'file'    => ':attribute must be lower or equal :value kilobytes,',
        'string'  => ':attribute must be lower or equal :value signs.',
        'array'   => ':attribute must have :value or less elements.',
    ],
    'max'                  => [
        'numeric' => ':attribute cannot be higher than :max.',
        'file'    => ':attribute cannot be bigger than :max kilobytes.',
        'string'  => ':attribute cannot be longer than :max signs.',
        'array'   => ':attribute cannot have more than :max elements.',
    ],
    'mimes'                => ':attribute must be file with type :values.',
    'mimetypes'            => ':attribute must be file with type :values.',
    'min'                  => [
        'numeric' => ':attribute must be lower than od :min.',
        'file'    => ':attribute must be lower than :min kilobytes.',
        'string'  => ':attribute must have at least :min signs.',
        'array'   => ':attribute must have at least :min elements.',
    ],
    'not_in'               => 'Selected :attribute is incorrect.',
    'not_regex'            => 'Format :attribute is incorrect.',
    'numeric'              => ':attribute must be number.',
    'present'              => 'Field :attribute must be present.',
    'regex'                => ':attribute is incorrect.',
    'required'             => 'Field :attribute is required.',
    'required_if'          => 'Field :attribute is required when :other is :value.',
    'required_unless'      => ':attribute is required if :other not in :values.',
    'required_with'        => 'Field :attribute is required when :values is present.',
    'required_with_all'    => 'Field :attribute is required when :values is present.',
    'required_without'     => 'Field :attribute is required when :values is not present.',
    'required_without_all' => 'Field :attribute is required when non of :values are present.',
    'same'                 => 'Field :attribute and :other must be the same.',
    'size'                 => [
        'numeric' => ':attribute must have :size.',
        'file'    => ':attribute must have :size kilobytes.',
        'string'  => ':attribute must have :size signs.',
        'array'   => ':attribute must have :size elements.',
    ],
    'starts_with'          => ':attribute must start with one of: :values',
    'string'               => ':attribute must be string.',
    'timezone'             => ':attribute must be correct timezone.',
    'unique'               => ':attribute is not unique.',
    'uploaded'             => 'File upload failed :attribute.',
    'url'                  => 'Format :attribute is incorrect.',

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
        'password' => 'password',
        'name' => 'name',
        'name.pl' => 'Name PL',
        'name.en' => 'Name EN',
        'email' => 'e-mail',
        'emailTemporary' => 'e-mail',
        'phone_number' => "phone number",
        'colour' => "color",
        'avatar' => "profile picture",
        //Client
        'contact_emails.*' => 'contact e-mail',
        'billing_emails.*' => 'invoicing e-mail',
        'address.*' => 'address',
        'additional_informations' => 'additional informations',
        'validation.distinct' => 'e-maile cannot be the same',

        //Project
        'term' => 'deadline',
        'comment' => 'comment',
        //Work time
        'date.*' => 'date',
        'task.*' => 'task',
        'logged_hours.*' => 'hours',
        'project' => 'project',
        'date_edit' => 'date',
        'task_edit' => 'task',
        'logged_hours_edit' => 'hours',

        //Project Element
        'components' => 'Element components',

        //Project Version
        'version' => 'version',
        'title' => 'title',
        'description' => 'description',
        'storage_path' => 'movie',
        'file' => 'attachment',

        //to do list
        'content' => 'content',

        //calendar
        'start_date' => 'start day',
        'end_date' => 'end day',
        'start_time' => 'start time',
        'end_time' => 'end time',
        'event' => 'event',

        'start_date_edit' => 'start day',
        'end_date_edit' => 'end day',
        'start_time_edit' => 'start time',
        'end_time_edit' => 'end time',
        'event_edit' => 'event'
    ),

);
