<div ng-show="active=='anexos'">
    <?php $i=1; ?>
    @forelse($attachments as $attachment)
        <div class="row">
            @if(is_null($attachment->id))
                <!-- attachment Form Input -->
                <div class="form-group col-sm-5">
                    {!! Form::label('file',trans('modelAttachment.attributes.file',['numero'=>$i])) !!}
                    {!! Form::file('file'.$i, ['class'=>'form-control']) !!}
                </div>
            @else
                <div class="col-sm-5">
                    {{ trans('modelAttachment.attributes.file',['numero'=>$i]) }} -
                    {!! link_to_route('attachment',$attachment->file,isset($host)?[$host,$attachment->file]:[$attachment->file],['target'=>'_blank', 'title'=>$attachment->file]) !!}
                </div>
            @endif

        </div>
        <?php $i++; ?>
    @empty
        <div>{{ trans('order.listaAnexosEmpty') }}</div>
    @endforelse
</div>