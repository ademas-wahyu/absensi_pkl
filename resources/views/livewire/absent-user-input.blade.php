<div>
    <flux:modal name="input-absent-user" class="md:w-[500px]">
        <div class="space-y-6" x-data="{
                activeTab: 'qr',
                cameraActive: false,
                capturedImage: null,
                latitude: null,
                longitude: null,
                locationStatus: null,
                locationGranted: false,
                isSubmitting: false,
                html5QrcodeScanner: null,
                cameraStream: null,

                init() {
                    this.requestLocation();
                    window.addEventListener('close-scanner', () => this.stopQrScanner());
                    window.addEventListener('close-camera', () => this.stopCamera());
                },

                requestLocation() {
                    if ('geolocation' in navigator) {
                        navigator.geolocation.getCurrentPosition(
                            (position) => {
                                this.latitude = position.coords.latitude;
                                this.longitude = position.coords.longitude;
                                this.locationGranted = true;
                                this.locationStatus = '📍 Lokasi: ' + this.latitude.toFixed(6) + ', ' + this.longitude.toFixed(6);
                            },
                            (error) => {
                                console.error('Geolocation error:', error);
                                this.locationStatus = '⚠️ Lokasi tidak tersedia';
                                this.locationGranted = false;
                            },
                            { enableHighAccuracy: true, timeout: 10000 }
                        );
                    } else {
                        this.locationStatus = '⚠️ Browser tidak mendukung GPS';
                    }
                },

                startQrScanner() {
                    if (this.html5QrcodeScanner === null) {
                        if (window.Html5Qrcode) {
                            this.html5QrcodeScanner = new window.Html5Qrcode('reader');
                        } else {
                            alert('Library scanner belum dimuat. Silahkan refresh halaman.');
                            return;
                        }
                    }

                    const config = { fps: 10, qrbox: { width: 250, height: 250 } };
                    const self = this;

                    this.html5QrcodeScanner.start(
                        { facingMode: 'environment' },
                        config,
                        (decodedText) => {
                            self.html5QrcodeScanner.stop().then(() => {
                                $wire.call('verifyQrCode', decodedText);
                                self.html5QrcodeScanner = null;
                                self.toggleQrButtons(false);
                            });
                        },
                        () => {}
                    ).catch(err => {
                        console.error('Error starting scanner', err);
                        alert('Gagal membuka kamera: ' + err);
                        self.toggleQrButtons(false);
                    });

                    this.toggleQrButtons(true);
                },

                stopQrScanner() {
                    if (this.html5QrcodeScanner) {
                        const self = this;
                        this.html5QrcodeScanner.stop().then(() => {
                            self.html5QrcodeScanner = null;
                            self.toggleQrButtons(false);
                        }).catch(err => console.log(err));
                    }
                },

                toggleQrButtons(isScanning) {
                    const btnStart = document.getElementById('btn-start-scan');
                    const btnStop = document.getElementById('btn-stop-scan');
                    if (btnStart && btnStop) {
                        if (isScanning) {
                            btnStart.classList.add('hidden');
                            btnStop.classList.remove('hidden');
                        } else {
                            btnStart.classList.remove('hidden');
                            btnStop.classList.add('hidden');
                        }
                    }
                },

                async startCamera() {
                    try {
                        this.requestLocation();

                        this.cameraStream = await navigator.mediaDevices.getUserMedia({
                            video: {
                                facingMode: 'user',
                                width: { ideal: 1280 },
                                height: { ideal: 720 }
                            }
                        });

                        this.$refs.selfieVideo.srcObject = this.cameraStream;
                        this.cameraActive = true;
                    } catch (err) {
                        console.error('Camera error:', err);
                        alert('Gagal membuka kamera. Pastikan izin kamera diaktifkan.');
                    }
                },

                stopCamera() {
                    if (this.cameraStream) {
                        this.cameraStream.getTracks().forEach(track => track.stop());
                        this.cameraStream = null;
                    }
                    this.cameraActive = false;
                },

                capturePhoto() {
                    const video = this.$refs.selfieVideo;
                    const canvas = this.$refs.selfieCanvas;
                    const ctx = canvas.getContext('2d');

                    canvas.width = video.videoWidth;
                    canvas.height = video.videoHeight;
                    ctx.drawImage(video, 0, 0);

                    this.capturedImage = canvas.toDataURL('image/jpeg', 0.8);
                    this.stopCamera();
                },

                retakePhoto() {
                    this.capturedImage = null;
                    this.startCamera();
                },

                async submitSelfie() {
                    if (!this.capturedImage) {
                        alert('Silakan ambil foto terlebih dahulu.');
                        return;
                    }

                    if (!this.locationGranted) {
                        alert('Lokasi diperlukan untuk absensi. Silakan izinkan akses lokasi.');
                        this.requestLocation();
                        return;
                    }

                    this.isSubmitting = true;

                    try {
                        await $wire.call('submitWithSelfie', this.capturedImage, this.latitude, this.longitude);
                        this.capturedImage = null;
                        this.isSubmitting = false;
                    } catch (err) {
                        console.error('Submit error:', err);
                        alert('Gagal mengirim absensi. Silakan coba lagi.');
                        this.isSubmitting = false;
                    }
                },

                setTab(tab) {
                    this.activeTab = tab;
                    $wire.call('setTab', tab);
                    if (tab !== 'qr') {
                        this.stopQrScanner();
                    }
                }
            }">

            <!-- TAB SELECTOR -->
            <div class="flex border-b border-neutral-200 dark:border-neutral-700">
                <button type="button" @click="setTab('qr')"
                    :class="activeTab === 'qr' ? 'border-b-2 border-[#3526B3] text-[#3526B3] dark:border-[#8615D9] dark:text-[#8615D9]' : 'text-neutral-500'"
                    class="flex-1 py-3 px-4 text-center font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="inline-block mr-2">
                        <rect width="5" height="5" x="3" y="3" rx="1" />
                        <rect width="5" height="5" x="16" y="3" rx="1" />
                        <rect width="5" height="5" x="3" y="16" rx="1" />
                        <path d="M21 16h-3a2 2 0 0 0-2 2v3" />
                        <path d="M21 21v.01" />
                        <path d="M12 7v3a2 2 0 0 1-2 2H7" />
                        <path d="M3 12h.01" />
                        <path d="M12 3h.01" />
                        <path d="M12 16v.01" />
                        <path d="M16 12h1" />
                        <path d="M21 12v.01" />
                        <path d="M12 21v-1" />
                    </svg>
                    QR Code
                </button>
                <button type="button" @click="setTab('selfie')"
                    :class="activeTab === 'selfie' ? 'border-b-2 border-[#3526B3] text-[#3526B3] dark:border-[#8615D9] dark:text-[#8615D9]' : 'text-neutral-500'"
                    class="flex-1 py-3 px-4 text-center font-medium transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="inline-block mr-2">
                        <path
                            d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
                        <circle cx="12" cy="13" r="3" />
                    </svg>
                    Selfie
                </button>
            </div>

            <!-- QR CODE TAB -->
            <div x-show="activeTab === 'qr'" x-transition>
                <div class="mb-4">
                    <flux:heading size="lg">Scan QR Code</flux:heading>
                    <p class="text-sm text-neutral-500 mt-1">Arahkan kamera ke QR Code untuk absen</p>
                </div>

                <div id="reader"
                    class="rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700 min-h-[250px] bg-neutral-100 dark:bg-neutral-800">
                </div>

                <div class="mt-4 flex justify-center gap-2">
                    <flux:button wire:ignore class="w-full" variant="primary" @click="startQrScanner()"
                        id="btn-start-scan">
                        Mulai Scan
                    </flux:button>
                    <flux:button wire:ignore class="hidden w-full" variant="danger" @click="stopQrScanner()"
                        id="btn-stop-scan">
                        Stop Kamera
                    </flux:button>
                </div>
            </div>

            <!-- SELFIE TAB -->
            <div x-show="activeTab === 'selfie'" x-transition>
                <div class="mb-4">
                    <flux:heading size="lg">Foto Selfie</flux:heading>
                    <p class="text-sm text-neutral-500 mt-1">Ambil foto selfie untuk absen. Lokasi akan otomatis
                        direkam.</p>
                </div>

                <!-- Camera Preview -->
                <div
                    class="relative rounded-lg overflow-hidden border border-neutral-200 dark:border-neutral-700 bg-neutral-900">
                    <video x-ref="selfieVideo" autoplay playsinline muted class="w-full h-[300px] object-cover"
                        x-show="!capturedImage"></video>
                    <canvas x-ref="selfieCanvas" class="hidden"></canvas>
                    <img x-show="capturedImage" :src="capturedImage" class="w-full h-[300px] object-cover"
                        alt="Captured selfie">

                    <!-- Location indicator -->
                    <div class="absolute bottom-2 left-2 right-2 bg-black/60 text-white text-xs p-2 rounded"
                        x-show="locationStatus">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="inline-block mr-1">
                            <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z" />
                            <circle cx="12" cy="10" r="3" />
                        </svg>
                        <span x-text="locationStatus"></span>
                    </div>
                </div>

                <!-- Controls -->
                <div class="mt-4 space-y-3">
                    <!-- Start Camera -->
                    <flux:button x-show="!cameraActive && !capturedImage" @click="startCamera()" class="w-full"
                        variant="primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="mr-2">
                            <path
                                d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
                            <circle cx="12" cy="13" r="3" />
                        </svg>
                        Buka Kamera
                    </flux:button>

                    <!-- Capture Button -->
                    <flux:button x-show="cameraActive && !capturedImage" @click="capturePhoto()"
                        class="w-full bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white!">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="mr-2">
                            <circle cx="12" cy="12" r="10" />
                        </svg>
                        Ambil Foto
                    </flux:button>

                    <!-- After Capture -->
                    <div x-show="capturedImage" class="flex gap-2">
                        <flux:button @click="retakePhoto()" variant="ghost" class="flex-1">
                            Ulangi
                        </flux:button>
                        <flux:button @click="submitSelfie()"
                            class="flex-1 bg-linear-to-r from-[#3526B3] to-[#8615D9] text-white!"
                            x-bind:disabled="isSubmitting">
                            <span x-show="!isSubmitting">Kirim Absensi</span>
                            <span x-show="isSubmitting">Mengirim...</span>
                        </flux:button>
                    </div>
                </div>

                <!-- Location Permission Notice -->
                <div x-show="!locationGranted"
                    class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                    <p class="text-sm text-yellow-800 dark:text-yellow-200">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="inline-block mr-1">
                            <path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z" />
                            <path d="M12 9v4" />
                            <path d="M12 17h.01" />
                        </svg>
                        Izinkan akses lokasi untuk melanjutkan absensi.
                    </p>
                </div>
            </div>

        </div>
    </flux:modal>
</div>