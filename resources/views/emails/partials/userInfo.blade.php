<p><img src="{{ $user->avatar }}" style="
padding: 4px;
line-height: 1.42857143;
background-color: #ffffff;
border: 1px solid #dddddd;
border-radius: 4px;
transition: all 0.2s ease-in-out;
display: inline-block;
max-width: 100%;
height: auto;"></p>
<p>Usuário {{ $user->name }} criado em {{ Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</p>
<p>Data de Nascimento: {{ $user->partner->data_nascimento }}</p>
<p>E-mail: {{ $user->email }}</p>
<p>Provider: {!! link_to('http://facebook.com/'.$user->provider_id, ucfirst($user->provider)) !!}</p>

@if(count($user->partner->addresses)>0)
    @foreach($user->partner->addresses as $addr)
        <p>Endereço: {{ $addr->endereco }}</p>
    @endforeach
@endif

@if(count($user->partner->contacts)>0)
    @foreach($user->partner->contacts as $contact)
        <p>{{ ucfirst($contact->contact_type) }}: {{ $contact->contact_data }}</p>
    @endforeach
@endif

@if(count($user->partner->documents)>0)
    @foreach($user->partner->documents as $document)
        <p>{{ ucfirst($document->document_type) }}: {{ $document->document_data }}</p>
    @endforeach
@endif

<p>Grupos: {{ $user->partner->group_list }}</p>
<p>Status: {{ $user->partner->status_list }}</p>