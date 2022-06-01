<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class AcceptEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $projectElementComponentVersion;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($projectElementComponentVersion)
    {
        $this->projectElementComponentVersion = $projectElementComponentVersion;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->projectElementComponentVersion->projectElementComponent->projectElement->project->name;
        $subject .= ' - '.auth()->user()->name;
        $subject .= '  zaakceptował/accepted '.$this->projectElementComponentVersion->projectElementComponent->projectElement->project->name;
        $subject .= '  > '.__($this->projectElementComponentVersion->projectElementComponent->name, [], 'pl');
        $subject .= ' > '.$this->projectElementComponentVersion->version;

        $date = Carbon::now()->format('Y.m.d H:m');

        return $this->subject($subject)
            ->markdown('emails.accept-email')
            ->with([
                'projectElementComponentVersion' => $this->projectElementComponentVersion,
                'date' => $date,
                'userName' => auth()->user()->name
            ]);
    }
}
