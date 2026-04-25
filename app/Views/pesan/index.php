<?= $this->extend('layouts/main'); ?>
<?= $this->section('content'); ?>

<style>
    /* Reset & Base Layout */
    body {
        background-color: #000;
        color: #fff;
    }

    .main-wrapper {
        display: flex;
        height: calc(100vh - 120px);
        background: #000;
        border: 1px solid #333;
        margin-top: 20px;
        border-radius: 8px;
        overflow: hidden;
    }

    .chat-area {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background: #000;
        width: 100%;
    }

    .chat-header {
        padding: 15px 20px;
        border-bottom: 1px solid #333;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #000;
    }

    .chat-container {
        flex-grow: 1;
        overflow-y: auto;
        padding: 20px;
        display: flex;
        flex-direction: column;
    }

    .bubble {
        max-width: 60%;
        padding: 12px 16px;
        border-radius: 18px;
        margin-bottom: 8px;
        font-size: 14px;
        line-height: 1.4;
        position: relative;
    }

    .bubble-right {
        align-self: flex-end;
        background: #3797f0;
        color: white;
        border-bottom-right-radius: 4px;
    }

    .bubble-left {
        align-self: flex-start;
        background: #262626;
        color: white;
        border-bottom-left-radius: 4px;
    }

    .time {
        font-size: 10px;
        opacity: 0.5;
        margin-top: 5px;
        display: block;
    }

    .chat-img {
        max-width: 100%;
        border-radius: 12px;
        margin: 5px 0;
    }

    .chat-footer {
        padding: 20px;
        border-top: 1px solid #333;
        background: #000;
    }

    .input-wrapper {
        display: flex;
        align-items: center;
        background: #000;
        border: 1px solid #333;
        border-radius: 25px;
        padding: 5px 15px;
    }

    .input-wrapper input {
        background: transparent;
        border: none;
        color: white;
        flex-grow: 1;
        padding: 10px;
    }

    .input-wrapper input:focus {
        outline: none;
    }

    .icon-btn {
        color: #fff;
        font-size: 20px;
        margin: 0 8px;
        cursor: pointer;
        background: none;
        border: none;
    }

    /* Perbaikan Tampilan Audio agar tidak gepeng */
    /* Gunakan nama class baru untuk menghindari cache browser */
    /* Pastikan nama class ini sama dengan yang ada di HTML nanti */
    .audio-wrapper-fixed {
        display: flex !important;
        align-items: center !important;
        background-color: rgba(255, 255, 255, 0.2) !important;
        padding: 8px 15px !important;
        border-radius: 20px !important;
        width: 180px !important;
        /* Paksa lebar agar tidak gepeng */
        min-height: 40px !important;
        cursor: pointer !important;
        gap: 10px !important;
        margin: 5px 0 !important;
        z-index: 999 !important;
    }

    .audio-wrapper-fixed i {
        font-size: 24px !important;
        color: #fff !important;
        pointer-events: none;
        /* Supaya klik tembus ke div utama */
    }

    /* Garis-garis dekorasi audio */
    .audio-visualizer {
        display: flex;
        gap: 3px;
        align-items: center;
        flex-grow: 1;
    }

    .audio-visualizer span {
        width: 2px;
        height: 10px;
        background: #fff;
        opacity: 0.5;
    }

    @keyframes blink {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0;
        }

        100% {
            opacity: 1;
        }
    }

    .blink {
        animation: blink 1s infinite;
    }
</style>

<div class="container-fluid">
    <div class="main-wrapper">
        <div class="chat-area">
            <div class="chat-header">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white fw-bold shadow-sm me-2"
                        style="width: 35px; height: 35px; flex-shrink: 0; font-size: 14px; overflow: hidden;">
                        <?php if (!empty($detail_lawan['foto'])): ?>
                            <img src="<?= base_url('uploads/users/' . $detail_lawan['foto']) ?>" style="width:100%; height:100%; object-fit:cover;">
                        <?php else: ?>
                            <?= strtoupper(substr($detail_lawan['nama'] ?? 'U', 0, 1)) ?>
                        <?php endif; ?>
                    </div>
                    <div>
                        <h6 class="mb-0 fw-bold text-white"><?= esc($detail_lawan['nama'] ?? 'User') ?></h6>
                        <small class="text-success" style="font-size: 10px;">• Online</small>
                    </div>
                </div>
                <div class="header-icons d-flex align-items-center">
                    <a href="<?= base_url('/') ?>" class="text-white ms-2" style="font-size: 24px; line-height: 1;">
                        <i class="bi bi-x"></i>
                    </a>
                </div>
            </div>

            <div class="chat-container" id="chat-body">
                <?php foreach ($semua_pesan as $p) : ?>
                    <?php
                    $myId = session()->get('id_user');
                    $myRole = session()->get('role');
                    $isRight = ($myRole == 'admin') ? ($p['role_pengirim'] == 'admin') : ($p['id_pengirim'] == $myId);

                    // DETEKSI FILE: Apakah ini audio?
                    $isAudio = (isset($p['file_lampiran']) && strpos($p['file_lampiran'], '.webm') !== false);
                    // DETEKSI FILE: Apakah ini gambar?
                    $isImage = (isset($p['file_lampiran']) && (strpos($p['file_lampiran'], '.png') !== false || strpos($p['file_lampiran'], '.jpg') !== false || strpos($p['file_lampiran'], '.jpeg') !== false));
                    ?>

                    <div class="bubble <?= $isRight ? 'bubble-right' : 'bubble-left' ?>" style="min-width: 150px !important; margin-bottom: 15px !important; display: block !important;">

                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                            <small style="font-size: 10px; font-weight: bold; color: <?= $isRight ? '#d1e7ff' : '#0095f6' ?>;">
                                <?= ($isRight) ? esc($p['nama_pengirim']) : esc($p['nama_pengirim']) ?>
                            </small>
                            <?php if ($p['id_pengirim'] == $myId || $myRole == 'admin') : ?>
                                <i class="bi bi-trash" style="cursor:pointer; font-size: 12px; opacity: 0.5;" onclick="hapusPesan(<?= $p['id_pesan'] ?>)"></i>
                            <?php endif; ?>
                        </div>

                        <?php if ($isAudio) : ?>
                            <div class="audio-box" onclick="playAudioDirect(this)" style="background: rgba(255,255,255,0.2) !important; width: 220px !important; height: 50px !important; display: flex !important; align-items: center !important; padding: 0 15px !important; border-radius: 25px !important; gap: 10px !important; cursor: pointer !important; margin: 10px 0 !important;">
                                <div style="background: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                                    <i class="bi bi-play-fill" style="color: #3797f0; font-size: 20px;"></i>
                                </div>
                                <div style="flex-grow: 1; display: flex; gap: 2px; align-items: center;">
                                    <div style="height: 15px; width: 2px; background: white;"></div>
                                    <div style="height: 10px; width: 2px; background: white;"></div>
                                    <div style="height: 20px; width: 2px; background: white;"></div>
                                    <div style="height: 12px; width: 2px; background: white;"></div>
                                    <div style="height: 18px; width: 2px; background: white;"></div>
                                </div>
                                <audio class="main-player">
                                    <source src="<?= base_url('pesan/gambar/' . $p['file_lampiran']) ?>" type="audio/webm">
                                </audio>
                            </div>
                        <?php endif; ?>

                        <?php if ($isImage) : ?>
                            <img src="<?= base_url('pesan/gambar/' . $p['file_lampiran']) ?>" style="width: 100%; border-radius: 10px; margin-bottom: 5px; cursor: pointer;" onclick="window.open(this.src)">
                        <?php endif; ?>

                        <?php if (!empty($p['isi_pesan'])) : ?>
                            <div style="word-wrap: break-word; font-size: 14px;"><?= esc($p['isi_pesan']) ?></div>
                        <?php endif; ?>

                        <small style="display: block; text-align: right; font-size: 9px; opacity: 0.6; margin-top: 5px;">
                            <?= date('H:i', strtotime($p['created_at'])) ?>
                        </small>
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="preview-container" class="d-none px-4 py-2 border-top border-secondary bg-dark">
                <div class="position-relative d-inline-block">
                    <img id="img-preview" src="" class="rounded" style="max-height: 100px; display: none; border: 1px solid #444;">
                    <audio id="audio-preview" controls class="d-none"></audio>
                    <button type="button" class="btn btn-danger btn-sm rounded-circle position-absolute top-0 start-100 translate-middle" onclick="batalKirim()">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </div>

            <div class="chat-footer">
                <div class="input-wrapper">
                    <button class="icon-btn" data-bs-toggle="modal" data-bs-target="#modalKamera" onclick="startCamera()"><i class="bi bi-camera"></i></button>
                    <label class="icon-btn mb-0" for="file-upload"><i class="bi bi-image"></i></label>
                    <input type="file" id="file-upload" class="d-none" accept="image/*" onchange="tampilkanPreview(this)">
                    <button class="icon-btn" id="btn-mic" onclick="toggleRecording()"><i class="bi bi-mic" id="icon-mic"></i></button>
                    <input type="text" id="input-chat" placeholder="Tulis pesan..." autocomplete="off">
                    <button class="icon-btn text-primary" onclick="eksekusiKirim()"><b>Kirim</b></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalKamera" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark border-secondary">
            <div class="modal-body p-0">
                <video id="video-feed" autoplay playsinline class="w-100 rounded"></video>
                <canvas id="canvas-capture" class="d-none"></canvas>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-outline-light rounded-pill" data-bs-dismiss="modal" onclick="stopCamera()">Batal</button>
                <button type="button" class="btn btn-primary rounded-pill" onclick="capturePhoto()">Ambil Foto</button>
            </div>
        </div>
    </div>
</div>

<script>
    const ID_LAWAN = "<?= $id_lawan ?? '' ?>";
    let streamPointer = null;
    let mediaRecorder;
    let audioChunks = [];
    let isRecording = false;
    let fileSiapKirim = null;

    $(document).ready(function() {
        if ($('#chat-body').length) {
            $('#chat-body').scrollTop($('#chat-body')[0].scrollHeight);
        }
        bacaPesan();
        setInterval(cekPesanBaru, 3000);
    });

    function bacaPesan() {
        if (ID_LAWAN == "") return;
        $.post("<?= base_url('pesan/baca') ?>/" + ID_LAWAN, {
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        }, function(res) {
            $('#badge-notif-chat').text('0').addClass('d-none').hide();
        });
    }

    function cekPesanBaru() {
        if (ID_LAWAN == "") return;
        $.get("<?= base_url('pesan/cek_baru') ?>/" + ID_LAWAN, function(res) {
            if (res.ada_baru && $('#input-chat').val().length === 0) {
                location.reload();
            }
        });
    }

    function playAudioDirect(el) {
        const audio = el.querySelector('.main-player');
        const icon = el.querySelector('i');

        if (audio.paused) {
            audio.play().catch(e => {
                console.error(e);
                alert("File audio tidak ditemukan di server.");
            });
            icon.classList.replace('bi-play-fill', 'bi-pause-fill');
        } else {
            audio.pause();
            icon.classList.replace('bi-pause-fill', 'bi-play-fill');
        }

        audio.onended = () => icon.classList.replace('bi-pause-fill', 'bi-play-fill');
    }

    function tampilkanPreview(input) {
        if (input.files && input.files[0]) {
            fileSiapKirim = input.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-container').removeClass('d-none');
                $('#img-preview').attr('src', e.target.result).show();
                $('#audio-preview').addClass('d-none');
            }
            reader.readAsDataURL(fileSiapKirim);
        }
    }

    function batalKirim() {
        fileSiapKirim = null;
        $('#file-upload').val('');
        $('#preview-container').addClass('d-none');
        $('#img-preview').attr('src', '').hide();
    }

    function eksekusiKirim() {
        const teks = $('#input-chat').val();
        if (teks.trim() == "" && !fileSiapKirim) return;
        let fd = new FormData();
        fd.append('id_penerima', ID_LAWAN);
        fd.append('pesan', teks);
        fd.append('<?= csrf_token() ?>', '<?= csrf_hash() ?>');
        if (fileSiapKirim) {
            if (fileSiapKirim.type.includes('audio')) fd.append('audio', fileSiapKirim);
            else fd.append('gambar', fileSiapKirim);
        }
        $.ajax({
            url: "<?= base_url('pesan/kirim') ?>",
            method: "POST",
            data: fd,
            contentType: false,
            processData: false,
            success: function(res) {
                if (res.status == 'success') location.reload();
            }
        });
    }

    function hapusPesan(id) {
        if (confirm('Hapus pesan?')) {
            $.post("<?= base_url('pesan/hapus') ?>/" + id, {
                '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
            }, function(res) {
                if (res.status == 'success') location.reload();
            });
        }
    }

    async function toggleRecording() {
        if (!isRecording) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({
                    audio: true
                });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];
                mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
                mediaRecorder.onstop = () => {
                    const audioBlob = new Blob(audioChunks, {
                        type: 'audio/webm'
                    });
                    fileSiapKirim = new File([audioBlob], "vn.webm", {
                        type: "audio/webm"
                    });
                    $('#preview-container').removeClass('d-none');
                    $('#audio-preview').attr('src', URL.createObjectURL(audioBlob)).removeClass('d-none');
                    $('#img-preview').hide();
                };
                mediaRecorder.start();
                isRecording = true;
                $('#icon-mic').addClass('text-success blink');
            } catch (err) {
                alert("Mic error: " + err);
            }
        } else {
            mediaRecorder.stop();
            isRecording = false;
            $('#icon-mic').removeClass('text-success blink');
        }
    }

    async function startCamera() {
        try {
            streamPointer = await navigator.mediaDevices.getUserMedia({
                video: true
            });
            document.getElementById('video-feed').srcObject = streamPointer;
        } catch (err) {
            alert("Kamera error");
        }
    }

    function stopCamera() {
        if (streamPointer) streamPointer.getTracks().forEach(t => t.stop());
    }

    function capturePhoto() {
        const video = document.getElementById('video-feed');
        const canvas = document.getElementById('canvas-capture');
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        canvas.getContext('2d').drawImage(video, 0, 0);
        canvas.toBlob(blob => {
            fileSiapKirim = new File([blob], "snap.png", {
                type: "image/png"
            });
            $('#preview-container').removeClass('d-none');
            $('#img-preview').attr('src', URL.createObjectURL(blob)).show();
            $('#modalKamera').modal('hide');
            stopCamera();
        }, 'image/png');
    }
</script>

<?= $this->endSection(); ?>