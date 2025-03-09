<div>
    <div id="calendar"></div>

    <style>
        .fc-event {
            white-space: normal;
            /* Permite que el texto se ajuste en múltiples líneas */
            word-wrap: break-word;
            /* Asegura que las palabras largas se dividan si es necesario */
        }

        #calendar {
            overflow: hidden;
            /* Oculta la barra de desplazamiento */
        }

        .color_not_assigned {
            color: #CCCCCC !important;
            /* Gris claro */
        }

        .color_approved {
            color: #FF6400 !important;
            /* Naranja */
        }

        .color_finished {
            color: #FFFF00 !important;
            /* Amarillo brillante */
        }

        #tooltip {
            position: absolute;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            font-size: 0.9rem;
            width: 200px;
            display: none;
        }
    </style>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>

    <script>
        // Usa el evento 'load' para asegurarte de que FullCalendar se haya cargado completamente
        window.addEventListener("load", function() {
            var calendarEl = document.getElementById("calendar");
            var tooltip = document.createElement('div');
            tooltip.id = 'tooltip';
            document.body.appendChild(tooltip);

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: "dayGridMonth",
                events: {!! json_encode($services) !!},
                eventContent: function(info) {
                    // Personalizar el contenido del evento para incluir color basado en estado
                    const status = info.event.extendedProps.status || 'No disponible';
                    const title = document.createElement('div');
                    title.innerText = info.event.title;

                    // Asignar color según el estado
                    if (status === 'Approved') {
                        title.classList.add('color_approved');
                    } else if (status === 'Finished') {
                        title.classList.add('color_finished');
                    } else {
                        title.classList.add('color_not_assigned');
                    }

                    return {
                        domNodes: [title]
                    };
                },
                eventMouseEnter: function(info) {
                    // Mostrar tooltip
                    const event = info.event;
                    tooltip.innerHTML = `
                        <strong>${event.title}</strong><br>
                        Fecha: ${event.start.toLocaleDateString()}<br>
                        ${event.extendedProps.description || ''}
                        <br>
                        ${event.extendedProps.unit_number}<br>
                        Estado: ${event.extendedProps.status || 'No disponible'}
                    `;
                    tooltip.style.display = 'block';
                    tooltip.style.left = info.jsEvent.pageX + 10 + 'px';
                    tooltip.style.top = info.jsEvent.pageY + 10 + 'px';
                },
                eventMouseLeave: function() {
                    // Ocultar tooltip
                    tooltip.style.display = 'none';
                },
                eventMouseMove: function(info) {
                    // Mover el tooltip con el ratón
                    tooltip.style.left = info.jsEvent.pageX + 10 + 'px';
                    tooltip.style.top = info.jsEvent.pageY + 10 + 'px';
                },
                eventClick: function(info) {
                    // Redirige a la página de edición del servicio
                    var serviceId = info.event
                        .id; // Asumiendo que el ID del servicio está en la propiedad 'id'
                    window.location.href = '/admin/services/' + serviceId +
                        '/edit'; // Cambia la URL según sea necesario
                }
            });
            calendar.render();
        });
    </script>
</div>
