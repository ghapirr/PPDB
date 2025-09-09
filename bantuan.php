<?php require_once 'includes/header.php'; ?>

<div class="text-center mb-5">
    <h1 class="display-6 fw-bold">Bantuan dan FAQ</h1>
    <p class="lead">Temukan jawaban atas pertanyaan yang sering diajukan mengenai proses PPDB.</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="accordion" id="faqAccordion">
            <!-- FAQ Item 1 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Bagaimana jika saya lupa nomor pendaftaran?
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Anda dapat mencoba login ke dashboard pendaftar menggunakan NIK dan password Anda. Di dalam dashboard, nomor pendaftaran Anda akan ditampilkan. Jika Anda juga lupa password, silakan hubungi panitia melalui kontak yang tersedia.
                    </div>
                </div>
            </div>
            <!-- FAQ Item 2 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingTwo">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                        Bagaimana cara memperbaiki data yang sudah terkirim?
                    </button>
                </h2>
                <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Selama periode pendaftaran masih dibuka dan data Anda belum diverifikasi secara permanen oleh panitia, Anda masih dapat login dan mengubah beberapa isian formulir. Jika data sudah terkunci, silakan segera hubungi panitia.
                    </div>
                </div>
            </div>
            <!-- FAQ Item 3 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingThree">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                        Dokumen apa saja yang wajib diunggah?
                    </button>
                </h2>
                <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Dokumen yang diunggah berbeda-beda tergantung jalur pendaftaran yang Anda pilih. Secara umum meliputi: Pas Foto, Scan Kartu Keluarga, Scan Rapor/Ijazah. Untuk jalur afirmasi biasanya memerlukan Scan Kartu Indonesia Pintar (KIP) atau Surat Keterangan Tidak Mampu (SKTM). Pastikan Anda membaca persyaratan di halaman pendaftaran.
                    </div>
                </div>
            </div>
             <!-- FAQ Item 4 -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingFour">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                        Kapan hasil seleksi akan diumumkan?
                    </button>
                </h2>
                <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                    <div class="accordion-body">
                        Hasil seleksi akan diumumkan sesuai dengan jadwal yang tertera di halaman <strong><a href="jadwal.php">Jadwal</a></strong>. Anda dapat memeriksanya di halaman <strong><a href="pengumuman.php">Pengumuman</a></strong> menggunakan nomor pendaftaran Anda.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="bi bi-headset"></i> Hubungi Panitia</h5>
            </div>
            <div class="card-body">
                <p>Jika Anda memiliki pertanyaan lain, jangan ragu untuk menghubungi kami melalui:</p>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <i class="bi bi-envelope-fill me-2"></i>
                        <a href="mailto:<?= htmlspecialchars($pengaturan['email_panitia']) ?>" class="text-dark"><?= htmlspecialchars($pengaturan['email_panitia']) ?></a>
                    </li>
                    <li>
                        <i class="bi bi-whatsapp me-2"></i>
                        <a href="https://wa.me/<?= htmlspecialchars($pengaturan['wa_panitia']) ?>" target="_blank" class="text-dark"><?= htmlspecialchars($pengaturan['wa_panitia']) ?></a> (WhatsApp)
                    </li>
                </ul>
                <hr>
                <p class="mb-1"><strong>Alamat Sekolah:</strong></p>
                <p><?= htmlspecialchars($pengaturan['alamat_sekolah']) ?></p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
