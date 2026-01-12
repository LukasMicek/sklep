<p>Dziękujemy za zamówienie #{{ $order->id }}.</p>

<p>Status: {{ $order->status }}</p>

<p>Pozycje:</p>
<ul>
    @foreach ($order->items as $it)
        <li>
            {{ $it->product_name }} x {{ $it->quantity }}
            = {{ number_format($it->line_total_cents/100, 2, ',', ' ') }} zł
        </li>
    @endforeach
</ul>

<p><strong>Suma:</strong> {{ number_format($order->total_cents/100, 2, ',', ' ') }} zł</p>
