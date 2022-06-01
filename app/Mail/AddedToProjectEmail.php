<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AddedToProjectEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $project;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($project)
    {
        $this->project = $project;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Dodano Cię do projektu/You were added to project:' . $this->project->name)
            ->markdown('emails.added-to-project')
            ->with([
                'project' => $this->project,
            ]);
    }
}
