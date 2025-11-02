<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tiket - {{ $Tickets->ticket_name ?? 'Detail Tiket' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
            padding: 10px;
        }

        .ticket-container {
            /* Ubah Max Width agar lebih cocok untuk tampilan Portrait/Vertical */
            max-width: 450px;
            margin: 20px auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border-radius: 15px;
            overflow: hidden;
            background: #ffffff;
        }

        /* Styling Umum untuk Section */
        .ticket-section {
            padding: 25px;
        }

        /* 1. Event Info Section (Bagian Atas) */
        .event-info-section {
            background-color: #007bff;
            /* Biru terang */
            color: white;
            text-align: left;
        }

        .event-info-section img {
            width: 80px;
            height: 110px;
            object-fit: cover;
            border-radius: 8px;
        }

        .event-details-text {
            line-height: 1.6;
        }

        /* 2. Detail Pemegang (Bagian Tengah) */
        .ticket-detail-section {
            background-color: white;
            color: #343a40;
            border-bottom: 2px dashed #e9ecef;
            /* Garis pemisah */
        }

        .detail-item {
            margin-bottom: 10px;
        }

        .detail-item strong {
            display: inline-block;
            width: 120px;
            /* Lebar tetap untuk label */
            font-size: 0.9rem;
            color: #6c757d;
        }

        .detail-item span {
            font-size: 0.9rem;
            font-weight: 600;
            color: #343a40;
        }

        .detail-title {
            color: #007bff;
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 15px;
        }

        /* 3. QR Code Section (Bagian Bawah) */
        .qr-code-section {
            background-color: #343a40;
            /* Dark background */
            color: white;
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 35px 20px;
            /* Padding lebih besar untuk QR */
            border-bottom-left-radius: 15px;
            border-bottom-right-radius: 15px;
        }

        .qr-code-section img {
            width: 180px;
            height: 180px;
            padding: 10px;
            border-radius: 8px;
        }

        /* Tag Status Kehadiran */
        .presence-tag {
            position: absolute;
            top: 0;
            right: 0;
            /* Styling mirip gambar: bentuk pill di pojok kanan atas QR Section */
            padding: 5px 15px;
            border-top-right-radius: 15px;
            border-bottom-left-radius: 10px;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.75rem;
            z-index: 10;
        }

        .used {
            background-color: #dc3545;
            color: white;
        }

        .unused {
            background-color: #28a745;
            color: white;
        }

        /* Media Query: DESKTOP (JIKA DIPERLUKAN) */
        @media (min-width: 768px) {
            .ticket-container {
                max-width: 1000px;
                /* Kembali ke mode lebar di desktop */
                /* Hapus border-radius dari bagian, gunakan pada container utama */
            }

            .event-info-section {
                /* Untuk desktop, agar rata kiri dengan detail */
                border-bottom-left-radius: 0;
                border-top-right-radius: 0;
            }

            .ticket-detail-section {
                border-bottom: none;
                /* Hapus garis putus-putus vertikal */
                border-right: 2px dashed #e9ecef;
                /* Tambah garis vertikal sebagai pemisah */
            }

            .qr-code-section {
                border-top-right-radius: 15px;
                border-bottom-left-radius: 0;
            }
        }
    </style>
</head>

<body>

    @php
    // Logic PHP/Blade
    $start_time = \Carbon\Carbon::parse($Tickets->events_time_start);
    $end_time = \Carbon\Carbon::parse($Tickets->events_time_end);
    $formatted_date = $start_time->isoFormat('dddd, D MMMM Y');
    $formatted_time = $start_time->isoFormat('HH:mm') . ' - ' . $end_time->isoFormat('HH:mm') . ' WIB';

    $presence_status_text = $Tickets->presence == 1 ? 'SUDAH DIGUNAKAN' : 'BELUM DIGUNAKAN';
    $presence_class = $Tickets->presence == 1 ? 'used' : 'unused';

    $qr_data = $Tickets->id_tiket;
    $qr_url = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qr_data);
    @endphp

    <div class="ticket-container position-relative">
        <div class="row g-0">

            <div class="col-12 col-md-7 d-flex flex-column">

                <div class="ticket-section event-info-section">
                    <div class="d-flex align-items-start mb-4">
                        <img src="/storage/event_images/{{ $Tickets->event_image }}.webp" class="me-3" alt="Event Image">

                        <div class="flex-grow-1">
                            <h1 class="fs-4 fw-bold mb-0">{{ $Tickets->event_name }}</h1>
                            <p class="lead mb-0 fs-6">{{ $Tickets->ticket_name }}</p>
                        </div>
                    </div>

                    <div class="event-details-text">
                        <p class="mb-1">
                            <i class="bi bi-calendar-event me-2"></i>
                            <strong>Tanggal:</strong> {{ $formatted_date }}
                        </p>
                        <p class="mb-1">
                            <i class="bi bi-clock me-2"></i>
                            <strong>Waktu:</strong> {{ $formatted_time }}
                        </p>
                        <p class="mb-0">
                            <i class="bi bi-geo-alt me-2"></i>
                            <strong>Lokasi:</strong> {{ $Tickets->event_location }}
                        </p>
                    </div>
                </div>

                <div class="ticket-section ticket-detail-section flex-grow-1">
                    <p class="detail-title">
                        <i class="bi bi-person-badge me-2"></i> Detail Pemegang Tiket
                    </p>

                    <div class="detail-item">
                        <strong>Nama</strong>
                        <span>{{ $Tickets->ots_name }}</span>
                    </div>

                    <div class="detail-item">
                        <strong>Nomor Telepon</strong>
                        <span>{{ $Tickets->phone }}</span>
                    </div>

                    <div class="detail-item">
                        <strong>Tipe Tiket</strong>
                        <span>{{ $Tickets->ticket_name }}</span>
                    </div>

                    <p class="mt-4 small text-muted">
                        Tunjukkan kode QR ini untuk verifikasi saat masuk ke lokasi acara.
                    </p>

                    <div class="detail-item">
                        <span>{{ $Tickets->id_tiket }}</span>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-5 qr-code-section position-relative">
                <span class="presence-tag {{ $presence_class }}">
                    {{ $presence_status_text }}
                </span>
                <img src="{{ $qr_url }}" alt="QR Code Tiket" class="img-fluid bg-white rounded">
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>