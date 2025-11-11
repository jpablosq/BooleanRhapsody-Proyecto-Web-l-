<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar viajes - Aventones</title>
    <link rel="stylesheet" href="styles/styles_search.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
<div class="auth-container">
    <!-- Lado izquierdo -->
    <div class="left-section">
        <div class="left-content">
            <div class="logo">Aventones</div>
            <div class="tx">
                <h1>Encuentra tu viaje<br>perfecto</h1>
            </div>
        </div>
    </div>

    <!-- Lado derecho -->
    <div class="right-section">
        <div class="form-container">
            <div class="form-content">
                <h2 class="form-title">Buscar viajes disponibles</h2>
                <p class="form-subtitle">
                    Filtra y encuentra el viaje que necesitas
                </p>

                <form id="searchForm">
                    <!-- Lugar de salida -->
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <input type="text" class="input with-icon" name="origen" placeholder="¿Desde dónde partes?">
                    </div>

                    <!-- Lugar de destino -->
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="bi bi-geo-fill"></i>
                        </div>
                        <input type="text" class="input with-icon" name="destino" placeholder="¿A dónde vas?">
                    </div>

                    <!-- Fecha y hora -->
                    <div class="input-row">
                        <div class="input-wrapper">
                            <div class="input-group">
                                <div class="input-icon">
                                    <i class="bi bi-calendar-event"></i>
                                </div>
                                <input type="date" class="input with-icon" name="fecha">
                            </div>
                        </div>
                        <div class="input-wrapper">
                            <div class="input-group">
                                <div class="input-icon">
                                    <i class="bi bi-clock"></i>
                                </div>
                                <input type="time" class="input with-icon" name="hora">
                            </div>
                        </div>
                    </div>

                    <!-- Día de la semana -->
                    <div class="input-group">
                        <label class="label-group">Día de la semana (opcional)</label>
                        <div class="days-grid">
                            <label class="day-checkbox">
                                <input type="radio" name="dia" value="">
                                <span class="day-label">Todos</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="radio" name="dia" value="Lunes">
                                <span class="day-label">L</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="radio" name="dia" value="Martes">
                                <span class="day-label">M</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="radio" name="dia" value="Miércoles">
                                <span class="day-label">X</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="radio" name="dia" value="Jueves">
                                <span class="day-label">J</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="radio" name="dia" value="Viernes">
                                <span class="day-label">V</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="radio" name="dia" value="Sábado">
                                <span class="day-label">S</span>
                            </label>
                            <label class="day-checkbox">
                                <input type="radio" name="dia" value="Domingo">
                                <span class="day-label">D</span>
                            </label>
                        </div>
                    </div>

                    <!-- Número de espacios -->
                    <div class="input-group">
                        <div class="input-icon">
                            <i class="bi bi-people"></i>
                        </div>
                        <input type="number" class="input with-icon" name="espacios" placeholder="Número de espacios necesarios" min="1" max="10">
                    </div>

                    <!-- Rango de precio -->
                    <div class="input-group">
                        <label class="label-group">Rango de precio</label>
                        <div class="input-row">
                            <div class="input-wrapper">
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <input type="number" class="input with-icon" name="precio_min" placeholder="Mínimo" min="0" step="0.01">
                                </div>
                            </div>
                            <div class="input-wrapper">
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="bi bi-currency-dollar"></i>
                                    </div>
                                    <input type="number" class="input with-icon" name="precio_max" placeholder="Máximo" min="0" step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <i class="bi bi-search"></i> Buscar viajes
                    </button>

                    <button type="button" class="clear-btn">
                        <i class="bi bi-x-circle"></i> Limpiar filtros
                    </button>

                    <button type="button" class="cancel-btn" onclick="window.location.href='index.php'">
                        <i class="bi bi-x-lg"></i> Cancelar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Select personalizado
    const selects = document.querySelectorAll('select');
    selects.forEach(select => {
        select.addEventListener('change', function() {
            if (this.value) {
                this.style.color = 'var(--text-primary)';
            }
        });
    });

    // Días de la semana con radio buttons
    const dayRadios = document.querySelectorAll('.day-checkbox');
    dayRadios.forEach(radio => {
        radio.addEventListener('click', function() {
            const input = this.querySelector('input[type="radio"]');
            
            // Remover estilos de todos
            dayRadios.forEach(r => {
                const label = r.querySelector('.day-label');
                label.style.backgroundColor = 'var(--input-bg)';
                label.style.color = 'var(--text-secondary)';
            });
            
            // Aplicar estilo al seleccionado
            if (input.checked) {
                const label = this.querySelector('.day-label');
                label.style.backgroundColor = 'var(--primary-purple)';
                label.style.color = 'white';
            }
        });
    });

    // Limpiar filtros
    const clearBtn = document.querySelector('.clear-btn');
    clearBtn.addEventListener('click', function() {
        document.getElementById('searchForm').reset();
        
        // Resetear estilos de días
        dayRadios.forEach(r => {
            const label = r.querySelector('.day-label');
            label.style.backgroundColor = 'var(--input-bg)';
            label.style.color = 'var(--text-secondary)';
        });
        
        // Resetear color del select
        selects.forEach(select => {
            select.style.color = 'var(--text-secondary)';
        });
    });
</script>
</body>
</html>
