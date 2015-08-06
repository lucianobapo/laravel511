@extends('gallery.app')
@section('content')
    <!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
    <div id="blueimp-gallery" class="blueimp-gallery" data-use-bootstrap-modal="true">
        <!-- The container for the modal slides -->
        <div class="slides"></div>
        <!-- Controls for the borderless lightbox -->
        <h3 class="title"></h3>
        <a class="prev">‹</a>
        <a class="next">›</a>
        <a class="close">×</a>
        <a class="play-pause"></a>
        <ol class="indicator"></ol>
        <!-- The modal dialog, which will be used to wrap the lightbox content -->
        <div class="modal fade">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" aria-hidden="true">&times;</button>
                        <h4 class="modal-title"></h4>
                    </div>
                    <div class="modal-body next"></div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left prev">
                            <i class="glyphicon glyphicon-chevron-left"></i>
                            {{ trans('pagination.previousText') }}
                        </button>
                        <button type="button" class="btn btn-primary next">
                            {{ trans('pagination.nextText') }}
                            <i class="glyphicon glyphicon-chevron-right"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="links" class="row">
        <div class="well col-sm-2"><a href="http://delivery.localhost.com/images/imagem-de-porcao-de-batata-frita.png" title="Banana" data-gallery>
                <img src="http://delivery.localhost.com/images/imagem-de-porcao-de-batata-frita.png" alt="Banana">
            </a></div>
        <div class="well col-sm-2"><a href="images/apple.jpg" title="Apple" data-gallery>
                <img src="images/thumbnails/apple.jpg" alt="Apple">
            </a></div>
        <div class="well col-sm-2"><a href="images/orange.jpg" title="Orange" data-gallery>
                <img src="images/thumbnails/orange.jpg" alt="Orange">
            </a></div>
    </div>
@endsection