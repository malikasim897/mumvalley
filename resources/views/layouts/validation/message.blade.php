@if ($errors->any())
<div class="alert alert-danger px-3 p-1">
    <ul>
        @foreach ($errors->all() as $error)
        <li style="list-style:initial;">{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
