<div>

    @if ($holiday)
    <div class="alert alert-success">
        <small class="fw-bold">Hari ini adalah hari libur.</small>
    </div>
    @else

    {{-- jika tidak menggunakan qrcode (button) dan karyawan saat ini tidak menekan tombol izin --}}
    @if (!$attendance->data->is_using_qrcode && !$data['is_there_permission'])

    {{-- jika belum absen dan absen masuk sudah dimulai --}}
    @if ($attendance->data->is_start && !$data['is_has_enter_today'])
    <button class="btn btn-primary px-3 py-2 btn-sm fw-bold d-block w-100 mb-2" wire:click="sendEnterPresence"
        wire:loading.attr="disabled" wire:target="sendEnterPresence">Masuk</button>
    <a href="{{ route('home.permission', $attendance->id) }}"
        class="btn btn-info px-3 py-2 btn-sm fw-bold d-block w-100">Izin</a>
    @endif

    @if ($data['is_has_enter_today'])
    <div class="alert alert-success">
        <small class="d-block fw-bold text-success">Anda sudah berhasil mengirim absensi masuk.</small>
    </div>
    @endif

    {{-- Jika absen pulang sudah dimulai, karyawan sudah absen masuk, dan belum absen pulang --}}
    @if ($attendance->data->is_end && $data['is_has_enter_today'] && $data['is_not_out_yet'])

    {{-- Pilih Aktivitas --}}
    <div class="mb-2">
        <label for="activity" class="fw-bold">Pilih Aktivitas Hari Ini:</label>
        <select wire:model="selectedActivity" id="activity" class="form-control">
            <option value="">-- Pilih Aktivitas --</option>
            <option value="Meeting">Meeting</option>
            <option value="Coding">Coding</option>
            <option value="Testing">Testing</option>
            <option value="Dokumentasi">Dokumentasi</option>
        </select>
    </div>

    {{-- Tombol Pulang, hanya aktif jika aktivitas sudah dipilih --}}
    <button class="btn btn-primary px-3 py-2 btn-sm fw-bold d-block w-100"
        wire:click="sendOutPresence"
        wire:loading.attr="disabled"
        wire:target="sendOutPresence"
        @if (!$selectedActivity) disabled @endif> {{-- Tombol tidak aktif jika belum memilih aktivitas --}}
        Pulang
    </button>

    @endif



    {{-- sudah absen masuk dan absen pulang --}}
    @if ($data['is_has_enter_today'] && !$data['is_not_out_yet'])
    <div class="alert alert-success">
        <small class="d-block fw-bold text-success">Anda sudah melakukan absen masuk dan absen pulang.</small>
    </div>
    @endif

    {{-- jika sudah absen masuk dan belum saatnya absen pulang --}}
    @if ($data['is_has_enter_today'] && !$attendance->data->is_end)
    <div class="alert alert-danger">
        <small class="fw-bold">Belum saatnya melakukan absensi pulang.</small>
    </div>
    @endif
    @endif

    @if($data['is_there_permission'] && !$data['is_permission_accepted'])
    <div class="alert alert-info">
        <small class="fw-bold">Permintaan izin sedang diproses (atau masih belum di terima).</small>
    </div>
    @endif

    @if($data['is_there_permission'] && $data['is_permission_accepted'])
    <div class="alert alert-success">
        <small class="fw-bold">Permintaan izin sudah diterima.</small>
    </div>
    @endif

    @endif

</div>