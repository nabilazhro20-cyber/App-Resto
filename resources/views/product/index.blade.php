@extends('layouts.app')

@section('title', 'Product')

@section('content')
<div id="productApp">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Product</h1>
            <p class="text-muted mb-0">Master data makanan dan minuman.</p>
        </div>

        <a href="{{ route('product.create') }}" class="btn btn-primary">
            Tambah Product
        </a>
    </div>

    <div v-if="loading" class="alert alert-info">
        Memuat data Product...
    </div>

    <div v-else-if="error" class="alert alert-danger">
        @{{ error }}
    </div>

    <div v-else class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Stok</th>
                        <th>Harga Modal</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products" :key="product.id">
                        <td>@{{ product.kode }}</td>
                        <td>
                            <div class="fw-semibold">@{{ product.name }}</div>
                            <small class="text-muted">
                                @{{ product.deskripsi || '-' }}
                            </small>
                        </td>
                        <td>@{{ product.stok }}</td>
                        <td>Rp @{{ rupiah(product.modal) }}</td>
                        <td class="text-end">
                            <a :href="editUrl(product.id)"
                               class="btn btn-sm btn-outline-primary">
                                Edit
                            </a>
                        </td>
                    </tr>
                    <tr v-if="products.length === 0">
                        <td colspan="5" class="text-center text-muted py-4">
                            Belum ada Product.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const { createApp, ref, onMounted } = Vue;

createApp({
    setup() {
        const products = ref([]);
        const loading = ref(true);
        const error = ref(null);

        const API_PRODUCTS = @json(url('/api/products'));
        const PRODUCT_PAGE = @json(url('/product'));

        async function loadProducts() {
            try {
                const response = await fetch(API_PRODUCTS);
                if (!response.ok) {
                    throw new Error('Gagal mengambil data Product.');
                }
                products.value = await response.json();
            } catch (err) {
                error.value = err.message;
            } finally {
                loading.value = false;
            }
        }

        function rupiah(value) {
            return new Intl.NumberFormat('id-ID').format(value || 0);
        }

        function editUrl(id) {
            return `${PRODUCT_PAGE}/${id}/edit`;
        }

        onMounted(loadProducts);

        return { products, loading, error, rupiah, editUrl };
    }
}).mount('#productApp');
</script>
@endpush
