<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Asignado</title>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
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
            color: #667eea;
        }
        .ticket-summary {
            background-color: #f9f9f9;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin: 25px 0;
            border-radius: 4px;
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
            color: #667eea;
            min-width: 120px;
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
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 12px 40px;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin: 25px 0;
            text-align: center;
            transition: transform 0.3s ease;
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
            color: #667eea;
            text-decoration: none;
        }
        .divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #667eea, transparent);
            margin: 30px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎯 Nuevo Ticket Asignado</h1>
            <p>Se te ha asignado una nueva tarea</p>
        </div>

        <!-- Content -->
        <div class="content">
            <div class="greeting">
                Hola <strong>{{ $user->nombre }}</strong>,
            </div>

            <p class="message">
                Te informamos que se te ha asignado un nuevo ticket que requiere tu atención. A continuación encontrarás los detalles principales:
            </p>

            <!-- Ticket Summary -->
            <div class="ticket-summary">
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
                    <span class="ticket-label">Fecha Asignación</span>
                    <span class="ticket-value">{{ now()->format('d/m/Y H:i') }}</span>
                </div>
            </div>

            <div class="divider"></div>

            <p class="message">
                <strong>Descripción breve:</strong><br>
                {{ Str::limit($ticket->description, 150, '...') }}
            </p>

            <p class="message" style="color: #667eea; font-style: italic;">
                Para ver todos los detalles del ticket y comenzar a trabajar en él, haz clic en el botón de abajo:
            </p>

            <center>
                <a href="{{ route('user.projects.tickets.show', $ticket->project_id, $ticket->id) }}" class="cta-button">
                    Ver Ticket Completo
                </a>
            </center>

            <p class="message" style="margin-top: 30px; font-size: 13px;">
                Si tienes preguntas o necesitas ayuda, no dudes en ponerte en contacto con el administrador del proyecto.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ now()->year }} {{ config('app.name') }} - Sistema de Gestión de Tickets</p>
            <p style="margin-top: 10px;">
                <a href="{{ route('dashboard') }}">Panel de Control</a> | 
                <a href="{{ route('profile.show') }}">Mi Perfil</a>
            </p>
        </div>
    </div>
</body>
</html>