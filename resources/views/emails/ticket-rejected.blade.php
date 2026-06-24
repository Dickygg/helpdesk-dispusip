<h2>Tiket Anda Ditolak</h2>

<p>Halo {{ $ticket->user->name }},</p>

<p>
    Mohon Maaf Tiket Anda Ditolak Oleh Admin.
</p>

<p>
    Nomor Tiket:
    <b>{{ $ticket->ticket_code }}</b>
</p>

<p>
    Judul:
    <b>{{ $ticket->title }}</b>
</p>

<p>
    Alasan Penolakan:
    {{$ticket->reason_rejected}}
</p>

<p>
    Terima kasih.
</p>