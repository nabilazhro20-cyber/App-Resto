@extends('layouts.app')

@section('title', 'Menu Resto')

@section('content')
<div id="menuApp">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h1 class="h3 mb-1">Menu Resto</h1>
            <p class="text-muted mb-0">
                Master Menu beserta Product dan harga yang ditawarkan.
            </p>
        </div>

        <a href="{{ route('menu.create') }}" class="btn btn-primary">
            Tambah Menu
        </a>
    </div>

    <div v-if="loading" class="alert alert-info">
        Memuat data Menu...
    </div>
    <div v-else-if="error" class="alert alert-danger">
        @{{ error }}
    </div>

    <div v-else-if="menus.length === 0" class="alert alert-warning">
        Belum ada Menu.
    </div>

    <div v-else class="row g-3">
        <div v-for="menu in menus" :key="menu.id" class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white d-flex justify-content-between">
                    <div>
                        <span class="badge text-bg-secondary me-2">
                            @{{ menu.kode }}
                        </span>
                        <strong>@{{ menu.nama }}</strong>
                    </div>
                    <a :href="editUrl(menu.id)"
                       class="btn btn-sm btn-outline-primary">
                        Edit
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Harga Modal</th>
                                <th>Harga Menu</th>
                                <th>Profit</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="detail in menu.details" :key="detail.id">
                                <td>
                                    @{{ detail.product.kode }} -
                                    @{{ detail.product.name }}
                                </td>
                                <td>Rp @{{ rupiah(detail.product.modal) }}</td>
                                <td class="fw-semibold">
                                    Rp @{{ rupiah(detail.harga) }}
                                </td>
                                <td class="fw-semibold">
                                    Rp @{{ rupiah(detail.harga - detail.product.modal) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
const { createApp, ref, onMounted } = Vue;

createApp({
    setup() {
        const menus = ref([]);
        const loading = ref(true);
        const error = ref(null);

        const API_MENUS = @json(url('/api/menus'));
        const MENU_PAGE = @json(url('/menu'));

        async function loadMenus() {
            try {
                const response = await fetch(API_MENUS);
                if (!response.ok) {
                    throw new Error('Gagal mengambil data Menu.');
                }
                menus.value = await response.json();
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
            return `${MENU_PAGE}/${id}/edit`;
        }

        onMounted(loadMenus);

        return { menus, loading, error, rupiah, editUrl };
    }
}).mount('#menuApp');
</script>
@endpush
