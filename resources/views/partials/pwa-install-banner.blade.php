<!-- PWA INSTALL BANNER & MODAL (SMPN 9 PROBOLINGGO) -->
<div x-data="pwaInstaller()" x-init="init()" x-cloak>
    <!-- Floating Bottom Banner (Muncul otomatis di mobile/desktop jika belum dipasang) -->
    <div x-show="showBanner && !isStandalone" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-6 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-6 scale-95"
         class="fixed bottom-4 inset-x-3 sm:inset-x-auto sm:right-5 sm:max-w-md z-[9990] bg-slate-900/95 border border-slate-700/80 text-white rounded-3xl p-4 sm:p-5 shadow-2xl backdrop-blur-xl">
        
        <div class="flex items-start gap-3.5">
            <!-- App Icon Preview -->
            <img src="{{ asset('icons/icon-192x192.png') }}" 
                 alt="Ikon Aplikasi SMPN 9" 
                 class="w-12 h-12 rounded-2xl object-cover p-1 bg-white/10 border border-white/20 shadow-md shrink-0">
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2">
                    <span class="inline-block px-2 py-0.5 rounded-md bg-emerald-500/20 text-emerald-400 text-[10px] font-black uppercase tracking-wider border border-emerald-500/30">
                        Aplikasi Mobile
                    </span>
                    <span class="text-[11px] text-slate-400 font-medium">Bebas Kuota Besar</span>
                </div>
                <h4 class="text-sm font-black text-white mt-1 leading-snug">
                    Pasang Aplikasi SMPN 9 di HP
                </h4>
                <p class="text-xs text-slate-300/90 font-medium mt-0.5 leading-relaxed">
                    Akses jurnal lebih cepat, layar penuh tanpa bilah browser, dan hemat memori.
                </p>
                
                <!-- Action Buttons: Min 48px touch targets -->
                <div class="flex items-center gap-2.5 mt-3.5">
                    <button type="button" 
                            @click="installApp()" 
                            class="min-h-[48px] px-5 py-2.5 rounded-2xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 active:scale-[0.98] text-white text-xs font-black shadow-lg shadow-blue-900/40 flex items-center justify-center gap-2 transition cursor-pointer flex-1 sm:flex-initial">
                        <span>📲</span>
                        <span>Pasang Sekarang</span>
                    </button>
                    
                    <button type="button" 
                            @click="dismissBanner()" 
                            class="min-h-[48px] px-3.5 py-2.5 rounded-2xl bg-white/10 hover:bg-white/15 text-slate-300 hover:text-white text-xs font-bold transition cursor-pointer"
                            title="Tutup pemberitahuan">
                        Nanti Saja
                    </button>
                </div>
            </div>
            
            <!-- Dismiss Close Button -->
            <button type="button" 
                    @click="dismissBanner()" 
                    class="w-8 h-8 rounded-xl bg-white/5 hover:bg-white/10 text-slate-400 hover:text-white flex items-center justify-center text-sm transition shrink-0 cursor-pointer"
                    aria-label="Tutup banner">
                &times;
            </button>
        </div>
    </div>

    <!-- Modal Petunjuk Instalasi Khusus iOS / Safari (iPhone & iPad) -->
    <div x-show="showIosModal" 
         x-cloak
         class="fixed inset-0 z-[10000] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        
        <div @click.outside="showIosModal = false"
             class="bg-white text-slate-900 rounded-3xl p-6 sm:p-7 max-w-sm w-full shadow-2xl border border-slate-100 relative"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 scale-95 translate-y-4"
             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
             x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <!-- Header Modal iOS -->
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('icons/icon-192x192.png') }}" alt="Logo" class="w-10 h-10 rounded-2xl object-cover p-1 bg-blue-50 border border-blue-200">
                    <div>
                        <h3 class="text-sm font-black text-slate-900">Cara Pasang di iPhone / iPad</h3>
                        <p class="text-[11px] text-slate-500 font-medium">Melalui Browser Safari</p>
                    </div>
                </div>
                <button type="button" @click="showIosModal = false" class="w-8 h-8 rounded-xl bg-slate-100 text-slate-500 hover:text-slate-900 flex items-center justify-center text-lg font-bold">
                    &times;
                </button>
            </div>

            <!-- Langkah-langkah Pasang di iOS -->
            <div class="py-4 space-y-3.5 text-xs text-slate-700">
                <div class="flex items-start gap-3 p-2.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-900 font-black flex items-center justify-center text-xs shrink-0">1</span>
                    <p class="leading-relaxed">
                        Ketuk tombol <strong>Bagikan</strong> (<span class="font-bold">Share</span> <span class="text-blue-600 text-sm">⎋</span>) pada bilah navigasi bawah browser Safari.
                    </p>
                </div>
                <div class="flex items-start gap-3 p-2.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-900 font-black flex items-center justify-center text-xs shrink-0">2</span>
                    <p class="leading-relaxed">
                        Gulir menu ke bawah dan pilih opsi <strong>"Tambahkan ke Layar Utama"</strong> (<em>Add to Home Screen</em> ➕).
                    </p>
                </div>
                <div class="flex items-start gap-3 p-2.5 rounded-2xl bg-slate-50 border border-slate-100">
                    <span class="w-6 h-6 rounded-lg bg-blue-100 text-blue-900 font-black flex items-center justify-center text-xs shrink-0">3</span>
                    <p class="leading-relaxed">
                        Ketuk <strong>"Tambah"</strong> di pojok kanan atas. Ikon aplikasi akan langsung muncul di layar utama HP Anda.
                    </p>
                </div>
            </div>

            <button type="button" 
                    @click="showIosModal = false" 
                    class="w-full min-h-[48px] rounded-2xl bg-blue-900 hover:bg-blue-800 text-white font-black text-xs transition cursor-pointer shadow-md shadow-blue-900/20">
                Saya Mengerti
            </button>
        </div>
    </div>
</div>

<script>
    function pwaInstaller() {
        return {
            deferredPrompt: null,
            showBanner: false,
            showIosModal: false,
            isStandalone: false,
            isIos: false,

            init() {
                // Deteksi apakah sedang dibuka di mode Standalone PWA (Aplikasi Terpasang)
                this.isStandalone = window.matchMedia('(display-mode: standalone)').matches || 
                                    window.navigator.standalone === true ||
                                    document.referrer.includes('android-app://');

                // Deteksi iOS perangkat Apple
                this.isIos = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;

                // Registrasi Service Worker resmi
                if ('serviceWorker' in navigator) {
                    navigator.serviceWorker.register('/sw.js').then(function(reg) {
                        console.log('SMPN 9 Service Worker terdaftar:', reg.scope);
                    }).catch(function(err) {
                        console.warn('Registrasi Service Worker tertunda:', err);
                    });
                }

                // Cek status penyimpanan dismissal di localStorage (ingat 3 hari)
                const lastDismissed = localStorage.getItem('smpn9_pwa_dismissed');
                const now = new Date().getTime();
                const threeDays = 3 * 24 * 60 * 60 * 1000;

                if (!this.isStandalone) {
                    if (!lastDismissed || (now - parseInt(lastDismissed, 10)) > threeDays) {
                        // Di iOS Safari, tampilkan banner setelah beberapa detik
                        if (this.isIos) {
                            setTimeout(() => {
                                this.showBanner = true;
                            }, 2500);
                        }
                    }
                }

                // Listener untuk tombol manual di halaman (misal di halaman login atau pengaturan)
                window.addEventListener('trigger-pwa-install', () => {
                    this.installApp();
                });

                // Tangkap event instalasi browser Android / Chrome / Edge
                window.addEventListener('beforeinstallprompt', (e) => {
                    e.preventDefault();
                    this.deferredPrompt = e;

                    if (!this.isStandalone) {
                        if (!lastDismissed || (now - parseInt(lastDismissed, 10)) > threeDays) {
                            this.showBanner = true;
                        }
                    }
                });

                // Deteksi saat pengguna berhasil menginstal aplikasi
                window.addEventListener('appinstalled', () => {
                    this.showBanner = false;
                    this.deferredPrompt = null;
                    console.log('Aplikasi SMPN 9 berhasil dipasang!');
                });
            },

            installApp() {
                if (this.isIos) {
                    // Tampilkan panduan iOS Safari
                    this.showIosModal = true;
                    return;
                }

                if (this.deferredPrompt) {
                    this.deferredPrompt.prompt();
                    this.deferredPrompt.userChoice.then((choiceResult) => {
                        if (choiceResult.outcome === 'accepted') {
                            this.showBanner = false;
                        }
                        this.deferredPrompt = null;
                    });
                } else {
                    // Fallback jika browser belum memicu beforeinstallprompt secara otomatis
                    alert('Untuk mengunduh ke HP: Ketuk menu titik tiga (⋮) pada browser Chrome Anda, lalu pilih "Instal Aplikasi" atau "Tambahkan ke Layar Utama".');
                }
            },

            dismissBanner() {
                this.showBanner = false;
                localStorage.setItem('smpn9_pwa_dismissed', new Date().getTime().toString());
            }
        };
    }
</script>
