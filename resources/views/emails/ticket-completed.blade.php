<h2>Tiket Selesai Diproses</h2>

<p>Halo {{ $ticket->user->name }},</p>

<p>
    Tiket Anda telah diselesaikan oleh petugas teknis.
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
    Silakan login ke sistem Helpdesk untuk melakukan pengecekan hasil pekerjaan dan memberikan feedback.
</p>
<p style="color:red;">
    Tiket Akan Dikonfirmasi Otomatis Jika Tidak Ada Feedback Dalam 3 Hari!
</p>

<p>
    Terima kasih.
</p>