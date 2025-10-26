@extends('layouts.app')

@section('title', 'Carga Horaria - ' . $docente->user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Carga Horaria del Docente: {{ $docente->user->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.docentes.show', $docente->codigo) }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Volver al Perfil
                        </a>
                        <a href="{{ route('admin.docentes.grupos-asignados', $docente->codigo) }}" class="btn btn-info">
                            <i class="fas fa-list"></i> Ver Grupos Asignados
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">Materias Asignadas</span>
                                    <span class="info-box-number">{{ $docente->materias->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">Carreras</span>
                                    <span class="info-box-number">{{ $docente->carreras->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">Aulas Disponibles</span>
                                    <span class="info-box-number">{{ $aulas->count() }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <div class="info-box-content">
                                    <span class="info-box-text">Gestión Actual</span>
                                    <span class="info-box-number">{{ $gestiones->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Formulario para asignar grupo -->
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Asignar Grupo y Horario</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('admin.docentes.asignar-grupo', $docente->codigo) }}" method="POST" id="asignarGrupoForm">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="materia_sigla">Materia *</label>
                                                    <select class="form-control" id="materia_sigla" name="materia_sigla" required>
                                                        <option value="">Seleccione una materia</option>
                                                        @foreach($materiasDocente as $materia)
                                                            <option value="{{ $materia->sigla }}">
                                                                {{ $materia->sigla }} - {{ $materia->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="id_gestion">Gestión Académica *</label>
                                                    <select class="form-control" id="id_gestion" name="id_gestion" required>
                                                        <option value="">Seleccione una gestión</option>
                                                        @foreach($gestiones as $gestion)
                                                            <option value="{{ $gestion->id }}">
                                                                {{ $gestion->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="id_grupo">Grupo *</label>
                                                    <select class="form-control" id="id_grupo" name="id_grupo" required>
                                                        <option value="">Seleccione un grupo</option>
                                                        @foreach($grupos as $grupo)
                                                            <option value="{{ $grupo->id }}">
                                                                {{ $grupo->nombre }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="aula_id">Aula *</label>
                                                    <select class="form-control" id="aula_id" name="aula_id" required>
                                                        <option value="">Seleccione un aula</option>
                                                        @foreach($aulas as $aula)
                                                            <option value="{{ $aula->id }}">
                                                                {{ $aula->nombre }} (Cap: {{ $aula->capacidad }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Horarios dinámicos -->
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <label>Horarios *</label>
                                                <div id="horarios-container">
                                                    <div class="horario-item row mb-2">
                                                        <div class="col-md-3">
                                                            <select class="form-control" name="horarios[0][dia]" required>
                                                                <option value="">Día</option>
                                                                <option value="Lunes">Lunes</option>
                                                                <option value="Martes">Martes</option>
                                                                <option value="Miércoles">Miércoles</option>
                                                                <option value="Jueves">Jueves</option>
                                                                <option value="Viernes">Viernes</option>
                                                                <option value="Sábado">Sábado</option>
                                                            </select>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="time" class="form-control" name="horarios[0][hora_inicio]" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <input type="time" class="form-control" name="horarios[0][hora_fin]" required>
                                                        </div>
                                                        <div class="col-md-3">
                                                            <button type="button" class="btn btn-danger btn-remove-horario" disabled>
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button type="button" class="btn btn-sm btn-success mt-2" id="add-horario">
                                                    <i class="fas fa-plus"></i> Agregar Horario
                                                </button>
                                            </div>
                                        </div>

                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fas fa-save"></i> Asignar Grupo y Horario
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let horarioCount = 1;

    // Agregar horario
    $('#add-horario').click(function() {
        const newHorario = `
            <div class="horario-item row mb-2">
                <div class="col-md-3">
                    <select class="form-control" name="horarios[${horarioCount}][dia]" required>
                        <option value="">Día</option>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <input type="time" class="form-control" name="horarios[${horarioCount}][hora_inicio]" required>
                </div>
                <div class="col-md-3">
                    <input type="time" class="form-control" name="horarios[${horarioCount}][hora_fin]" required>
                </div>
                <div class="col-md-3">
                    <button type="button" class="btn btn-danger btn-remove-horario">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        $('#horarios-container').append(newHorario);
        horarioCount++;
        
        // Habilitar botones de eliminar si hay más de un horario
        $('.btn-remove-horario').prop('disabled', false);
    });

    // Eliminar horario
    $(document).on('click', '.btn-remove-horario', function() {
        if ($('.horario-item').length > 1) {
            $(this).closest('.horario-item').remove();
            horarioCount--;
        }
        
        // Deshabilitar botones de eliminar si solo queda un horario
        if ($('.horario-item').length === 1) {
            $('.btn-remove-horario').prop('disabled', true);
        }
    });
</script>
@endpush