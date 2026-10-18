@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div id="productEditApp" class="row justify-content-center">
    <div class="col-lg-8">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Edit Product</h1>
                <p class="text-muted mb-0">Perbarui master data Product.</p>
            </div>
            <a href="{{ route('product.index') }}"
               class="btn btn-outline-secondary">
                Kembali
            </a>
        </div>

        <div v-if="loading" class="alert alert-info">
            Memuat Product...
        </div>
        <div v-if="message" class="alert alert-success">
            @{{ message }}
        </div>
        <div v-if="error" class="alert alert-danger">
            @{{ error }}
        </div>

        <form v-if="!loading"
              @submit.prevent="submitForm"
              class="card shadow-sm border-0">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Kode</label>
                        <input v-model.trim="form.kode"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-8">
                        <label class="form-label">Nama Product</label>
                        <input v-model.trim="form.name"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Stok</label>
                        <input type="number"
                               min="0"
                               v-model.number="form.stok"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Harga Dasar</label>
                        <input type="number"
                               min="0"
                               v-model.number="form.modal"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-12">
                        <label class="form-label">Deskripsi</label>
                        <textarea v-model.trim="form.deskripsi"
                                  class="form-control"
                                  rows="3"></textarea>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-white text-end">
                <button class="btn btn-primary" :disabled="saving">
                    @{{ saving ? 'Menyimpan...' : 'Update Product' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
const { createApp, ref, onMounted } = Vue;

createApp({
    setup() {
        const productId = @json($id);
        const API_PRODUCT = @json(url('/api/products')) + '/' + productId;

        const form = ref({
            kode: '', name: '', deskripsi: '', stok: 0, modal: 0
        });
        const loading = ref(true);
        const saving = ref(false);
        const message = ref(null);
        const error = ref(null);

        async function loadProduct() {
            try {
                const response = await fetch(API_PRODUCT);
                if (!response.ok) {
                    throw new Error('Product tidak ditemukan.');
                }
                const data = await response.json();
                form.value = {
                    kode: data.kode,
                    name: data.name,
                    deskripsi: data.deskripsi || '',
                    stok: Number(data.stok),
                    modal: Number(data.modal)
                };
            } catch (err) {
                error.value = err.message;
            } finally {
                loading.value = false;
            }
        }

        async function submitForm() {
            saving.value = true;
            message.value = null;
            error.value = null;

            try {
                const response = await fetch(API_PRODUCT, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(form.value)
                });

                const result = await response.json();
                if (!response.ok) {
                    throw new Error(
                        result.message || 'Gagal memperbarui Product.'
                    );
                }
                message.value = result.message;
            } catch (err) {
                error.value = err.message;
            } finally {
                saving.value = false;
            }
        }

        onMounted(loadProduct);

        return {
            form, loading, saving, message, error, submitForm
        };
    }
}).mount('#productEditApp');
</script>
@endpush
