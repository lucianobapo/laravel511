<meta property="og:url" content="{{ config('delivery.siteCurrentUrl') }}"/>
<meta property="og:type" content="website"/>
<meta property="og:title" content="{{ trans('delivery.head.title') }}"/>
<meta property="og:image" content="{{ config('delivery.siteCurrentUrl').'/img/logo.png' }}"/>
<meta property="og:description" content="{{ trans('delivery.head.metaDescription') }}"/>
<meta property="og:updated_time" content="{{ Carbon\Carbon::now()->timestamp }}"/>