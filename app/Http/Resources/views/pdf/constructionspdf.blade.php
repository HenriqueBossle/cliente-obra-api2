<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Relatório Geral de Obras (Completo)</title>
    <style>
        /* Configura a página para modo Paisagem (Landscape) para caber todas as colunas */
        @page {
            size: landscape;
            margin: 20px;
        }
        
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            font-size: 10px; /* Reduzido levemente para otimizar o espaço das colunas */
            line-height: 1.3;
        }
        
        .header {
            width: 100%;
            border-bottom: 2px solid #0284c7;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        .header table {
            width: 100%;
            border-collapse: collapse;
        }
        .header td {
            border: none;
            padding: 0;
        }
        .logo-title {
            font-size: 18px;
            font-weight: bold;
            color: #0284c7;
            text-transform: uppercase;
        }
        .report-subtitle {
            color: #64748b;
            font-size: 11px;
        }
        .report-date {
            text-align: right;
            font-size: 9px;
            color: #666;
            vertical-align: bottom;
        }
        
        /* Tabela Principal */
        table.main-table {
            width: 100%;
            border-collapse: collapse;
        }
        table.main-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
            padding: 6px 4px;
            text-align: left;
            border: 1px solid #0f172a;
        }
        table.main-table td {
            padding: 6px 4px;
            border-bottom: 1px solid #e2e8f0;
            border-left: 1px solid #f1f5f9;
            border-right: 1px solid #f1f5f9;
            color: #334155;
            vertical-align: top;
            word-wrap: break-word; /* Evita que textos longos quebrem o layout */
        }
        
        /* Zebra striping */
        table.main-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }
        
        /* Utilitários de texto */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        
        /* Pequeno bloco para notas não estourar a célula */
        .notes-cell {
            font-size: 8.5px;
            color: #64748b;
            max-width: 150px;
        }

        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
            border-top: 1px solid #e2e8f0;
            padding-top: 4px;
        }
    </style>
</head>
<body>

    <div class="header">
        <table>
            <tr>
                <td>
                    <div class="logo-title">Relatório Consolidado de Obras</div>
                    <div class="report-subtitle">Exibição de todos os dados cadastrais</div>
                </td>
                <td class="report-date">
                    Gerado em: {{ date('d/m/Y H:i') }}<br>
                    Total de Registros: {{ count($constructions) }}
                </td>
            </tr>
        </table>
    </div>

    <table class="main-table">
        <thead>
            <tr>
                <th class="text-center" style="width: 3%;">ID</th>
                <th style="width: 12%;">Obra / Tipo</th>
                <th style="width: 12%;">Construtor (CPF/CNPJ)</th>
                <th style="width: 9%;">Tel. Construtor</th>
                <th style="width: 12%;">Mestre de Obra</th>
                <th style="width: 9%;">Tel. Mestre</th>
                <th style="width: 13%;">Endereço</th>
                <th class="text-center" style="width: 7%;">Datas (Início/Fim)</th>
                <th class="text-center" style="width: 6%;">Status</th>
                <th class="text-right" style="width: 6%;">Vol. ($m³$)</th>
                <th style="width: 11%;">Observações</th>
            </tr>
        </thead>
        <tbody>
            @forelse($constructions as $construction)
                <tr>
                    <td class="text-center font-bold">
                        {{ $construction->id }}
                        <br><small style="color: #94a3b8; font-weight: normal;">User: #{{ $construction->user_id }}</small>
                    </td>

                    <td>
                        <span class="font-bold" style="color: #0284c7;">{{ $construction->construction_name ?? '-' }}</span>
                        <br><small style="color: #64748b;">{{ $construction->type ?? 'Não especificado' }}</small>
                    </td>

                    <td>
                        {{ $construction->builder_name ?? '-' }}
                        @if($construction->cpf_cnpj)
                            <br><small style="color: #64748b;">Doc: {{ $construction->cpf_cnpj }}</small>
                        @endif
                    </td>

                    <td>{{ $construction->builder_phone ?? '-' }}</td>

                    <td>{{ $construction->sitemanager_name ?? '-' }}</td>

                    <td>{{ $construction->sitemanager_phone ?? '-' }}</td>

                    <td>{{ $construction->address ?? '-' }}</td>

                    <td class="text-center">
                        <span style="color: #16a34a;">I: {{ $construction->start_date ? \Carbon\Carbon::parse($construction->start_date)->format('d/m/Y') : '-' }}</span>
                        <br>
                        <span style="color: #dc2626;">F: {{ $construction->finish_date ? \Carbon\Carbon::parse($construction->finish_date)->format('d/m/Y') : '-' }}</span>
                    </td>

                    <td class="text-center font-bold">
                        <span style="text-transform: uppercase; font-size: 8.5px;">{{ $construction->status ?? '-' }}</span>
                    </td>

                    <td class="text-right font-bold">
                        {{ $construction->volume ? number_format($construction->volume, 2, ',', '.') : '-' }}
                    </td>

                    <td class="notes-cell">
                        {{ $construction->notes ? Str::limit($construction->notes, 80, '...') : '-' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center" style="color: #94a3b8; padding: 25px; font-style: italic; font-size: 12px;">
                        Nenhuma obra encontrada no banco de dados.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Relatório de Obras Completo - Emitido por ambiente corporativo.
    </div>

</body>
</html>