<h2>Tiket Selesai Diproses</h2>

<p>Halo {{ $ticket->user->name }},</p>

<p>
    Tiket Anda telah diselesaikan oleh petugas teknis.
</p>

<p>
    Nomor Tiket:
    <b>{{ $ticket->kode_tiket }}</b>
</p>

<p>
    Judul:
    <b>{{ $ticket->judul }}</b>
</p>

<p>
    Silakan login ke sistem Helpdesk untuk melakukan pengecekan hasil pekerjaan dan memberikan feedback.
</p>

<p>
    Terima kasih.
</p>