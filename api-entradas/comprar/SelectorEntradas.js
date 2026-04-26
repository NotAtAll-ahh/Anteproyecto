const AVAILABLE_SEAT_IMG = '../../media/asientos/Disponible.png';
const SELECTED_SEAT_IMG = '../../media/asientos/Seleccion.png';
const UNAVAILABLE_SEAT_IMG = '../../media/asientos/NoDisponible.png';
let seatCounter = 0;

function setRandomUnavailableSeats(numSeats) {
    const seats = document.querySelectorAll('.seat');
    const totalSeats = seats.length;
    const unavailableSeats = new Set();

    while (unavailableSeats.size < numSeats) {
        const randomIndex = Math.floor(Math.random() * totalSeats);
        unavailableSeats.add(randomIndex);
    }

    unavailableSeats.forEach((index) => {
        const seat = seats[index];
        const img = seat.querySelector('img');
        if (img) {
            img.src = UNAVAILABLE_SEAT_IMG;
            seat.classList.add('unavailable');
            img.alt = 'Asiento no disponible';
        }
    });
}


function appendSeats(numSeats, rowClass) {
    const row = document.querySelector(`.${rowClass}`);
    if (!row) {
        console.warn(`No se encontro la fila: .${rowClass}`);
        return;
    }

    for (let i = 0; i < numSeats; i++) {
        const seat = document.createElement('div');
        seat.classList.add('seat');
        seatCounter += 1;
        seat.dataset.seatId = String(seatCounter);
        const img = document.createElement('img');
        img.src = AVAILABLE_SEAT_IMG;
        img.alt = 'Asiento disponible';
        seat.appendChild(img);
        row.appendChild(seat);
    }
}

function getMaxSeleccionables() {
    let total = 0;
    document.querySelectorAll('.ticket-stepper .cantidad').forEach((span) => {
        total += parseInt(span.textContent, 10) || 0;
    });
    return total;
}

function getAsientosSeleccionados() {
    return Array.from(document.querySelectorAll('.seat.selected'));
}

function sincronizarAsientosConEntradas() {
    const maxSeleccionables = getMaxSeleccionables();
    const seleccionados = getAsientosSeleccionados();

    if (seleccionados.length <= maxSeleccionables) {
        return;
    }

    // Si bajan las entradas, quitamos los asientos extra empezando por los ultimos seleccionados.
    for (let i = maxSeleccionables; i < seleccionados.length; i++) {
        const seat = seleccionados[i];
        seat.classList.remove('selected');
        const img = seat.querySelector('img');
        if (img) {
            img.src = AVAILABLE_SEAT_IMG;
        }
    }
}

function selectSeats() {
    const seats = document.querySelectorAll('.seat');

    seats.forEach((s) => {
        s.addEventListener('click', () => {
            if (s.classList.contains('unavailable')) {
                return;
            }

            const img = s.querySelector('img');
            if (!img) return;

            // Permitir deseleccionar siempre.
            if (s.classList.contains('selected')) {
                s.classList.remove('selected');
                img.src = AVAILABLE_SEAT_IMG;
                img.alt = 'Asiento disponible';
                return;
            }

            const maxSeleccionables = getMaxSeleccionables();
            const seleccionados = getAsientosSeleccionados().length;

            if (maxSeleccionables === 0) {
                alert('Primero selecciona la cantidad de entradas.');
                return;
            }

            if (seleccionados >= maxSeleccionables) {
                alert('Solo puedes seleccionar ' + maxSeleccionables + ' asiento(s).');
                return;
            }

            s.classList.add('selected');
            img.src = SELECTED_SEAT_IMG;
            img.alt = 'Asiento seleccionado';
        });
    });
}

function initSteppers() {
    document.querySelectorAll('.ticket-stepper').forEach((stepper) => {
        const decreaseBtn = stepper.querySelector('.btn-decrease');
        const increaseBtn = stepper.querySelector('.btn-increase');
        const cantidadSpan = stepper.querySelector('.cantidad');

        if (decreaseBtn && increaseBtn && cantidadSpan) {
            decreaseBtn.addEventListener('click', (e) => {
                e.preventDefault();
                let cantidad = parseInt(cantidadSpan.textContent) || 0;
                if (cantidad > 0) {
                    cantidad--;
                    cantidadSpan.textContent = cantidad;
                    actualizarResumenEntradas();
                    sincronizarAsientosConEntradas();
                }
            });

            increaseBtn.addEventListener('click', (e) => {
                e.preventDefault();
                let cantidad = parseInt(cantidadSpan.textContent) || 0;
                cantidad++;
                cantidadSpan.textContent = cantidad;
                actualizarResumenEntradas();
                sincronizarAsientosConEntradas();
            });
        }
    });
}

function actualizarResumenEntradas() {
    const cantidadSpan = document.querySelector('.ticket-stepper .cantidad');
    const cantidad = parseInt(cantidadSpan?.textContent) || 0;
    const eventoNombre = document.getElementById('eventoNombre')?.textContent || 'Evento';
    const container = document.getElementById('resumenLineasContainer');

    if (!container) return;

    container.innerHTML = '';
    for (let i = 1; i <= cantidad; i++) {
        const div = document.createElement('div');
        div.className = 'resumen-linea';
        div.innerHTML = `
            <span>${eventoNombre} - Entrada ${i}</span>
            <span style="white-space: nowrap;">40&nbsp;€</span>
        `;
        container.appendChild(div);
    }

    // Actualizar total
    const totalPrecio = cantidad * 40;
    const totalSpan = document.querySelector('.resumen-total span:last-child');
    if (totalSpan) {
        totalSpan.textContent = totalPrecio + ' €';
    }
}

window.addEventListener('DOMContentLoaded', () => {
    appendSeats(17, "front-row-first-front-row");
    appendSeats(19, "front-row-second-front-row");
    appendSeats(84, "middle-row");
    appendSeats(15, "front-row-third-last-row");
    appendSeats(11, "front-row-second-last-row");
    appendSeats(9, "front-row-first-last-row");
    selectSeats();
    initSteppers();
    setRandomUnavailableSeats(50);

    // Cargar imagen del evento desde la URL
    const params = new URLSearchParams(window.location.search);
    const eventoId = params.get('evento_id');
    const img = document.querySelector('.imagen img');
    const nombreEventoH4 = document.getElementById('eventoNombre');
    const fechaEventoP = document.getElementById('fechaEvento');
    const tipoEntradaLabel = document.getElementById('tipoEntradaLabel');
    const buyBtn = document.querySelector('.buy-btn');

    if (buyBtn) {
        buyBtn.addEventListener('click', () => {
            const totalEntradas = getMaxSeleccionables();
            const asientosSeleccionados = getAsientosSeleccionados();
            const destino = new URL('DatosFacturacion.html', window.location.href);

            if (totalEntradas === 0) {
                alert('Debes seleccionar al menos una entrada.');
                return;
            }

            if (asientosSeleccionados.length !== totalEntradas) {
                alert('Debes seleccionar exactamente ' + totalEntradas + ' asiento(s) antes de continuar.');
                return;
            }

            if (eventoId) {
                destino.searchParams.set('evento_id', eventoId);
            }

            destino.searchParams.set('entradas', String(totalEntradas));
            destino.searchParams.set('asientos', String(asientosSeleccionados.length));
            destino.searchParams.set('seats', asientosSeleccionados
                .map((seat) => seat.dataset.seatId || '')
                .filter(Boolean)
                .join(','));

            window.location.href = destino.toString();
        });
    }

    if (eventoId) {
        fetch(`https://tarea-proyecto-seo-victoria-dani.free.nf/api-entradas/public/api/eventos/${encodeURIComponent(eventoId)}`)
            .then(res => res.json())
            .then(data => {
                const evento = data.data ?? data;
                if (img) {
                    img.src = evento && evento.imagen
                        ? 'https://tarea-proyecto-seo-victoria-dani.free.nf/api-entradas/public' + evento.imagen
                        : 'https://via.placeholder.com/300x450?text=Sin+imagen';
                    img.alt = (evento && evento.nombre) || 'Imagen del Evento';
                }
                if (nombreEventoH4 && evento && evento.nombre) {
                    nombreEventoH4.textContent = evento.nombre;
                    document.title = "Comprar entradas para" + evento.nombre;
                }
                if (fechaEventoP && evento && evento.fecha) {
                    const fecha = new Date(evento.fecha);
                    fechaEventoP.textContent = fecha.toLocaleDateString('es-ES');
                }
                if (tipoEntradaLabel && evento && evento.nombre) {
                    tipoEntradaLabel.textContent = evento.nombre + ' - Tipos de Entrada';
                }
            })
            .catch(() => {
                if (img) img.src = 'https://via.placeholder.com/300x450?text=Sin+imagen';
            });
    }
});