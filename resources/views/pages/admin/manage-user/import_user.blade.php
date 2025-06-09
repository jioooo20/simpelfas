<dialog id="modal_import_user" class="modal">
    <div class="modal-box w-11/12 max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2 hover:bg-red-100 hover:text-red-600 transition-colors">✕</button>
        </form>

        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                <i class="fas fa-upload text-2xl text-blue-600"></i>
            </div>
            <h3 class="text-2xl font-bold text-gray-800">Impor Data Pengguna</h3>
            <p class="text-gray-600 mt-2">Upload file Excel untuk menambahkan multiple user sekaligus</p>
        </div>

        <div class="space-y-6">
            <!-- Progress Steps -->
            <div class="flex items-center justify-center space-x-4 mb-8">
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-semibold">1</div>
                    <span class="ml-2 text-sm font-medium text-blue-600">Download Template</span>
                </div>
                <div class="w-12 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full text-sm font-semibold">2</div>
                    <span class="ml-2 text-sm font-medium text-gray-600">Upload File</span>
                </div>
                <div class="w-12 h-0.5 bg-gray-300"></div>
                <div class="flex items-center">
                    <div class="flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full text-sm font-semibold">3</div>
                    <span class="ml-2 text-sm font-medium text-gray-600">Impor Selesai</span>
                </div>
            </div>

            <!-- Template Download Section -->
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-xl p-6">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg">
                            <i class="fas fa-file-excel text-xl text-green-600"></i>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h4 class="text-lg font-semibold text-green-800 mb-2">Download Template Excel</h4>
                        <p class="text-green-700 mb-4">Template ini berisi format yang benar untuk data user. Pastikan mengikuti struktur kolom yang telah disediakan dan membaca petunjuk di dalam excel.</p>
                        <div class="flex flex-wrap gap-2 text-sm text-green-600 mb-4">
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 rounded-full">
                                <i class="fas fa-check w-3 h-3 mr-1"></i>
                                Format standar
                            </span>
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 rounded-full">
                                <i class="fas fa-check w-3 h-3 mr-1"></i>
                                Kolom lengkap
                            </span>
                            <span class="inline-flex items-center px-2 py-1 bg-green-100 rounded-full">
                                <i class="fas fa-check w-3 h-3 mr-1"></i>
                                Contoh data
                            </span>
                        </div>
                        <a href="{{ asset('template_user.xlsx') }}"
                           class="btn btn-success btn-sm hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                            <i class="fas fa-download mr-2"></i>
                            Download Template
                        </a>
                    </div>
                </div>
            </div>

            <!-- Upload Section -->
            <form action="{{ route('admin.import-user') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div class="bg-gray-50 border-2 border-dashed border-gray-300 rounded-xl p-8 transition-all duration-200">
                    <div class="text-center">
                        <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                            <i class="fas fa-cloud-upload-alt text-2xl text-blue-600"></i>
                        </div>
                        <h4 class="text-lg font-semibold text-gray-800 mb-2">Upload File Excel</h4>
                        <p class="text-gray-600 mb-4">Pilih file Excel yang telah diisi sesuai template</p>

                        <input type="file" name="excel_file" accept=".xlsx,.xls" id="excel_file"
                               class="hidden" required onchange="updateFileName(this)">

                        <label for="excel_file" class="btn btn-outline btn-primary cursor-pointer hover:shadow-lg transition-all duration-200">
                            <i class="fas fa-folder-open mr-2"></i>
                            Pilih File
                        </label>

                        <div id="file-info" class="mt-4 hidden">
                            <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-lg">
                                <i class="fas fa-file-excel mr-2"></i>
                                <span id="file-name"></span>
                                <button type="button" onclick="clearFile()" class="ml-2 text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="mt-4 flex flex-wrap justify-center gap-4 text-sm text-gray-500">
                            <span class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-1"></i>
                                Format: .xlsx, .xls
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-1"></i>
                                Max: 2MB
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-1"></i>
                                Multiple users
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-end gap-3 pt-6 border-t border-gray-200">
                    <button type="button" class="btn btn-ghost bg-base-300 hover:bg-gray-300 transition-colors" onclick="modal_import_user.close()">
                        <i class="fas fa-times mr-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-primary text-white hover:shadow-lg transition-all duration-200 transform hover:-translate-y-0.5">
                        <i class="fas fa-upload mr-2"></i>
                        Impor Data Pengguna
                    </button>
                </div>
            </form>
        </div>
    </div>
</dialog>

@push('skrip')
<script>
function updateFileName(input) {
    const fileInfo = document.getElementById('file-info');
    const fileName = document.getElementById('file-name');

    if (input.files && input.files[0]) {
        fileName.textContent = input.files[0].name;
        fileInfo.classList.remove('hidden');

        // Update progress step
        updateProgressStep(2);
    }
}

function clearFile() {
    const fileInput = document.getElementById('excel_file');
    const fileInfo = document.getElementById('file-info');

    fileInput.value = '';
    fileInfo.classList.add('hidden');

    // Reset progress step
    updateProgressStep(1);
}

function updateProgressStep(step) {
    // Reset all steps
    for (let i = 1; i <= 3; i++) {
        const stepElement = document.querySelector(`.flex:nth-child(${i*2-1}) .w-8`);
        const stepText = document.querySelector(`.flex:nth-child(${i*2-1}) .ml-2`);

        if (i <= step) {
            stepElement.className = 'flex items-center justify-center w-8 h-8 bg-blue-600 text-white rounded-full text-sm font-semibold';
            stepText.className = 'ml-2 text-sm font-medium text-blue-600';
        } else {
            stepElement.className = 'flex items-center justify-center w-8 h-8 bg-gray-300 text-gray-600 rounded-full text-sm font-semibold';
            stepText.className = 'ml-2 text-sm font-medium text-gray-600';
        }
    }
}
</script>
@endpush
