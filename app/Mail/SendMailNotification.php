<?php

namespace App\Mail;

use App\Models\ActivityLogs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendMailNotification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public $activityLog;
    public function __construct(ActivityLogs $activityLog)
    {
        $this->activityLog = $activityLog;
        $this->activityLog->load('user', 'msa', 'contract');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Send Mail Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.Activity-log',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
    public function build(){
        return $this->view('emails.Activity-log')
        ->with([
            'contract_ref_id' => $this->activityLog->contract ? $this->activityLog->contract->contract_ref_id : 'No Contract', 
            'msa_ref_id' => $this->activityLog->msa ? $this->activityLog->msa->msa_ref_id : 'No MSA', 
            'user_name' => $this->activityLog->user ? $this->activityLog->user->user_name : 'No user',
            'contract_id' => $this->activityLog->contract_id,
            'msa_id' => $this->activityLog->msa_id,
            'performed_by'=> $this->activityLog->performed_by,
            'action'=> $this->activityLog->action,
        ]);
    }
}
