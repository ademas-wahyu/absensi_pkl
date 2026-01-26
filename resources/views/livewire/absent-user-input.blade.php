<div>
    <flux:modal name="input-absent-user" class="md:w-100">
        <div class="space-y-6" x-data="{ scanning: false }">

            <!-- SCANNER SECTION -->
            <!-- SCANNER SECTION -->
            <div>
                <div class="mb-4">
                    <flux:heading size="lg">Scan QR Code</flux:heading>
                    <p class="text-sm text-neutral-500 mt-1">Arahkan kamera ke QR Code untuk absen</p>
                </div>

                <div id="reader" class="rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700 min-h-[250px] bg-neutral-100 dark:bg-neutral-800">
                </div>
                
                <div class="mt-4 flex justify-center gap-2">
                     <flux:button wire:ignore class="w-full" variant="primary" onclick="startScanner()" id="btn-start-scan">
                        Mulai Scan
                    </flux:button>
                     <flux:button wire:ignore class="hidden w-full" variant="danger" onclick="stopScanner()" id="btn-stop-scan">
                        Stop Kamera
                    </flux:button>
                </div>
            </div>

            {{--
            <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script> --}}
            <script>
                let html5QrcodeScanner = null;

                window.startScanner = function () {
                    if (html5QrcodeScanner === null) {
                        if (window.Html5Qrcode) {
                            html5QrcodeScanner = new window.Html5Qrcode("reader");
                        } else {
                            console.error("Html5Qrcode library not found");
                            alert("Library scanner belum dimuat. Silahkan refresh halaman.");
                            return;
                        }
                    }

                    const config = { fps: 10, qrbox: { width: 250, height: 250 } };

                    // Prefer back camera
                    html5QrcodeScanner.start({ facingMode: "environment" }, config, (decodedText, decodedResult) => {
                        // Success
                        console.log(`QR Code Scanned: ${decodedText}`);
                        html5QrcodeScanner.stop().then(() => {
                            // Call Livewire component method
                            @this.call('verifyQrCode', decodedText);
                            html5QrcodeScanner = null;
                            toggleButtons(false);
                        }).catch(err => console.error(err));
                    }, (errorMessage) => {
                        // Scanning...
                    }).catch(err => {
                        console.error('Error starting scanner', err);
                        alert('Gagal membuka kamera: ' + err);
                        toggleButtons(false);
                    });
                    
                    toggleButtons(true);
                }

                window.stopScanner = function () {
                    if (html5QrcodeScanner) {
                        html5QrcodeScanner.stop().then(() => {
                            html5QrcodeScanner = null;
                            toggleButtons(false);
                        }).catch(err => console.log(err));
                    }
                }
                
                function toggleButtons(isScanning) {
                    const btnStart = document.getElementById('btn-start-scan');
                    const btnStop = document.getElementById('btn-stop-scan');
                    if(isScanning) {
                        btnStart.classList.add('hidden');
                        btnStop.classList.remove('hidden');
                    } else {
                        btnStart.classList.remove('hidden');
                        btnStop.classList.add('hidden');
                    }
                }

                // Listen for event from Livewire to close scanner/modal if needed or reset
                window.addEventListener('close-scanner', () => {
                    stopScanner();
                });
            </script>
        </div>
    </flux:modal>
</div>