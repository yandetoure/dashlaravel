<?php declare(strict_types=1); ?>
@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Calendrier</title>
    <!-- FullCalendar v5 -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
    <!-- Bootstrap 4 -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <!-- Tippy.js -->
    <link rel="stylesheet" href="https://unpkg.com/tippy.js@6/themes/light.css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#3B82F6',
                        secondary: '#10B981',
                        dark: '#1F2937',
                    }
                }
            }
        }
    </script>
    <style>
    #calendar {
            max-width: 900px;
            margin: 40px auto;
        }
        .reservation-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .status-badge {
            top: -10px;
            right: -10px;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <h1 class="text-center my-4">Calendrier Google</h1>
    
    <div id="calendar"></div>

    <!-- Modal -->
    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="eventModalLabel">Détails de l'événement</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p><strong>Titre :</strong> <span id="eventTitle"></span></p>
                    <p><strong>Début :</strong> <span id="eventStart"></span></p>
                    <p><strong>Fin :</strong> <span id="eventEnd"></span></p>
                    <p><strong>Description :</strong> <span id="eventDescription"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JS FullCalendar, jQuery, Bootstrap, Tippy -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/fr.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://unpkg.com/tippy.js@6"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                locale: 'fr',
                initialView: 'dayGridMonth',
                events: {!! json_encode($events) !!},

                eventDidMount: function (info) {
                    // Affiche un tooltip au survol
                    tippy(info.el, {
                        content: info.event.extendedProps.description?.replace(/\n/g, '<br>') || 'Pas de description',
                        allowHTML: true,
                        theme: 'light',
                        placement: 'top',
                    });
                },

                eventClick: function (info) {
                    // Affiche les infos dans le modal
                    document.getElementById('eventTitle').textContent = info.event.title;
                    document.getElementById('eventStart').textContent = info.event.start.toLocaleString();
                    document.getElementById('eventEnd').textContent = info.event.end ? info.event.end.toLocaleString() : 'Non défini';
                    document.getElementById('eventDescription').textContent = info.event.extendedProps.description || 'Pas de description';

                    $('#eventModal').modal('show');
                }
            });

            calendar.render();
        });
    </script>

</body>
</html>
@endsection