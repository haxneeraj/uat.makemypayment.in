{{--
  Reusable Alpine.js file uploader partial for KYC document uploads.

  Required variables:
    $wireModel   - Livewire model name (e.g. 'pan_front')
    $xRef        - Unique x-ref name for the hidden input (e.g. 'pan_front_input')
    $label       - Display label text
    $required    - bool: whether asterisk/required indicator is shown
    $existingUrl - URL of already uploaded file (or null/empty)
    $errorKey    - Livewire @error key (e.g. 'pan_front')

    Optional variables:
        $fileSize    - max upload size in MB (defaults to 5)
--}}

@php
        $maxFileSizeMb = (isset($fileSize) && is_numeric($fileSize) && (int) $fileSize > 0) ? (int) $fileSize : 5;
        $maxFileSizeBytes = $maxFileSizeMb * 1024 * 1024;
@endphp

<div
    x-data="{
        isDragging: false,
        fileName: '',
        fileType: '',
        previewUrl: '',
        sizeError: '',
        isUploading: false,
        uploadProgress: 0,
        uploadDone: false,
        uploadError: false,
        maxFileSizeMb: {{ $maxFileSizeMb }},
        maxFileSizeBytes: {{ $maxFileSizeBytes }},

        handleFile(file) {
            if (!file) return;
            if (file.size > this.maxFileSizeBytes) {
                this.sizeError = 'File must be under ' + this.maxFileSizeMb + 'MB';
                this.fileName = '';
                this.previewUrl = '';
                return;
            }
            this.sizeError = '';
            this.uploadDone = false;
            this.uploadError = false;
            this.fileName  = file.name;
            this.fileType  = file.type;
            this.previewUrl = file.type.startsWith('image/') ? URL.createObjectURL(file) : '';
        }
    }"
    class="mb-2"
    x-on:livewire-upload-start="isUploading = true; uploadProgress = 0; uploadDone = false; uploadError = false;"
    x-on:livewire-upload-progress="uploadProgress = $event.detail.progress;"
    x-on:livewire-upload-finish="isUploading = false; uploadProgress = 100; uploadDone = true;"
    x-on:livewire-upload-error="isUploading = false; uploadProgress = 0; uploadError = true;"
>
    <label class="block font-semibold mb-1 text-sm">
        {{ $label }}
        @if($required)
            <small class="text-red-600">*</small>
        @endif
        <span class="text-gray-400 font-normal">&nbsp;(PDF / JPG / PNG · max {{ $maxFileSizeMb }}MB)</span>
    </label>

    {{-- Drop zone --}}
    <div
        class="relative w-full border-2 border-dashed rounded-xl p-4 cursor-pointer transition-all duration-200"
        :class="isDragging
            ? 'border-blue-400 bg-blue-50'
            : (fileName
                ? 'border-green-400 bg-green-50'
                : 'border-gray-300 bg-gray-50 hover:border-blue-400 hover:bg-blue-50')"
        @dragover.prevent="isDragging = true"
        @dragleave.prevent="isDragging = false"
        @drop.prevent="isDragging = false; handleFile($event.dataTransfer.files[0]); $refs.{{ $xRef }}.files = $event.dataTransfer.files;"
        @click="$refs.{{ $xRef }}.click()"
    >
        {{-- Hidden file input --}}
        <input
            wire:model="{{ $wireModel }}"
            type="file"
            accept=".pdf,.jpg,.jpeg,.png"
            class="hidden"
            x-ref="{{ $xRef }}"
            @change="
                let f = $event.target.files[0];
                if (!f) return;
                if (f.size > maxFileSizeBytes) {
                    sizeError = 'File must be under ' + maxFileSizeMb + 'MB';
                    fileName = ''; previewUrl = '';
                    $el.value = '';
                    return;
                }
                handleFile(f);
            "
        />

        {{-- Empty state --}}
        <div x-show="!fileName && !previewUrl" class="flex flex-col items-center justify-center py-4 pointer-events-none select-none">
            <svg class="w-10 h-10 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
            </svg>
            <p class="text-sm text-gray-600"><span class="font-semibold">Click to upload</span> or drag &amp; drop</p>
            <p class="text-xs text-gray-400 mt-0.5">PDF, JPG, PNG · max {{ $maxFileSizeMb }}MB</p>
        </div>

        {{-- Image preview --}}
        <div x-show="previewUrl" class="flex items-center gap-3 pointer-events-none">
            <img :src="previewUrl" class="w-16 h-16 object-cover rounded-lg border border-green-300 shadow-sm" alt="Preview" />
            <div>
                <p class="text-sm font-semibold text-green-700" x-text="fileName"></p>
                <p class="text-xs flex items-center gap-1" :class="uploadDone ? 'text-green-600' : 'text-blue-500'">
                    <template x-if="!isUploading && uploadDone">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </template>
                    <span x-text="isUploading ? 'Uploading...' : (uploadDone ? 'Saved!' : 'Ready to upload')"></span>
                </p>
            </div>
        </div>

        {{-- PDF / other file preview --}}
        <div x-show="fileName && !previewUrl" class="flex items-center gap-3 pointer-events-none">
            <div class="w-14 h-14 bg-red-100 rounded-lg flex flex-col items-center justify-center border border-red-200 flex-shrink-0">
                <svg class="w-6 h-6 text-red-500 mb-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"/>
                </svg>
                <span class="text-red-600 text-[10px] font-bold leading-none">PDF</span>
            </div>
            <div>
                <p class="text-sm font-semibold text-green-700 break-all" x-text="fileName"></p>
                <p class="text-xs flex items-center gap-1" :class="uploadDone ? 'text-green-600' : 'text-blue-500'">
                    <template x-if="!isUploading && uploadDone">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                        </svg>
                    </template>
                    <span x-text="isUploading ? 'Uploading...' : (uploadDone ? 'Saved!' : 'Ready to upload')"></span>
                </p>
            </div>
        </div>

    </div>{{-- /drop zone --}}

    {{-- Upload Progress Bar --}}
    <div x-show="isUploading || uploadDone || uploadError" x-cloak class="mt-2">
        <div class="flex items-center justify-between mb-1">
            <span class="text-xs font-medium"
                :class="uploadError ? 'text-red-500' : (uploadDone ? 'text-green-600' : 'text-blue-600')"
                x-text="uploadError ? 'Upload failed!' : (uploadDone ? 'Upload complete!' : 'Uploading...')"></span>
            <span class="text-xs font-semibold"
                :class="uploadError ? 'text-red-500' : (uploadDone ? 'text-green-600' : 'text-blue-600')"
                x-show="!uploadError"
                x-text="uploadProgress + '%'"></span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-1.5 overflow-hidden">
            <div
                class="h-1.5 rounded-full transition-all duration-300"
                :class="uploadError ? 'bg-red-500' : (uploadDone ? 'bg-green-500' : 'bg-blue-500')"
                :style="'width: ' + (uploadError ? '100' : uploadProgress) + '%'"
            ></div>
        </div>
    </div>

    {{-- Size error --}}
    <span x-show="sizeError" x-text="sizeError" class="text-red-500 text-xs mt-1 block"></span>

    {{-- Livewire validation error --}}
    @if($errors->has($errorKey))
        <span class="text-red-500 text-xs block mt-0.5">{{ $errors->first($errorKey) }}</span>
    @endif

    {{-- Existing uploaded file link --}}
    @if(!empty($existingUrl))
        <a href="{{ $existingUrl }}" target="_blank" rel="noopener noreferrer"
            class="inline-flex items-center gap-1 mt-1 text-xs text-blue-600 underline hover:text-blue-800 transition">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            View uploaded file
        </a>
    @endif

</div>
