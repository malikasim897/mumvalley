<div class="row">
    <div class="col-md-12 d-flex justify-content-end">
        {{-- <button class="btn btn-primary"
            onclick="reloadLabel(5440,'#row_5440')">Reload</button> --}}
        <button class="btn btn-sm btn-primary me-2" id="reloadLabel"
           data-id="{{$order->id}}">Reload</button>
        <button
            onclick="window.open('{{ $label['data']['url'] }}','','top:0,left:0,width:600px;height:700px;')"
            class="btn btn-sm btn-primary">Print/Download</button>
    </div>
</div>
<div class="label mt-2">
    <iframe  id="myFrame" src="https://docs.google.com/gview?url={{$label['data']['url']}}/&embedded=true" style="width:100%; height:700px;" frameborder="0" sandbox="allow-same-origin allow-scripts"></iframe>
</div>
