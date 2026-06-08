<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório da Obra - {{ $construction->construction_name ?? 'N/A' }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 13px;
            line-height: 1.5;
        }
        .header {
            width: 100%;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 15px;
            margin-bottom: 25px;
        }
        .header table {
            width: 100%;
        }
        .logo-title {
            font-size: 24px;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
        }
        .report-date {
            text-align: right;
            font-size: 11px;
            color: #666;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
            background-color: #f1f5f9;
            padding: 6px 10px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-left: 4px solid #0284c7;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table.data-table th, table.data-table td {
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
        }
        table.data-table th {
            font-weight: bold;
            color: #475569;
            width: 30%;
            background-color: #fafafa;
        }
        table.data-table td {
            color: #334155;
        }
        .notes-box {
            border: 1px solid #e2e8f0;
            padding: 12px;
            background-color: #f8fafc;
            border-radius: 4px;
            min-height: 80px;
            white-space: pre-wrap;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="logo-title">Ficha da Obra</div>
                    <span style="color: #64748b;">Gerenciamento de Construções</span>
                </td>
                <td class="report-date">
                    Emitido em: {{ date('d/m/Y H:i') }}<br>
                    Responsável: {{ $construction->user->name ?? 'Não informado' }}
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Informações</div>
    <table class="data-table">
        <tr>
            <th>Nome da Obra:</th>
            <td>{{ $construction->construction_name ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <th>Tipo de Obra:</th>
            <td>{{ $construction->type ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <th>Status Atual:</th>
            <td>
                <strong>{{ $construction->status ?? 'Não informado' }}</strong>
            </td>
        </tr>
        <tr>
            <th>Volume ($m^3$):</th>
            <td>{{ $construction->volume ? number_format($construction->volume, 2, ',', '.') . ' m³' : 'Não informado' }}</td>
        </tr>
        <tr>
            <th>Endereço:</th>
            <td>{{ $construction->address ?? 'Não informado' }}</td>
        </tr>
    </table>

    <div class="section-title">Responsáveis e Contatos</div>
    <table class="data-table">
        <tr>
            <th>Construtor:</th>
            <td>{{ $construction->builder_name ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <th>CPF / CNPJ:</th>
            <td>{{ $construction->cpf_cnpj ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <th>Telefone Construtor:</th>
            <td>{{ $construction->builder_phone ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <th>Mestre de Obras:</th>
            <td>{{ $construction->sitemanager_name ?? 'Não informado' }}</td>
        </tr>
        <tr>
            <th>Telefone Mestre:</th>
            <td>{{ $construction->sitemanager_phone ?? 'Não informado' }}</td>
        </tr>
    </table>

    <div class="section-title">Cronograma</div>
    <table class="data-table">
        <tr>
            <th>Data de início:</th>
            <td>{{ $construction->start_date ? \Carbon\Carbon::parse($construction->start_date)->format('d/m/Y') : 'Não informada' }}</td>
        </tr>
        <tr>
            <th>Data de previsão de finalização:</th>
            <td>{{ $construction->finish_date ? \Carbon\Carbon::parse($construction->finish_date)->format('d/m/Y') : 'Não informada' }}</td>
        </tr>
    </table>

    <div class="section-title">Observações / Notas Adicionais</div>
    @if($construction->notes)
        <div class="notes-box">{{ $construction->notes }}</div>
    @else
        <p style="color: #94a3b8; font-style: italic;">Nenhuma observação registrada para esta obra.</p>
    @endif

    <div class="footer">
        Relatório gerado automaticamente pelo sistema ClienteObra.
    </div>

</body>
</html>