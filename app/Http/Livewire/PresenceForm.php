<?php

namespace App\Http\Livewire;

use App\Models\Attendance;
use App\Models\Presence;
use Livewire\Component;

class PresenceForm extends Component
{
    public Attendance $attendance;
    public $holiday;
    public $data;
    public $activity; // Menambahkan properti activity untuk simpan aktivitas
    public $selectedActivity;

    public function mount(Attendance $attendance)
    {
        $this->attendance = $attendance;
        $this->selectedActivity = '';
    }

    // NOTED: setiap method send presence agar lebih aman seharusnya menggunakan if statement seperti diviewnya

    public function sendEnterPresence()
    {
        if ($this->attendance->data->is_start && !$this->attendance->data->is_using_qrcode) { // sama (harus) dengan view
            Presence::create([
                "user_id" => auth()->user()->id,
                "attendance_id" => $this->attendance->id,
                "presence_date" => now()->toDateString(),
                "presence_enter_time" => now()->toTimeString(),
                "presence_out_time" => null,
                "activity" => $this->selectedActivity // // Menyimpan aktivitas harian
            ]);

            // untuk refresh if statement
            $this->data['is_has_enter_today'] = true;
            $this->data['is_not_out_yet'] = true;

            return $this->dispatchBrowserEvent('showToast', [
                'success' => true, 
                'message' => "Kehadiran atas nama '" . auth()->user()->name . "' berhasil dikirim dengan aktivitas: " . $this->selectedActivity
            ]);
        }
    }

    public function sendOutPresence()
    {
        if (!$this->attendance->data->is_end && $this->attendance->data->is_using_qrcode) 
            return false;
    
        $presence = Presence::query()
            ->where('user_id', auth()->user()->id)
            ->where('attendance_id', $this->attendance->id)
            ->where('presence_date', now()->toDateString())
            ->where('presence_out_time', null)
            ->first();
    
        if (!$presence) 
            return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => "Terjadi masalah pada saat melakukan absensi."]);
    
        // Pastikan aktivitas sudah dipilih sebelum bisa absen pulang
        if (!$this->selectedActivity) {
            return $this->dispatchBrowserEvent('showToast', ['success' => false, 'message' => "Silakan pilih aktivitas sebelum absen pulang."]);
        }
    
        // Update absensi dengan aktivitas yang dipilih
        $presence->update([
            'presence_out_time' => now()->toTimeString(),
            'activity' => $this->selectedActivity
        ]);
    
        // Perbarui status di tampilan
        $this->data['is_not_out_yet'] = false;
    
        return $this->dispatchBrowserEvent('showToast', [
            'success' => true,
            'message' => "Atas nama '" . auth()->user()->name . "' berhasil melakukan absensi pulang dengan aktivitas: " . $this->selectedActivity
        ]);
    }
    
    

    public function render()
    {
        return view('livewire.presence-form');
    }
}
