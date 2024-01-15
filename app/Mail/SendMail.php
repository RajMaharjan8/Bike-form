<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

// class SendMail extends Mailable
// {
//     use Queueable, SerializesModels;

//     public $mailData;
//     /**
//      * Create a new message instance.
//      */
//     public function __construct($mailData)
//     {
//         //
//         // $this->mailData = $mailData;
//         $this->mailData = (object) $mailData;
//     }

//     /**
//      * Get the message envelope.
//      */
//     public function envelope(): Envelope
//     {
//         return new Envelope(
//             subject: 'Verify Your Account',
//         );
//     }

//     /**
//      * Get the message content definition.
//      */
//     public function content(): Content
//     {
//         return new Content(
//             view: 'emails.display',
//             data: ['mailData' => $this->mailData],
//         );
//     }

//     /**
//      * Get the attachments for the message.
//      *
//      * @return array<int, \Illuminate\Mail\Mailables\Attachment>
//      */
//     public function attachments(): array
//     {
//         return [];
//     }
// }

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $mailData;

    /**
     * Create a new message instance.
     */
    public function __construct($mailData)
    {
        $this->mailData = $mailData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify Your Account',
        );
    }

    /**
     * Build the message.
     */
    public function build(): void
    {
        $this->view('emails.display')
            ->with(['mailData' => $this->mailData]);
    }
}
