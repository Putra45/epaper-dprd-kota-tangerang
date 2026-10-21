<footer id="kontak" class="bg-primary border-t-[6px] border-accent text-sm">
    <div class="max-w-7xl mx-auto px-6 md:px-12 py-14 lg:py-16">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-12">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <img src="{{ asset('images/logo-dprd.png') }}" alt="Logo DPRD Kota Tangerang" class="h-10 w-auto object-contain bg-white/10 p-1 rounded-md">
                    <h2 class="font-serif text-xl md:text-2xl font-bold text-white tracking-tight">ePaper DPRD</h2>
                </div>
                <p class="text-footer-text leading-relaxed">
                    Majalah digital resmi Sekretariat DPRD Kota Tangerang. Baca gratis, kapan saja, di perangkat apa saja.
                </p>
            </div>

            <div>
                <h3 class="text-accent text-xs font-semibold uppercase tracking-widest mb-4">Menu</h3>
                <nav class="flex flex-col gap-2.5 text-footer-text" aria-label="Menu footer">
                    <a href="{{ url('/') }}" class="hover:text-white transition-colors">Beranda</a>
                    <a href="{{ url('/majalah') }}" class="hover:text-white transition-colors">Majalah</a>
                    <a href="{{ url('/arsip') }}" class="hover:text-white transition-colors">Arsip</a>
                </nav>
            </div>

            <div>
                <h3 class="text-accent text-xs font-semibold uppercase tracking-widest mb-4">Kontak</h3>
                <address class="not-italic flex flex-col gap-2 leading-relaxed text-footer-text">
                    <p>Gedung DPRD Kota Tangerang, Jl. Satria Sudirman No. 1, Sukaasih, Tangerang</p>
                    <p>Telepon: <a href="tel:02155769051" class="hover:text-white transition-colors">(021) 5576-9051</a></p>
                    <p>Email: <a href="mailto:sekretariat@dprd.tangerangkota.go.id" class="hover:text-white transition-colors break-all">sekretariat@dprd.tangerangkota.go.id</a></p>
                </address>
            </div>
        </div>

        <div class="border-t border-white/10 mt-12 pt-6 text-footer-text/80 text-xs md:text-sm text-center md:text-left">
            <p>&copy; {{ date('Y') }} Sekretariat DPRD Kota Tangerang. Hak cipta dilindungi.</p>
        </div>
    </div>
</footer>
