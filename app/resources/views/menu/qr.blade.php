<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<style>
    * { margin:0; padding:0; box-sizing:border-box; }
    body { font-family: sans-serif; background: #f9fafb; }
    .grid { display: flex; flex-wrap: wrap; gap: 20px; padding: 20px; }
    .card { background: white; border: 1px solid #e5e7eb; border-radius: 12px; padding: 20px; text-align: center; width: 180px; }
    .card h3 { font-size: 14px; font-weight: bold; margin-bottom: 10px; }
    .card p { font-size: 11px; color: #6b7280; margin-top: 8px; }
    @media print { body { background: white; } }
</style>
</head>
<body>
<div style="padding:20px">
    <h1 style="font-size:20px; font-weight:bold; margin-bottom:5px;">📱 QR Kodlar — Barcha Stollar</h1>
    <p style="font-size:12px; color:#6b7280; margin-bottom:20px;">Mehmon stol QR sini scan qilsa, menyu ochiladi</p>
    <div class="grid">
        @foreach($tables as $table)
        <div class="card">
            <h3>Stol {{ $table->number }}</h3>
            <div>{!! $table->qrCode !!}</div>
            <p>{{ $table->capacity }} kishi · {{ $table->location->label() }}</p>
        </div>
        @endforeach
    </div>
</div>
</body>
</html>
