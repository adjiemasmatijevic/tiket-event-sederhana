@extends('templates.dashboard')

@section('title', 'Gate Check - Scan Tiket')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12 col-md-8 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <strong class="card-title">Scan QR Code Tiket</strong>
                </div>
                <div class="card-body text-center">
                    <div id="qr-reader" style="width: 100%; max-width: 500px; margin: auto;"></div>
                    <div id="qr-reader-results" class="mt-3"></div>
                    <div id="scan-result" class="alert mt-3" style="display: none;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const resultContainer = document.getElementById('scan-result');
        const readerElement = document.getElementById('qr-reader');

        function showMessage(message, isSuccess) {
            resultContainer.textContent = message;
            resultContainer.style.display = 'block';
            resultContainer.className = 'alert mt-3 ' + (isSuccess ? 'alert-success' : 'alert-danger');

            setTimeout(() => {
                resultContainer.style.display = 'none';
            }, 5000);
        }

        function onScanSuccess(decodedText, decodedResult) {
            console.log(`Code matched = ${decodedText}`, decodedResult);
            html5QrcodeScanner.clear();

            fetch("{{ route('gate-check.scan') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        cart_id: decodedText
                    })
                })
                .then(response => response.json().then(data => ({
                    status: response.status,
                    body: data
                })))
                .then(({
                    status,
                    body
                }) => {
                    showMessage(body.message, body.status === 'success');
                    setTimeout(() => renderScanner(), 1000);
                })
                .catch(error => {
                    console.error('Error:', error);
                    showMessage('Terjadi kesalahan saat memproses tiket.', false);
                    setTimeout(() => renderScanner(), 1000);
                });
        }

        function onScanFailure(error) {

        }

        let html5QrcodeScanner;

        function renderScanner() {
            if (document.getElementById('qr-reader')) {
                if (html5QrcodeScanner) {
                    html5QrcodeScanner.clear().catch(err => console.error("Error clearing scanner:", err));
                }

                html5QrcodeScanner = new Html5QrcodeScanner(
                    "qr-reader", {
                        fps: 10,
                        qrbox: {
                            width: 250,
                            height: 250
                        }
                    },
                    false
                );
                html5QrcodeScanner.render(onScanSuccess, onScanFailure);
                readerElement.style.display = 'block';
            } else {
                console.error("Elemen 'qr-reader' tidak ditemukan.");
            }
        }

        renderScanner();
    });
</script>
@endsection