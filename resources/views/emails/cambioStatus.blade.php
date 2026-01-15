<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estado del Ticket Actualizado</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header.status-open {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
        }
        .header.status-in-progress {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }
        .header.status-done {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #333;
        }
        .greeting strong {
            color: #3498db;
        }
        .status-badge {
            display: inline-block;
            padding: 12px 24px;
            border-radius: 25px;
            font-weight: bold;
            font-size: 16px;
            margin: 15px 0;
            text-align: center;
        }
        .status-badge.open {
            background-color: #ecf0f1;
            color: #7f8c8d;
        }
        .status-badge.in_progress {
            background-color: #fef5e7;
            color: #e67e22;
        }
        .status-badge.done {
            background-color: #eafaf1;
            color: #27ae60;
        }
        .ticket-summary {
            background-color: #f9f9f9;
            border-left: 4px solid #3498db;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
        }
        .ticket-summary.open {
            border-left-color: #95a5a6;
        }
        .ticket-summary.in_progress {
            border-left-color: #f39c12;
        }
        .ticket-summary.done {
            border-left-color: #27ae60;
        }
        .ticket-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .ticket-item:last-child {
            border-bottom: none;
        }
        .ticket-label {
            font-weight: 600;
            color: #3498db;
            min-width: 120px;
        }
        .ticket-summary.open .ticket-label {
            color: #95a5a6;
        }
        .ticket-summary.in_progress .ticket-label {
            color: #f39c12;
        }
        .ticket-summary.done .ticket-label {
            color: #27ae60;
        }
        .ticket-value {
            color: #555;
            text-align: right;
        }
        .priority-high {
            color: #e74c3c;
            font-weight: bold;
        }
        .priority-medium {
            color: #f39c12;
            font-weight: bold;
        }
        .priority-low {
            color: #27ae60;
            font-weight: bold;
        }
        .message {
            font-size: 14px;
            line-height: 1.6;
            color: #666;
            margin: 20px 0;
        }
        .status-description {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 4px;
            margin: 20px 0;
            border-left: 4px solid #3498db;
        }
        .status-description.open {
            background-color: #f4f6f7;
            border-left-color: #95a5a6;
        }
        .status-description.in_progress {
            background-color: #fef9e7;
            border-left-color: #f39c12;
        }
        .status-description.done {
            background-color: #eafaf1;
            border-left-color: #27ae60;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #3498db 0%, #2980b9 100%);
            color: white;
            padding: 12px 40px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin: 25px 0;
            text-align: center;
            transition: transform 0.3s ease;
        }
        .cta-button.open {
            background: linear-gradient(135deg, #95a5a6 0%, #7f8c8d 100%);
        }
        .cta-button.in_progress {
            background: linear-gradient(135deg, #f39c12 0%, #e67e22 100%);
        }
        .cta-button.done {
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
        }
        .cta-button:hover {
            transform: translateY(-2px);
        }
        .footer {
            background-color: #f9f9f9;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #eee;
            font-size: 12px;
            color: #999;
        }
        .footer a {
            color: #3498db;
            text-decoration: none;
        }
        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #3498db, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header status-{{ strtolower($ticket->status) }}">
            <h1>📋 Estado del Ticket Actualizado</h1>
            <p>Se ha realizado un cambio en el estado de tu actividad</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hola <strong>{{ $user->name }}</strong>,
            </div>

            <p class="message">
                Te informamos que el estado del ticket que tienes asignado ha sido actualizado. El ticket cambió de <strong>{{ $oldStatus }}</strong> a <strong>{{ $this->getStatusLabel($ticket->status) }}</strong>. A continuación encontrarás los detalles:
            </p>

            <!-- Status Change -->
            <center style="margin: 25px 0;">
                <div style="display: flex; align-items: center; justify-content: center; gap: 15px;">
                    <div class="status-badge {{ strtolower($oldStatus) }}">
                        @switch($oldStatus)
                            @case('open')
                                ⭕ Abierto
                                @break
                            @case('in_progress')
                                🔄 En Progreso
                                @break
                            @case('done')
                                ✅ Completado
                                @break
                            @default
                                {{ ucfirst($oldStatus) }}
                        @endswitch
                    </div>
                    <div style="font-size: 24px; color: #3498db;">→</div>
                    <div class="status-badge {{ strtolower($ticket->status) }}">
                        @switch($ticket->status)
                            @case('open')
                                ⭕ Abierto
                                @break
                            @case('in_progress')
                                🔄 En Progreso
                                @break
                            @case('done')
                                ✅ Completado
                                @break
                            @default
                                {{ ucfirst($ticket->status) }}
                        @endswitch
                    </div>
                </div>
            </center>

            <!-- Ticket Summary -->
            <div class="ticket-summary {{ strtolower($ticket->status) }}">
                <div class="ticket-item">
                    <span class="ticket-label">Ticket #</span>
                    <span class="ticket-value"><strong>{{ $ticket->id }}</strong></span>
                </div>
                <div class="ticket-item">
                    <span class="ticket-label">Título</span>
                    <span class="ticket-value">{{ $ticket->title }}</span>
                </div>
                <div class="ticket-item">
                    <span class="ticket-label">Proyecto</span>
                    <span class="ticket-value">{{ $ticket->project->name ?? 'N/A' }}</span>
                </div>
                <div class="ticket-item">
                    <span class="ticket-label">Categoría</span>
                    <span class="ticket-value">{{ $ticket->category ?? 'Sin categoría' }}</span>
                </div>
                <div class="ticket-item">
                    <span class="ticket-label">Prioridad</span>
                    <span class="ticket-value">
                        <span class="priority-{{ strtolower($ticket->priority ?? 'low') }}">
                            {{ ucfirst($ticket->priority ?? 'Baja') }}
                        </span>
                    </span>
                </div>
                <div class="ticket-item">
                    <span class="ticket-label">Fecha Actualización</span>
                    <span class="ticket-value">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="divider"></div>

            <!-- Status Description -->
            <div class="status-description {{ strtolower($ticket->status) }}">
                <strong>Nuevo Estado:</strong><br>
                @switch($ticket->status)
                    @case('open')
                        El ticket ha sido reabierto o está pendiente de comenzar. Puedes ver los detalles y comenzar a trabajar en él cuando sea necesario.
                        @break
                    @case('in_progress')
                        El ticket está actualmente en progreso. Se está trabajando en él y debe completarse pronto.
                        @break
                    @case('done')
                        ¡Excelente! El ticket ha sido marcado como completado. El trabajo ha sido finalizado exitosamente.
                        @break
                    @default
                        El estado del ticket ha sido actualizado a {{ ucfirst($ticket->status) }}.
                @endswitch
            </div>

            <p class="message">
                <strong>Descripción breve:</strong><br>
                {{ Str::limit($ticket->description, 150, '...') }}
            </p>

            <p class="message" style="color: #3498db; font-style: italic;">
                Para ver todos los detalles del ticket, haz clic en el botón de abajo:
            </p>

            <center>
                <a href="{{ route('user.projects.tickets.show', [$ticket->project_id, $ticket->id]) }}" class="cta-button {{ strtolower($ticket->status) }}">
                    Ver Ticket Completo
                </a>
            </center>

            <p class="message" style="margin-top: 30px; font-size: 13px;">
                Si tienes preguntas o necesitas más información sobre este cambio, puedes ponerte en contacto con el administrador del proyecto.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ now()->year }} {{ config('app.name') }} - Sistema de Gestión de Tickets</p>
            <p style="margin-top: 10px;">
                <a href="{{ route('dashboard') }}">Panel de Control</a> | 
                <a href="{{ route('user.projects.tickets.show', [$ticket->project_id, $ticket->id]) }}">Ver Ticket</a>
            </p>
        </div>
    </div>
</body>
</html>
