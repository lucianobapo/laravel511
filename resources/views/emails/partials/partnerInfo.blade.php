<p>Usuário {{ $partner->nome }} criado em {{ Carbon\Carbon::parse($partner->created_at)->format('d/m/Y H:i') }}</p>
<p>Data de Nascimento: {{ $partner->data_nascimento }}</p>

@foreach($partner->addresses as $addr)
    <p>Endereço: {{ $addr->endereco }}</p>
@endforeach

@foreach($partner->contacts as $contact)
    <p>{{ ucfirst($contact->contact_type) }}: {{ $contact->contact_data }}</p>
@endforeach

@foreach($partner->documents as $document)
    <p>{{ ucfirst($document->document_type) }}: {{ $document->document_data }}</p>
@endforeach

<p>Grupos: {{ $partner->group_list }}</p>
<p>Status: {{ $partner->status_list }}</p>