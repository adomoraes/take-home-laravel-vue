<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Solicitação de Exames</title>
    <style>
        /* Define o encoding para o PDF (importante para acentos) */
        @page {
            margin: 20px 25px;
            font-family: Arial, sans-serif;
        }
        
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        /* Estilo da "página" que será impressa */
        .pagina {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            margin-bottom: 20px;
            box-sizing: border-box;
        }

        /* Estilo de quebra de página */
        .quebra-pagina {
            page-break-after: always;
        }

        /* Cabeçalho */
        .cabecalho {
            display: block;
            text-align: center;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        
        h1, h2, h3 {
            margin: 0;
            padding: 0;
        }
        
        h1 {
            font-size: 18px;
        }
        
        h2 {
            font-size: 16px;
            color: #444;
            background-color: #f4f4f4;
            padding: 5px;
            margin-top: 15px;
        }

        /* Tabela de Exames */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        th, td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }
        
        th {
            background-color: #f9f9f9;
        }

        /* Informações do Paciente/Médico */
        .info-paciente {
            width: 100%;
            font-size: 13px;
            margin-bottom: 10px;
        }

    </style>
</head>
<body>

    @if (empty($paginas))
        <div class="pagina">
            <h1>Solicitação de Exames</h1>
            <p>Nenhum exame ou pacote foi selecionado para impressão.</p>
        </div>
    @endif

    {{-- LOOP 1: Iterar sobre cada PÁGINA (Grupo 1, Grupo 2, etc.) --}}
    @foreach ($paginas as $nomePagina => $pacotesNaPagina)
        
        <div class="pagina">
            <div class="cabecalho">
                <h1>Solicitação de Exames - {{ $nomePagina }}</h1>
            </div>

            <div class="info-paciente">
                <strong>Paciente:</strong> {{ $dadosMockados['paciente'] }} <br>
                <strong>Médico(a):</strong> {{ $dadosMockados['medico'] }} <br>
                <strong>Data:</strong> {{ $dadosMockados['data'] }}
            </div>

            {{-- LOOP 2: Iterar sobre cada PACOTE (Exames Avulsos, Pacote Glaucoma) --}}
            @foreach ($pacotesNaPagina as $nomePacote => $examesNoPacote)
                
                <h2>{{ $nomePacote }}</h2>
                
                @if ($nomePacote !== 'Exames avulsos' && isset($examesNoPacote[0]->pacote) && $examesNoPacote[0]->pacote->observations)
                    {{-- Esta lógica é um exemplo. Para funcionar 100%, o service teria de ajustar a estrutura. --}}
                    {{-- <p><strong>Observações do Pacote:</strong> ... </p> --}}
                @endif
                
                <table>
                    <thead>
                        <tr>
                            <th>Exame</th>
                            <th>Lateralidade</th>
                            <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- LOOP 3: Iterar sobre cada EXAME dentro do pacote --}}
                        @foreach ($examesNoPacote as $exame)
                            <tr>
                                <td style="width: 30%;">{{ $exame->name }}</td>
                                <td style="width: 15%;">{{ $exame->laterality ?? 'N/A' }}</td>
                                <td>{{ $exame->comment }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @endforeach
            {{-- Fim do Loop 2 --}}
        
        </div>

        {{-- IMPORTANTE: Adiciona uma quebra de página, exceto na última página --}}
        @if (!$loop->last)
            <div class="quebra-pagina"></div>
        @endif

    @endforeach
    {{-- Fim do Loop 1 --}}

</body>
</html>