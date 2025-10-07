<?php

namespace App\Mail;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ApplicationMail extends Mailable
{
    use Queueable, SerializesModels;


    /**
     * Create a new message instance.
     */
    public function __construct(
        public array $applicationData,
        public ?array $user = null,
        public array $files = []
    ) {}

    /**
     * Attach generated PDF and uploaded files.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        $atts = [];

        $app = (object) $this->applicationData;
        $app->date_of_birth = Carbon::parse($app->date_of_birth);
        $app->created_at    = Carbon::parse($app->created_at);

        $pdf = app('dompdf.wrapper');
        $pdf->loadView('pdf.application', [
            'application' => $app,
            'user'        => (object) ($this->user ?? []),
            'files'       => $this->files,
        ]);

        $atts[] = Attachment::fromData(fn() => $pdf->output(), 'application-details.pdf')
            ->withMime('application/pdf');


        foreach ($this->files as $file) {
            $path = $file['path'] ?? null;
            if ($path && Storage::disk('public')->exists($path)) {
                $atts[] = Attachment::fromStorageDisk('public', $path)
                    ->as($file['original_name'] ?? basename($path))
                    ->withMime($file['mime_type'] ?? null);
            }
        }

        return $atts;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Application Mail',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $app = (object) $this->applicationData;
        $app->date_of_birth = Carbon::parse($app->date_of_birth);
        $app->created_at    = Carbon::parse($app->created_at);

        return new Content(
            view: 'emails.application-email',
            with: [
                'application' => $app,
                'user'        => (object) ($this->user ?? []),
            ],
        );
    }
}
