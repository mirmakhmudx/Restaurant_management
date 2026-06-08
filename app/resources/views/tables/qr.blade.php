<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>QR Kodlar — BitePlate</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: sans-serif; background:#f3f4f6; padding:24px; }
        .header { text-align:center; margin-bottom:24px; }
        .header h1 { font-size:24px; font-weight:bold; }
        .header p { font-size:13px; color:#6b7280; margin-top:4px; }
        .grid { display:flex; flex-wrap:wrap; gap:16px; justify-content:center; }
        .card { background:white; border:1px solid #e5e7eb; border-radius:16px; padding:20px; text-align:center; width:180px; }
        .card .table-num { font-size:28px; font-weight:bold; color:#111; margin-bottom:12px; }
        .card .qr { margin:0 auto 12px; }
        .card .info { font-size:11px; color:#6b7280; line-height:1.5; }
        .card .location { display:inline-block; margin-top:6px; font-size:10px; background:#f3f4f6; padding:3px 8px; border-radius:20px; color:#374151; font-weight:600; }
        .print-btn { position:fixed; top:20px; right:20px; background:#111; color:white; border:none; padding:10px 20px; border-radius:10px; cursor:pointer; font-size:13px; font-weight:bold; }
        .url { font-size:9px; color:#9ca3af; word-break:break-all; margin-top:6px; }
        @media print {
            body { background:white; padding:10px; }
            .print-btn { display:none; }
            .grid { gap:10px; }
            .card { break-inside:avoid; }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨 Chop etish</button>

<div class="header">
    <h1>🍽 BitePlate — QR Kodlar</h1>
    <p>Har bir stolning QR kodini chop eting va stol ustiga qo'ying</p>
    <p style="font-size:11px;color:#9ca3af;margin-top:4px;">Mehmon QR ni scan qilsa → Menyu ochiladi</p>
</div>

<div class="grid">
    @foreach($tables as $table)
    <div class="card">
        <div class="table-num">{{ $table->number }}</div>
        <div class="qr">
            {!! $table->qrSvg !!}
        </div>
        <div class="info">
            <strong>Stol {{ $table->number }}</strong><br>
            {{ $table->capacity }} kishi
            <br>
            <span class="location">{{ $table->location->label() }}</span>
            <div class="url">{{ url('/public-menu?table='.$table->id) }}</div>
        </div>
    </div>
    @endforeach
</div>

</body>
</html>
