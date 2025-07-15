@if ($firma == 1)
    <footer style="position: fixed; bottom: 60px; left: 0; right: 0; font-size: 10px; text-align: center;">
        ___________________________<br>
        <strong>{{ $user->nombre_completo }}</strong><br>
        Firma del profesional
    </footer>
@endif