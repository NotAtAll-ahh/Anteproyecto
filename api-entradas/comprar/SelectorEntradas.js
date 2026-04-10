const AVAILABLE_SEAT_IMG = '../media/asientos/Disponible.png';
const SELECTED_SEAT_IMG = '../media/asientos/Seleccion.png';
const UNAVAILABLE_SEAT_IMG = '../media/asientos/NoDisponible.png';

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
        const img = document.createElement('img');
        img.src = AVAILABLE_SEAT_IMG;
        seat.appendChild(img);
        row.appendChild(seat);
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

            const isSelected = s.classList.toggle('selected');
            img.src = isSelected ? SELECTED_SEAT_IMG : AVAILABLE_SEAT_IMG;
        });
    });
}

window.addEventListener('DOMContentLoaded', () => {
    appendSeats(17, "front-row-first-front-row");
    appendSeats(19, "front-row-second-front-row");
    appendSeats(84, "middle-row");
    appendSeats(15, "front-row-third-last-row");
    appendSeats(11, "front-row-second-last-row");
    appendSeats(9, "front-row-first-last-row");
    selectSeats();
    setRandomUnavailableSeats(50);
});