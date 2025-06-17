<?php

namespace App\Livewire\Settings;

use App\Models\Setting\Setting;
use Livewire\Component;
use Livewire\WithFileUploads;

class ApplicationSettings extends Component
{
    use WithFileUploads;

    public $store_name;
    public $store_address;
    public $store_phone;
    public $store_email;
    public $receipt_footer_note;
    public $store_logo;

    public $newLogo;

    public function mount()
    {
        $settings = Setting::all()->keyBy('key');
        $this->store_name = $settings->get('store_name')->value ?? '';
        $this->store_address = $settings->get('store_address')->value ?? '';
        $this->store_phone = $settings->get('store_phone')->value ?? '';
        $this->store_email = $settings->get('store_email')->value ?? '';
        $this->receipt_footer_note = $settings->get('receipt_footer_note')->value ?? 'Terima kasih telah berbelanja!';
        $this->store_logo = $settings->get('store_logo')->value ?? null;
    }

    public function save()
    {
        $this->validate([
            'store_name' => 'required|string|max:255',
            'store_address' => 'nullable|string',
            'store_phone' => 'nullable|string|max:20',
            'store_email' => 'nullable|email|max:255',
            'receipt_footer_note' => 'nullable|string',
            'newLogo' => 'nullable|image|max:1024',
        ]);

        Setting::updateOrCreate(['key' => 'store_name'], ['value' => $this->store_name]);
        Setting::updateOrCreate(['key' => 'store_address'], ['value' => $this->store_address]);
        Setting::updateOrCreate(['key' => 'store_phone'], ['value' => $this->store_phone]);
        Setting::updateOrCreate(['key' => 'store_email'], ['value' => $this->store_email]);
        Setting::updateOrCreate(['key' => 'receipt_footer_note'], ['value' => $this->receipt_footer_note]);

        if ($this->newLogo) {
            $path = $this->newLogo->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'store_logo'], ['value' => $path]);
            $this->store_logo = $path;
            $this->newLogo = null;
        }

        session()->flash('message', 'Pengaturan berhasil disimpan.');
    }

    public function render()
    {
        return view('livewire.settings.application-settings')->layout('layouts.app');
    }
}
