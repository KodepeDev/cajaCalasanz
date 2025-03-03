<div class="pt-4">
    <div class="container-fluid">
        <div class="card p-4">
            <div wire:loading.class='overlay' class="d-none dark" wire:loading.class.remove='d-none'>
                <i class="fas fa-2x fa-sync-alt fa-spin"></i>
            </div>
            <div class="card-header">
                <h3 class="card-title">Nuevo Cierre de Moviemientos </h3>
                <div class="card-tools">
                    <button type="button" disabled class="btn btn-primary"> <i class="fa fa-plus-circle"
                            aria-hidden="true"></i>
                        TIPO: {{ $type == 'MONTH' ? 'MENSUAL' : 'ANUAL' }}</button>
                </div>
            </div>
            <div class="card-body">
                <h3>BALANCE DEL PERIODO ANTERIOR <span
                        class="badge badge-info">{{ $prev_close != null ? $prev_close->from_date . ' - ' . $prev_close->to_date : 'NO HAY DATOS' }}</span>
                </h3>
                <hr>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ number_format($prev_incomes, 2) }} <sup style="font-size: 20px">S/.</sup>
                                        </h3>
                                        <p>INGRESOS</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ number_format($prev_expenses, 2) }} <sup
                                                style="font-size: 20px">S/.</sup></h3>
                                        <p>GASTOS</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($prev_incomes - $prev_expenses, 2) }} <sup
                                        style="font-size: 20px">S/.</sup></h3>
                                <p>RESULATADO</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <h3>BALANCE DEL PERIODO ACTUAL <span class="badge badge-info">{{ $from_date }} -
                        {{ $to_date }}</span></h3>
                <hr>
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>{{ number_format($current_incomes, 2) }} <sup
                                                style="font-size: 20px">S/.</sup></h3>
                                        <p>INGRESOS</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>{{ number_format($current_expenses, 2) }} <sup
                                                style="font-size: 20px">S/.</sup></h3>
                                        <p>GASTOS</p>
                                    </div>
                                    <div class="icon">
                                        <i class="ion ion-stats-bars"></i>
                                    </div>
                                    <a href="#" class="small-box-footer">
                                        <i class="fas fa-arrow-circle-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($current_incomes - $current_expenses, 2) }} <sup
                                        style="font-size: 20px">S/.</sup></h3>
                                <p>RESULATADO</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>

                    <div class="col-md-8">
                        <div x-data="{ cuentas: @entangle('account_data') }">
                            <template x-for="cuenta in cuentas">
                                <button type="button" class="btn btn-primary btn-lg mr-2 mt-2">
                                    <span class="text-uppercase" x-text="cuenta.name"></span>
                                    <span class="badge badge-success" x-text="cuenta.total_income"></span>
                                    <span class="badge badge-warning" x-text="cuenta.total_expense"></span>
                                    <span class="badge badge-danger" x-text="cuenta.total_nulled"></span>
                                </button>
                            </template>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ number_format($current_nulled, 2) }} <sup style="font-size: 20px">S/.</sup></h3>
                                <p>ANULADOS</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-4">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($prev_close ? $prev_close->current_balance : 0, 2) }} <sup
                                        style="font-size: 20px">S/.</sup>
                                </h3>
                                <p>BALANCE ANTERIOR</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($current_incomes - $current_expenses, 2) }} <sup
                                        style="font-size: 20px">S/.</sup>
                                </h3>
                                <p>BALANCE DEL CIERRE</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ number_format($current_balance, 2) }} <sup style="font-size: 20px">S/.</sup>
                                </h3>
                                <p>SALDO O SUMATORIA ACTUAL</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                            <a href="#" class="small-box-footer">
                                <i class="fas fa-arrow-circle-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 text-center">
                        <button type="button" class="btn btn-secondary mr-2">Cancelar</button>
                        <button type="button" class="btn btn-primary" wire:click="saveClose()">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('livewire.cierre-moviemientos.modal-type')
</div>

@push('js')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            window.livewire.on('error', msg => {
                Swal.fire({
                    icon: 'error',
                    title: 'Opss...!',
                    text: msg,
                });
            });
            $('#modalTypeClose').modal('show');
            window.livewire.on('showAnularModal', msg => {
                $('#modalTypeClose').modal('show');
            });
            window.livewire.on('closeModalAnular', msg => {
                $('#modalAnularRegistro').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Anulado...!',
                    text: msg,
                });
            });

        });
    </script>
@endpush
