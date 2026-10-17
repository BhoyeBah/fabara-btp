@if (session('success') || $errors->any())
    <div class="wrap flash-zone">
        @if (session('success'))
            <div class="flash flash-ok" role="status">{{ session('success') }}</div>
        @endif
        @if ($errors->any())
            <div class="flash flash-bad" role="alert">
                <div>
                    <b>Vérifiez le formulaire :</b>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif
    </div>
@endif
