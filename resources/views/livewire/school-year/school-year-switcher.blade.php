<div class="dropdown">

    <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button">
        <i class="fas fa-calendar-alt mr-1"></i>
        {{ $currentSchoolYear->year ?? '—' }}
        @if($currentSchoolYear && !$currentSchoolYear->is_active)
            <span class="badge badge-warning navbar-badge">Hist.</span>
        @endif
    </a>

    <div class="dropdown-menu dropdown-menu-left">

        <span class="dropdown-header">
            <i class="fas fa-exchange-alt mr-1"></i> Cambiar año escolar
        </span>

        @foreach ($schoolYears as $sy)
            <button type="button"
                    wire:click="switchYear({{ $sy->id }})"
                    class="dropdown-item {{ $sy->id == $currentSchoolYear->id ? 'active' : '' }}">
                <i class="fas fa-calendar-check mr-2"></i>
                {{ $sy->year }}
                @if($sy->is_active)
                    <span class="badge badge-success float-right">Activo</span>
                @elseif($sy->id == $currentSchoolYear->id)
                    <span class="badge badge-warning float-right">Viendo</span>
                @endif
            </button>
        @endforeach

        <div class="dropdown-divider"></div>

        <a href="{{ route('school-years.index') }}" class="dropdown-item">
            <i class="fas fa-cog mr-2"></i> Gestionar años escolares
        </a>

    </div>
</div>
