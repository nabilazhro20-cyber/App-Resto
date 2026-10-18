@extends('layouts.app')

@section('title', 'Edit Menu')

@section('content')
<div id="menuEditApp" class="row justify-content-center">
    <div class="col-xl-10">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h1 class="h3 mb-1">Edit Menu Resto</h1>
                <p class="text-muted mb-0">
                    Perbarui master Menu dan susunan Product.
                </p>
            </div>
            <a href="{{ route('menu.index') }}"
               class="btn btn-outline-secondary">
                Kembali
            </a>
        </div>

        <div v-if="loading" class="alert alert-info">
            Memuat Menu...
        </div>
        <div v-if="message" class="alert alert-success">
            @{{ message }}
        </div>
        <div v-if="error" class="alert alert-danger">
            @{{ error }}
        </div>

        <form v-if="!loading" @submit.prevent="submitForm">
            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-semibold">Master Menu</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Kode Menu</label>
                            <input v-model.trim="form.kode"
                                   class="form-control"
                                   required>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Nama Menu</label>
                            <input v-model.trim="form.nama"
                                   class="form-control"
                                   required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-semibold">
                    Tambah Product
                </div>
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-7">
                            <label class="form-label">Product</label>
                            <select v-model="itemBaru.id_product"
                                    @change="gunakanHargaProduct"
                                    class="form-select">
                                <option value="">Pilih Product</option>
                                <option v-for="product in products"
                                        :key="product.id"
                                        :value="product.id">
                                    @{{ product.kode }} - @{{ product.name }}
                                </option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Harga Menu</label>
                            <input type="number"
                                   min="0"
                                   v-model.number="itemBaru.harga"
                                   class="form-control">
                        </div>

                        <div class="col-md-2 d-grid">
                            <button type="button"
                                    @click="tambahItem"
                                    class="btn btn-outline-primary">
                                Tambah
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3">
                <div class="card-header bg-white fw-semibold">Detail Menu</div>
                <div class="table-responsive">
                    <table class="table align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th style="width: 220px;">Harga Menu</th>
                                <th class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in form.items"
                                :key="item.id_product">
                                <td>@{{ item.kode }} - @{{ item.nama_product }}</td>
                                <td>
                                    <input type="number"
                                           min="0"
                                           v-model.number="item.harga"
                                           class="form-control form-control-sm">
                                </td>
                                <td class="text-end">
                                    <button type="button"
                                            @click="hapusItem(index)"
                                            class="btn btn-sm btn-outline-danger">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="text-end">
                <button class="btn btn-primary" :disabled="saving">
                    @{{ saving ? 'Menyimpan...' : 'Update Menu' }}
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
        const menuId = @json($id);
        const API_PRODUCTS = @json(url('/api/products'));
        const API_MENU = @json(url('/api/menus')) + '/' + menuId;

        const products = ref([]);
        const form = ref({ kode: '', nama: '', items: [] });
        const itemBaru = ref({ id_product: '', harga: 0 });
        const loading = ref(true);
        const saving = ref(false);
        const message = ref(null);
        const error = ref(null);

        async function loadData() {
            try {
                const [productResponse, menuResponse] = await Promise.all([
                    fetch(API_PRODUCTS),
                    fetch(API_MENU)
                ]);

                if (!productResponse.ok || !menuResponse.ok) {
                    throw new Error('Gagal memuat data edit Menu.');
                }

                products.value = await productResponse.json();
                const menu = await menuResponse.json();

                form.value = {
                    kode: menu.kode,
                    nama: menu.nama,
                    items: menu.details.map(detail => ({
                        id_product: detail.id_product,
                        kode: detail.product.kode,
                        nama_product: detail.product.name,
                        harga: Number(detail.harga)
                    }))
                };
            } catch (err) {
                error.value = err.message;
            } finally {
                loading.value = false;
            }
        }

        function gunakanHargaProduct() {
            const product = products.value.find(
                item => item.id == itemBaru.value.id_product
            );
            itemBaru.value.harga = product ? Number(product.modal) : 0;
        }

        function tambahItem() {
            error.value = null;
            const product = products.value.find(
                item => item.id == itemBaru.value.id_product
            );

            if (!product) {
                error.value = 'Pilih Product terlebih dahulu.';
                return;
            }

            if (form.value.items.some(item => item.id_product == product.id)) {
                error.value = 'Product sudah ada pada Menu.';
                return;
            }

            form.value.items.push({
                id_product: product.id,
                kode: product.kode,
                nama_product: product.name,
                harga: Number(itemBaru.value.harga)
            });

            itemBaru.value = { id_product: '', harga: 0 };
        }

        function hapusItem(index) {
            form.value.items.splice(index, 1);
        }

        async function submitForm() {
            if (form.value.items.length === 0) {
                error.value = 'Menu minimal memiliki satu Product.';
                return;
            }

            saving.value = true;
            message.value = null;
            error.value = null;

            const payload = {
                kode: form.value.kode,
                nama: form.value.nama,
                items: form.value.items.map(item => ({
                    id_product: item.id_product,
                    harga: item.harga
                }))
            };

            try {
                const response = await fetch(API_MENU, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });

                const result = await response.json();
                if (!response.ok) {
                    throw new Error(
                        result.message || 'Gagal memperbarui Menu.'
                    );
                }

                message.value = result.message;
            } catch (err) {
                error.value = err.message;
            } finally {
                saving.value = false;
            }
        }

        onMounted(loadData);

        return {
            products, form, itemBaru, loading, saving, message, error,
            gunakanHargaProduct, tambahItem, hapusItem, submitForm
        };
    }
}).mount('#menuEditApp');
</script>
@endpush
